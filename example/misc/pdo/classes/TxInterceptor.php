<?php
/**
 * @S2Component('name'      => 'tx',
 *              'namespace' => 'pdo');
 */
class TxInterceptor implements \seasar\aop\MethodInterceptor {
    /**
     * @var PDO
     */
    private $pdo = null;

    /**
     * @param PDO $pdo
     */
    public function setPdo(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * @see \seasar\aop\MethodInterceptor::invoke()
     */
    public function invoke(\seasar\aop\MethodInvocation $invocation) {
        $result = null;
        try {
            $this->pdo->beginTransaction();
            $result = $invocation->proceed();
            $this->pdo->commit();
        } catch (Exception $e) {
            \seasar\log\S2Logger::getInstance(__NAMESPACE__)->info("begin rollback...", __METHOD__);
            $this->pdo->rollback();
            \seasar\log\S2Logger::getInstance(__NAMESPACE__)->info("rollback has occured.", __METHOD__);
            throw $e;
        }
        return $result;
    }

}
