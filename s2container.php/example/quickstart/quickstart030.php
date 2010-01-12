<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');

s2import(dirname(__FILE__) . '/classes');
s2aspect('new seasar\aop\interceptor\TraceInterceptor')
  ->setPattern('/^Service$/')
  ->setPointcut('/^add$/');

$action = s2get('Action');
$action->indexAction();
