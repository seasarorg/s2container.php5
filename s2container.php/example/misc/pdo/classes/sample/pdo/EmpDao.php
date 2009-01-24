<?php
namespace sample\pdo;
/**
 * @S2Pdo('pdo' => 'sqliteC');
 */
class EmpDao {

    public function byPaginate(\Paginate $paginate) {
        try {
            $paginate->getTotal();
        } catch (\Exception $e) {
            list($row) = $this->findAllTotal($paginate);
            $paginate->setTotal($row->total);
        }
        return $this->findAll($paginate);
    }

    public function findAllTotal(\Paginate $paginate) {
        return "select count(*) as total from EMP order by EMPNO";
    }

    public function findAll(\Paginate $paginate) {
        return "select * from EMP order by EMPNO
                limit /*:paginate_getLimit*/3 offset /*:paginate_getOffset*/5";
    }
}
