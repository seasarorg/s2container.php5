<?php
/**
 * @S2Container_ComponentAnnotation(name = 'testB',
 *                                  instance = 'prototype',
 *                                  autoBinding = 'none')
 *      
 */
class B_FileSystemComponentAutoRegisterTests {
    const COMPONENT = "name = testB,
                       instance = prototype,
                       autoBinding = none";
    function __construct() {
    }
}
?>