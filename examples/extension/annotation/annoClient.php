<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

require_once('annotation.class.php');
require_once('Hello.class.php');

$annos = S2Container_Annotations::getAnnotations('Hello','greeting');
print_r($annos);

$anno = S2Container_Annotations::getAnnotation('Foo','Hello');
print_r($anno);

$anno = S2Container_Annotations::getAnnotation('Bar','Hello');
print_r($anno);

if(S2Container_Annotations::isAnnotationPresent('Seasar','Hello')){
    print "Seasar annotation exists at hello class.\n";
}

if(!S2Container_Annotations::isAnnotationPresent('Seasar','Hello','greeting')){
    print "Seasar annotation not exists at greeting method.\n";
}
?>
