<?php
/**
 * メソッド実行に際し、引数と戻り値の型チェックを行うインターセプター。
 */
class StrictInterceptor implements seasar::aop::MethodInterceptor {

    /**
     * @var boolean
     */
    public static $MUST = false;

    /**
     * @see seasar::aop::MethodInterceptor::invoke()
     */
    public function invoke(seasar::aop::MethodInvocation $invocation) {
        $this->strictArguments($invocation->getMethod(), $invocation->getArguments());
        $result = $invocation->proceed();
        $this->strictReturnValue($invocation->getMethod(), $result);
        return $result;
    }

    /**
     * メソッド引数値についてタイプ検証を行います。
     *
     * @param ReflectionMethod $method
     * @param array $arguments
     * @throw StrictException タイプ検証に失敗した場合にスローされます。
     */
    private function strictArguments(ReflectionMethod $method, array $arguments) {
        $annoInfo = StrictAnnotationFactory::create($method);
        if (isset($annoInfo['param'])) {
            $o = count($annoInfo['param']);
            if ($o != count($arguments)) {
                throw new StrictException('param and arguments count not match');
            }

            for($i=0; $i<$o; $i++) {
                if (false == $this->validate($annoInfo['param'][$i], $arguments[$i])) {
                    throw new StrictException('argument[' . $i . '] type unmatch. expected ' . $annoInfo['param'][$i]);
                }
            }
        } else if (self::$MUST) {
            throw new StrictException('argument type annotation not found.');
        }
    }

    /**
     * メソッド実行結果についてタイプ検証を行います。
     *
     * @param ReflectionMethod $method
     * @param mixed $result
     * @throw StrictException タイプ検証に失敗した場合にスローされます。
     *
     */
    private function strictReturnValue(ReflectionMethod $method, $result) {
        $annoInfo = StrictAnnotationFactory::create($method);
        if (isset($annoInfo['return'])) {
            if (false == $this->validate($annoInfo['return'], $result)) {
                throw new StrictException('return type unmatch. expected ' . $annoInfo['return']);
            }
        } else if (self::$MUST) {
            throw new StrictException('return type annotation not found.');
        }
    }

    /**
     * タイプと値が一致するかを検証します。タイプは「|」区切りで複数指定できます。
     *
     * @param string $type
     * @param mixed $value
     * @return boolean
     */
    private function validate($type, $value) {
        $types = preg_split('/\|/', $type);
        $valid = false;
        foreach ($types as $type) {
            $func = $this->getFunction($type);
            if ($func != null) {
                $valid = $valid || $func($value);
            } else if(class_exists($type, false) or interface_exists($type, false)) {
                if (is_object($value)) {
                    $valid = $valid || get_class($value) == $type;
                    $valid = $valid || is_subclass_of($value, $type);
                }
            } else {
                throw new StrictException('unknown type. ' . $type);
            }
        }
        return $valid;
    }

    /**
     * @param、@returnで指定されたタイプのis_関数が存在するか調べます。
     * 関数が存在した場合は関数名を返します。存在しない場合はnullを返します。
     *
     * @param string $type
     * @return string|null
     */
    private function getFunction($type) {
        $func = 'is_' . $type;
        if (function_exists($func)) {
            return $func;
        }
        return null;
    }
}

/**
 * @param、@returnでmixedが指定された場合は、validateを真とする。
 *
 * @param mixed $value
 * @return boolean
 */
function is_mixed($value) {
    return true;
}

/**
 * @param、@returnでignoreが指定された場合は、validateを真とする。
 *
 * @param mixed $value
 * @return boolean
 */
function is_ignore($value) {
    return true;
}
