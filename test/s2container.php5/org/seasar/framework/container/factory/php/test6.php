<?php
    $s2dicon->includeChild(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/php/test4.php');
    $cd = $s2dicon->addComponent("A_PhpS2ContainerBuilderTests","a");
    $s2dicon->addArg($cd,'b');
?>
