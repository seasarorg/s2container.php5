<?php
/**
 * @S2Aspect('new seasar\aop\interceptor\TraceInterceptor')
 */
class Dao {
    /**
     * S2Aspect('new seasar\aop\interceptor\TraceInterceptor')
     */
    public function findById($id) {
        return 2009;
    }
}
