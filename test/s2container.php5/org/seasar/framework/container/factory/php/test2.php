<?php
    $cd = $s2dicon->addComponent("A_PhpS2ContainerBuilderTests","a");
    $s2dicon->addArg($cd,'b');

    $cd = $s2dicon->addComponent("B_PhpS2ContainerBuilderTests","b");
    $s2dicon->addProperty($cd,"value","1+1");
?>
