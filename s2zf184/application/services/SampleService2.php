<?php
/**
 * @S2Component('name' => 'service2')
 */
class Service_SampleService2 {

    public function setCdModel(Model_DbTable_Cd $cdModel) {
        $this->cdModel = $cdModel;
    }
    
    public function findAllCd() {
        return $this->cdModel->fetchAll();
    }
}

