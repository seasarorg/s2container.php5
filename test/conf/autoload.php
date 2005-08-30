<?php
function __autoload($class=null){
    if(S2ClassLoader::load($class)){return;}
    require_once($class . '.class.php');
} 
?>
