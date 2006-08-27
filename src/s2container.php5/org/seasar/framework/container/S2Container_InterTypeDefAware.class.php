<?php

/**
 * @author nowel
 */
interface S2Container_InterTypeDefAware {
    public function addInterTypeDef(S2Container_InterTypeDef $interTypeDef);
    public function getInterTypeDefSize();
    public function getInterTypeDef($index);
}

?>