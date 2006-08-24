<?php

/**
 * @author nowel
 */
class S2Container_DefaultPropertyAnnotationHandler
    implements S2Container_PropertyAnnotationHandler {

    public function getPropertyType(Reflector $reflector, $defaultValue){
        return $defaultValue;
    }
}

?>
