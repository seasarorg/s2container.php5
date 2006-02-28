<?php
    $cd = $s2dicon->addComponent("A_PhpS2ContainerBuilderTests","a");
    $s2dicon->addComponent("B_PhpS2ContainerBuilderTests","b");

    $s2dicon->addInitMethod($cd,"initTest,initTest2");
    $s2dicon->addInitMethodArg($cd,
                              "initTest2",
                              "1+1");
    $s2dicon->addDestroyMethod($cd,"destroyTest,destroyTest2");
    $s2dicon->addDestroyMethodArg($cd,
                                 "destroyTest2",
                                 "b");
?>
