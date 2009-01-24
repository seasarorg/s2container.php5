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
     * @var \seasar\container\S2Container
     */
    public $container = 's2binding';

    /**
     * @S2Pdo('pdo' => 'PDOコンポーネントの名前');
     */
    const ANNOTATION = '@S2Pdo';

    /**
     * @var PDO
     */
    private $pdo = null;

    /**
     * @see \seasar\aop\MethodInterceptor::invoke()
     */
    public function invoke(\seasar\aop\MethodInvocation $invocation) {
        if ($invocation->getMethod()->isAbstract()) {
            $result = null;
        } else {
            $result = $invocation->proceed();
        }
        return $this->execute($result, $invocation);
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
    public function getQueryFromSqlFile(\ReflectionClass $targetClass, \ReflectionMethod $method, array $context) {
        $sqlFile = dirname($targetClass->getFileName())
                 . DIRECTORY_SEPARATOR
                 . \seasar\util\ClassUtil::getClassName($targetClass->getName())
                 . '_' . $method->getName() . '.sql';
        $query = null;
        if (is_file($sqlFile) and is_readable($sqlFile)) {
            $query = SqlFileReader::read($sqlFile, $context);
        }
        return $query;
    }

    /**
     * アンダースコアで区切られた文字列をもとに、オブジェクトから値を取得します。
     * 文字列が condition_id で、conditionがインスタンスの場合は、そのインスタンスのidメソッド、または
     * idプロパティを返します。($condition->id() or $condition->id)
     * 文字列が condition_id で、conditionが配列の場合は、その配列のキーをidとして値を返します。
     * ($condition['id'])
     *
     * 文字列が condition_id_abc で、conditionとidがインスタンスの場合は、そのインスタンスのabcメソッド、
     * または idプロパティを返します。($condition->id()->abc() or $condition->id->abc())
     * 文字列が condition_id_0 で、conditionとidが配列の場合は、その配列のキーを0として値を返します。
     * ($condition['id'][0])
     *
     * 文字列が condition_id_abc で、conditionが配列でcondition['id']がインスタンスの場合、
     * icondition['id']のabcメソッド、またはabcプロパティを返します。
     * ($condition['id']->abc() $condition['id']->abc)
     *
     * @param object $obj
     * @param array $items
     * @return mixed
     */
    public function resolveValue($obj, array $items) {
        if (is_array($obj)) {
            if (isset($obj[$items[0]])) {
                if (count($items) > 1) {
                    $index = array_shift($items);
                    return $this->resolveValue($obj[$index], $items);
                } else { 
                    return $obj[$items[0]];
                }
            } else {
                return null;
            }
        }
        else if (is_object($obj)) {
            $curObj = null;
            if (in_array($items[0], get_class_methods($obj))) {
                $methodName = $items[0];
                $curObj = $obj->$methodName();
            } else if (property_exists($obj, $items[0])){
                $curObj = $obj->$items[0];
            }
            if ($curObj === null) {
                return null;
            }
            if (count($items) > 1) {
                array_shift($items);
                return $this->resolveValue($curObj, $items);
            } else { 
                return $curObj;
            }
        }
        return null;
    }

    /**
     * \ReflectionMethodから引数の名前を取得し$argsを割り当てます。
     *
     * @param \ReflectionMethod $method
     * @param array $args
     * @return array
     */
    public function getContext(\ReflectionMethod $method, array $args) {
        $context = array();
        $refParames = $method->getParameters();
        $o = count($args);
        for($i=0; $i<$o; $i++) {
            $context[$refParames[$i]->getName()] = $args[$i];
        }
        return $context;
    }

    /**
     * S2ContainerからPDOコンポーネントを取得します。コンテナにPDOコンポーネントが1つだけ存在する場合は、
     * そのインスタンスを$this->pdoに保持します。コンテナに複数のPDOコンポーネントが存在する場合は、
     * クラスとメソッドに付いている@S2Pdoアノテーションのpdo属性値をキーとしてコンテナから取得します。
     *
     * @param \ReflectionClass $targetClass
     * @param \ReflectionMethod $method
     * @return PDO
     * @throw Exception PDOコンポーネントが存在しなかった場合にスローされます。
     */
    public function getPdo(\ReflectionClass $targetClass, \ReflectionMethod $method) {
        if ($this->pdo instanceof PDO) {
            return $this->pdo;
        }

        $cd = $this->container->getComponentDef('PDO');
        if (! $cd instanceof seasar\container\impl\TooManyRegistrationComponentDef) {
            $this->pdo = $cd->getComponent();
            return $this->pdo;
        }

        if (\seasar\util\Annotation::has($method, self::ANNOTATION)) {
            $pdoInfo = \seasar\util\Annotation::get($method, self::ANNOTATION);
            if (isset($pdoInfo['pdo'])) {
                return $this->container->getComponent($pdoInfo['pdo']);
            }
        }
        if (\seasar\util\Annotation::has($targetClass, self::ANNOTATION)) {
            $pdoInfo = \seasar\util\Annotation::get($targetClass, self::ANNOTATION);
            if (isset($pdoInfo['pdo'])) {
                return $this->container->getComponent($pdoInfo['pdo']);
            }
        }
        throw new Exception('two or more PDO found, not specified.');
    }

    /**
     * SQLクエリを整形し、プレースホルダーを取得します。
     *
     * @param string $query
     * @return array
     */
    public function setupQuery($query) {
        $query = trim($query);
        $query = preg_replace('/(\/\*:.+?\s*\*\/)\'[^\']*\'(\s*,*\s*)/su', '\1\2', $query);   // /*:id*/'aaa' => /*:id*/
        $query = preg_replace('/(\/\*:.+?\s*\*\/)[^(\s|,)]*(\s*,*\s*)/su', '\1\2', $query);   // /*:id*/5,    => /*:id*/,
        $reg = '/\/\*:(.+?)\s*\*\/(\s*,*\s*)/su';
        $matches = array();
        $placeHolders = array();
        if (preg_match_all($reg, $query, $matches)) {
            $placeHolders = $matches[1];
        }
        $query = preg_replace($reg, ':\1\2', $query);
        if (\seasar\Config::$DEBUG_VERBOSE) {
            \seasar\log\S2Logger::getInstance(__NAMESPACE__)->debug($this->queryToString($query), __METHOD__);
        }
        return array($query, $placeHolders);
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
    protected function execute($result, \seasar\aop\MethodInvocation $invocation) {
        if (is_array($result)) {
            $query   = $result[0];
            $context = $result[1];
        } else {
            $query   = $result;
            $context = $this->getContext($invocation->getMethod(), $invocation->getArguments());
        }
        if ($query === null) {
            $query = $this->getQueryFromSqlFile($invocation->getTargetClass(), $invocation->getMethod(), $context);
        }
        if ($query === null) {
            throw new Exception('none sql found.');
        }
        if (!is_string($query)) {
            throw new Exception('invalid sql found. [' . $query . ']');
        }

        list($query, $placeHolders) = $this->setupQuery($query);

        $pdo = $this->getPdo($invocation->getTargetClass(), $invocation->getMethod());
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->getModelClass($invocation->getMethod()));
        $this->setupBindValue($stmt, $placeHolders, $context);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if (count($rows) == 0 and
            $this->guessCudSql($invocation->getMethod()->getName())) {
            return array('last_insert_id' => $pdo->lastInsertId(), 'affected_rows' => $stmt->rowCount());
        }
        return $rows;
    }

    /**
     * PDOStatementにプレースホルダーに対応するcontextの値をbindします。
     *
     * @param PDOStatement $stmt
     * @param array $placeHolders
     * @param array $context
     */
    private function setupBindValue(PDOStatement $stmt, array $placeHolders, array $context) {
        $bindValues = array();
        foreach ($placeHolders as $ph) {
            if (isset($context[$ph])) {
                $bindValues[$ph] = $context[$ph];
                $stmt->bindValue($ph, $context[$ph]);
                continue;
            }
            $matches = array();
            $items = preg_split('/_/', $ph);
            if (count($items) > 1 and isset($context[$items[0]])) {
                $obj = $context[$items[0]];
                array_shift($items);
                $value = $this->resolveValue($obj, $items);
                if ($value !== null) {
                    $bindValues[$ph] = $value;
                    $stmt->bindValue($ph, $value);
                    continue;
                }
            }
            \seasar\log\S2Logger::getInstance(__NAMESPACE__)->info("$ph defined. value not found.", __METHOD__);
        }
        if (\seasar\Config::$DEBUG_VERBOSE) {
            \seasar\log\S2Logger::getInstance(__NAMESPACE__)->debug($this->bindValuesToString($bindValues), __METHOD__);
        }
    }

    /**
     * SQLクエリのタイプが、insert、update、deleteかどうかをメソッド名から推測します。
     *
     * @param string $methodName
     * @return boolean
     */
    private function guessCudSql($methodName) {
        if (preg_match('/^(insert|create)/i', $methodName)) {
            return true;
        }
        if (preg_match('/^(update|save)/i', $methodName)) {
            return true;
        }
        if (preg_match('/^(delete|remove)/i', $methodName)) {
            return true;
        }
        return false;
    }

    /**
     * SQLクエリをログ出力用の文字列に変換します。
     *
     * @param string $query
     * @return string
     */
    private function queryToString($query) {
        $query = preg_replace('/\/\*\s*?\*\//s', '', $query);
        $query = preg_replace('/[\r|\n]+/s', ' ', trim($query));
        $query = preg_replace('/\s+/', ' ', $query);
        return $query;
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
        return 'bindValues(' . implode(', ', $items) . ')';
    }

    /**
     * メソッドについている@S2Pdoアノテーションから戻り値に使用するモデルクラスを取得する。
     * アノテーションが付いていなければ、デフォルトのPdoStandardModelクラス名を返す。
     *
     * @param \ReflectionMethod $method
     * @return string
     */
    private function getModelClass(\ReflectionMethod $method) {
        if (\seasar\util\Annotation::has($method, self::ANNOTATION)) {
            $pdoInfo = \seasar\util\Annotation::get($method, self::ANNOTATION);
            if (isset($pdoInfo['dto'])) {
                return $pdoInfo['dto'];
            }
        }
        return self::$MODEL_CLASS;
    }
}
