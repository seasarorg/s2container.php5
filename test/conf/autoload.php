<?php
function __autoload($class=null){
    if(S2ContainerClassLoader::load($class)){return;}
    require_once($class . '.class.php');
} 
?>
