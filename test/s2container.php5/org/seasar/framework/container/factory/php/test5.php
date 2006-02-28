<?php
    $cd = $s2dicon->addComponent("B_PhpS2ContainerBuilderTests","b");

    $s2dicon->addProperty($cd,'value','1+1');
    $s2dicon->addAspect($cd,'new S2Container_TraceInterceptor()','test');

?>
