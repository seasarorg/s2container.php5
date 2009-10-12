<?php
class Service_ItemService {

    public function setItemModel(Model_DbTable_Item $model) {
        $this->itemModel = $model;
    }
    
    public function findAllItem() {
        return $this->itemModel->fetchAll();
    }

    public function findOrderingByItemId($id) {
        $itemRow = $this->itemModel->find($id)->current();
        if (is_null($itemRow)) {
            return array();
        }

        $orderingDetailRows = $itemRow->findModel_DbTable_OrderingDetail();
        $result = array();
        foreach($orderingDetailRows as $row) {
            $result[] = $row->findParentModel_DbTable_Ordering()->toArray();
        }
        return $result;
    }
}

