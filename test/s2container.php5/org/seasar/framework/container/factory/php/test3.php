<?php
    $cd = $s2dicon->addComponent("A_PhpS2ContainerBuilderTests","a");
    $s2dicon->addArg($cd,'1+1');

    $s2dicon->addInitMethod($cd,"initTest");
    $s2dicon->addDestroyMethod($cd,"destroyTest");
?>
