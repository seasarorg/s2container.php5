<?php
namespace sample::pdo;
/**
 * @S2Pdo('pdo' => 'SqliteAPdo');
 */
class CdDao {

    public $sqliteBPdo = 's2binding';

    public function findAll() {
        return "select * from CD";
    }

    public function findById($id) {
        return "select * from CD where id = /*:id*/5";
    }

    public function findByTitle() {
        return array("select * from CD where title = /*:title*/'AAA'", array('title' => 'S2Pdo!!!'));
    }

    /**
     * @S2Pdo('pdo' => 'sqliteBPdo');
     */
    public function findAllFromB() {
        return "select * from CD";
    }

    public function findBySqlFile1($condition){}
    public function findBySqlFile2($condition){}
    public function findBySqlFile3($condition){}
    public function findBySqlFile4($condition){}

    public function transactionalInsert() {
        try {
            $this->sqliteBPdo->beginTransaction();
            $this->insert(10, 'aaa', 'bbb');
            $this->sqliteBPdo->commit();
        } catch (Exception $e) {
            seasar::log::S2Logger::getInstance(__NAMESPACE__)->warn($e->getMessage(), __METHOD__);
            $this->sqliteBPdo->rollBack();
        }
    }

    public function sampleTransaction() {
        try {
            seasar::log::S2Logger::getInstance(__NAMESPACE__)->info('start transaction.', __METHOD__);
            $this->sqliteBPdo->beginTransaction();
            $this->insert(10, 'aaa', 'bbb');
            $this->updateTitle(10, 'AAA');
            $this->delete(10);
            seasar::log::S2Logger::getInstance(__NAMESPACE__)->info('commit transaction.', __METHOD__);
            $this->sqliteBPdo->commit();
        } catch (Exception $e) {
            seasar::log::S2Logger::getInstance(__NAMESPACE__)->info($e->getMessage(), __METHOD__);
            seasar::log::S2Logger::getInstance(__NAMESPACE__)->info('rollback transaction.', __METHOD__);
            $this->sqliteBPdo->rollBack();
        }
    }

    public function errorTransaction() {
        try {
            seasar::log::S2Logger::getInstance(__NAMESPACE__)->info('start transaction.', __METHOD__);
            $this->sqliteBPdo->beginTransaction();
            $this->insert(10, 'aaa', 'bbb');
            $this->insert(10, 'AAA', 'BBB');
            seasar::log::S2Logger::getInstance(__NAMESPACE__)->info('commit transaction.', __METHOD__);
            $this->sqliteBPdo->commit();
        } catch (Exception $e) {
            seasar::log::S2Logger::getInstance(__NAMESPACE__)->info($e->getMessage(), __METHOD__);
            seasar::log::S2Logger::getInstance(__NAMESPACE__)->info('rollback transaction.', __METHOD__);
            $this->sqliteBPdo->rollBack();
        }
    }

    /**
     * @S2Pdo('pdo' => 'SqliteBPdo');
     */
    public function insert($id, $title, $content) {
        return "insert into CD values(/*:id*/5, /*:title*/'a,a a', /*:content*/'b,b b' )";
    }

    /**
     * @S2Pdo('pdo' => 'SqliteBPdo');
     */
    public function updateTitle($id, $title) {
        return "update CD set title = /*:title*/'xxx' where id = /*:id*/5";
    }

    /**
     * @S2Pdo('pdo' => 'SqliteBPdo');
     */
    public function delete($id) {
        return "delete from CD where id = /*:id*/5";
    }
}
