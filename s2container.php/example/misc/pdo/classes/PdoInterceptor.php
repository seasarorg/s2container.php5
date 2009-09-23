<?php
/**
 * @S2Component('name'      => 'interceptor',
 *              'namespace' => 'pdo');
 */
class PdoInterceptor implements \seasar\aop\MethodInterceptor {
    /**
     * @var string
     */
     public static $MODEL_CLASS = 'StandardDto';

    /**
     * DTO('DTOクラス名');
     */
    const ANNOTATION = 'DTO';

    /**
     * @var PDO
     */
    private $pdo = null;

    /**
     * @param PDO $pdo
     */
    public function setPdo(\PDO $pdo) {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @see \seasar\aop\MethodInterceptor::invoke()
     */
    public function invoke(\seasar\aop\MethodInvocation $invocation) {
        $result = null;
        if (!$invocation->getMethod()->isAbstract()) {
            $result = $invocation->proceed();
        }
        return $this->execute($result, $invocation);
    }

    /**
     * データベースにSQLクエリを発行し結果を返します。SQLクエリの発行はプリペアドステートメントで実施されます。
     * プリペアドステートメントに渡すパラメータは、名前つきプレースホルダが使用されます。
     *
     * @param mixed $result 配列の場合は、1番目の値がSQLクエリ、2番目の値がbind値の配列になります。
     *                      文字列の場合は、SQLクエリとして扱われます。この場合、bind値の配列にはメソッド引数が使用されます。
     *                      null値の場合は、SQLファイルを検索してクエリを取得します。
     * @param \seasar\aop\MethodInvocation $invocation
     * @resutl array 発行されたSQLクエリがselect文の場合は、PDOStatement->fetchAll()結果配列を返します。
     *               それ以外のSQLクエリが発行された場合は次の配列を返します。
     *               array('last_insert_id' => $pdo->lastInsertId(),
     *                     'affected_rows'  => $stmt->rowCount());
     * @throw Exception SQLクエリがnullの場合にスローされます。
     */
    private function execute($result, \seasar\aop\MethodInvocation $invocation) {
        list($query, $context) = $this->inspectResult($result);

        if (is_null($context)) {
            $context = $this->getContext($invocation->getMethod(), $invocation->getArguments());
        }

        if (is_null($query)) {
            $query = $this->getQueryFromSqlFile($invocation->getTargetClass(), $invocation->getMethod(), $context);
        }
        if (!is_string($query)) {
            throw new Exception("invalid sql found. [$query]");
        }
        $query = trim($query);


        if (\seasar\Config::$DEBUG_VERBOSE) {
            \seasar\log\S2Logger::getInstance(__NAMESPACE__)->debug('prepared sql [' . $this->queryToString($query) . ']', __METHOD__);
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->getDtoClass($invocation->getTargetClass(), $invocation->getMethod()));
        $binded = $this->bindValue($stmt, $query, $context);
        if (\seasar\Config::$DEBUG_VERBOSE) {
            \seasar\log\S2Logger::getInstance(__NAMESPACE__)->debug($this->bindValuesToString($binded), __METHOD__);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if (!preg_match('/^select/si', $query) and count($rows) == 0) {
          return $stmt->rowCount();
        }
        return $rows;
    }

    /**
     * @param PDOStatement $stmt
     * @param string $query
     * @param array $context
     */
    private function bindValue(\PDOStatement $stmt, $query, array $context) {
        $binded = array();
        foreach($context as $key => $val) {
            if (preg_match("/:$key\W*/", $query)) {
                $type = $this->getValueType($val);
                if (!is_null($type)) {
                    if ($stmt->bindValue($key, $val, $type)) {
                        $binded[$key] = $val;
                    } else {
                        throw new Exception("illegal bind value. [" . $this->queryToString($query) . "] [:$key <- " . \seasar\util\StringUtil::mixToString($val) . "]");
                    }
                } else {
                    \seasar\log\S2Logger::getInstance(__NAMESPACE__)->info("type not applicable. ignored. [:$key <- " . \seasar\util\StringUtil::mixToString($val) . "]", __METHOD__);
                }
            } else {
                \seasar\log\S2Logger::getInstance(__NAMESPACE__)->info("placeholder not found. ignored. [:$key <- " . \seasar\util\StringUtil::mixToString($val) . "]", __METHOD__);
            }
        }
        return $binded;
    }

    /**
     * @param mixed $val
     * @return int|null
     */
    private function getValueType($val) {
        if (is_bool($val)) {
            return \PDO::PARAM_BOOL;
        } else if (is_null($val)) {
            return \PDO::PARAM_NULL;
        } else if (is_int($val) or is_float($val)) {
            return \PDO::PARAM_INT;
        } else if (is_string($val)) {
            return \PDO::PARAM_STR;
        } else {
            return null;
        }
    }

    /**
     * アスペクト対象メソッド結果から、SQLクエリとコンテキストを抽出します。
     * @param null|string|array $result
     * @return array
     */
    private function inspectResult($result) {
        $query = null;
        $context = null;
        if (is_array($result)) {
            if (count($result) == 1) {
                if (array_key_exists(0, $result)) {
                    $query = $result[0];
                } else {
                    $context = $result;
                }
            } else if (count($result) == 2) {
                if (array_key_exists(0, $result)) {
                    $query   = $result[0];
                    $context = $result[1];
                } else {
                    $context = $result;
                }
            } else {
                $context = $result;
            }
        } else if (is_string($result)) {
            $query = $result;
        } else if (is_null($result)) {
        } else {
            throw new Exception("invalid result. [$result]");
        }

        if (!is_null($query) and !is_string($query)) {
            throw new Exception("invalid query. [$query]");
        }

        if (!is_null($context) and !is_array($context)) {
            throw new Exception("invalid context. [$context]");
        }

        return array($query, $context);
    }

    /**
     * DAOメソッド呼び出しに対応するSQLファイルを読み込みます。
     * HogeDao->findById() の呼び出しの場合、SQLファイ名は、
     * /path/to/Hogeクラスファイルの場所/HogeDao_findById.sql
     *
     * @param \ReflectionClass $targetClass
     * @param \ReflectionMethod $method
     * @param array $context
     * @return string
     */
    private function getQueryFromSqlFile(\ReflectionClass $targetClass, \ReflectionMethod $method, array $context) {
        $sqlFile = dirname($targetClass->getFileName())
                 . DIRECTORY_SEPARATOR
                 . \seasar\util\ClassUtil::getClassName($targetClass->getName())
                 . '_' . $method->getName() . '.sql';
        $query = null;
        if (is_file($sqlFile) and is_readable($sqlFile)) {
            $query = SqlFileReader::read($sqlFile, $context);
        } else {
            \seasar\log\S2Logger::getInstance(__NAMESPACE__)->info("sql file found, but unreadable. [$sqlFile]", __METHOD__);
        }
        return $query;
    }

    /**
     * \ReflectionMethodから引数の名前を取得し$argsを割り当てます。
     *
     * @param \ReflectionMethod $method
     * @param array $args
     * @return array
     */
    private function getContext(\ReflectionMethod $method, array $args) {
        $context = array();
        $refParames = $method->getParameters();
        $o = count($args);
        for($i=0; $i<$o; $i++) {
            $context[$refParames[$i]->getName()] = $args[$i];
        }
        return $context;
    }

    /**
     * クラス、メソッドについている@S2Pdoアノテーションから戻り値に使用するモデルクラスを取得する。
     * アノテーションが付いていなければ、デフォルトのPdoStandardModelクラス名を返す。
     *
     * @param \ReflectionMethod $method
     * @return string
     */
    private function getDtoClass(\ReflectionClass $clazz, \ReflectionMethod $method) {
        $pdoInfo = array();
        if (\seasar\util\Annotation::has($method, self::ANNOTATION)) {
            $pdoInfo = \seasar\util\Annotation::get($method, self::ANNOTATION);
        } else if (\seasar\util\Annotation::has($clazz, self::ANNOTATION)) {
            $pdoInfo = \seasar\util\Annotation::get($clazz, self::ANNOTATION);
        }
        if (isset($pdoInfo[0])) {
            return $pdoInfo[0];
        }
        return self::$MODEL_CLASS;
    }

    /**
     * SQLクエリをログ出力用の文字列に変換します。
     *
     * @param string $query
     * @return string
     */
    private function queryToString($query) {
        $query = preg_replace('/[\r\n]+/s', ' ', $query);
        $query = preg_replace('/\s+/', ' ', $query);
        return trim($query);
    }

    /**
     * BindValueをログ出力用の文字列に変換します。
     *
     * @param array $bindValues
     * @return string
     */
    private function bindValuesToString(array $bindValues) {
        $items = array();
        foreach($bindValues as $ph => $value) {
            $items[] = $ph . ' => ' . \seasar\util\StringUtil::mixToString($value);
        }
        return 'bind values (' . implode(', ', $items) . ')';
    }
}
