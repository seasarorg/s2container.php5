<?php
    $container = new S2ContainerImpl();
    $container->register(new S2Container_ComponentDefImpl("C_PhpS2ContainerBuilderTests",'c'));
    $container->register(new S2Container_ComponentDefImpl("D_PhpS2ContainerBuilderTests"));
?>
