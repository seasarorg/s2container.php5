<?php
namespace sample::pdo;
/**
 * @S2Pdo('pdo' => 'sqliteC')
 */
class EmpDao {

    /**
     * @S2Pdo('available' => false)
     */
    public function findByPaginate(::Paginate $paginate) {
        list($row) = $this->findAllTotal($paginate);
        $paginate->setTotal($row->total);
        return $this->findAll($paginate);
    }

    public function findAllTotal(::Paginate $paginate) {
        return "select count(*) as total from EMP";
    }

    public function findAll(::Paginate $paginate) {
        return "select * from EMP
                limit /*:paginate_getLimit*/3 offset /*:paginate_getOffset*/5";
    }
}
