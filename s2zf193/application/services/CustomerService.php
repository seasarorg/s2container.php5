<?php
class Service_CustomerService {

    public function setCustomerModel(Model_DbTable_Customer $model) {
        $this->customerModel = $model;
    }
    
    public function findAllCustomer() {
        return $this->customerModel->fetchAll();
    }
}

