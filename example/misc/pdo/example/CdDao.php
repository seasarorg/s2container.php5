<?php
class CdDao {

    public $pdo = 's2binding pdo.StandardPdo';

    public function sampleTransaction() {
        try {
            \seasar\log\S2Logger::getInstance(__NAMESPACE__)->info('start transaction.', __METHOD__);
            $this->pdo->beginTransaction();
            $this->insert(10, 'aaa', 'bbb');
            $this->updateTitle(10, 'AAA');
            $this->delete(10);
            \seasar\log\S2Logger::getInstance(__NAMESPACE__)->info('commit transaction.', __METHOD__);
            $this->pdo->commit();
        } catch (Exception $e) {
            \seasar\log\S2Logger::getInstance(__NAMESPACE__)->info($e->getMessage(), __METHOD__);
            \seasar\log\S2Logger::getInstance(__NAMESPACE__)->info('rollback transaction.', __METHOD__);
            $this->pdo->rollBack();
        }
    }

    public function errorTransaction() {
        try {
            \seasar\log\S2Logger::getInstance(__NAMESPACE__)->info('start transaction.', __METHOD__);
            $this->pdo->beginTransaction();
            $this->insert(10, 'aaa', 'bbb');
            $this->insert(10, 'AAA', 'BBB');
            \seasar\log\S2Logger::getInstance(__NAMESPACE__)->info('commit transaction.', __METHOD__);
            $this->pdo->commit();
        } catch (Exception $e) {
            \seasar\log\S2Logger::getInstance(__NAMESPACE__)->info($e->getMessage(), __METHOD__);
            \seasar\log\S2Logger::getInstance(__NAMESPACE__)->info('rollback transaction.', __METHOD__);
            $this->pdo->rollBack();
        }
    }

    public function insert($id, $title, $content) {
        return "insert into CD values(:id, :title, :content)";
    }

    public function updateTitle($id, $title) {
        return "update CD set title = :title where id = :id";
    }

    public function delete($id) {
        return "delete from CD where id = :id";
    }
}
