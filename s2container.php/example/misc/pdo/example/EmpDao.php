<?php
class EmpDao2 {

    public function byPaginate(\Paginate $paginate) {
        if (!$paginate->hasTotal()) {
            list($row) = $this->findAllTotal($paginate);
            $paginate->setTotal($row->total);
        }
        return $this->findAll($paginate);
    }

    public function findAllTotal(\Paginate $paginate) {
        return 'select count(*) as total from EMP order by EMPNO';
    }

    public function findAll(\Paginate $paginate) {
        $sql = 'select * from EMP order by EMPNO limit :limit offset :offset';
        $context = array('limit' => $paginate->getLimit(), 'offset' => $paginate->getOffset());
        return array($sql, $context);
    }
}
