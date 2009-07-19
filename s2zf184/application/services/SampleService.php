<?php
class Service_SampleService {

    public function setCdModel(Model_DbTable_Cd $cdModel) {
        $this->cdModel = $cdModel;
    }
    
    public function findAllCd() {
        return $this->cdModel->fetchAll();
    }
}

