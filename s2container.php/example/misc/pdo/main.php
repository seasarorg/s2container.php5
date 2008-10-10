<?php
define('ROOT_DIR', dirname(__FILE__));
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

use seasar::container::S2ApplicationContext as s2app;
s2app::import(ROOT_DIR . DIRECTORY_SEPARATOR . 'classes');
s2app::import(ROOT_DIR . DIRECTORY_SEPARATOR . 'config');
s2app::registerAspect('/Dao$/', 'pdo.interceptor', '/^(find|insert|update|delete)/');

/**
 * SqliteDB : sqlite_a.db
 */
$dao = s2app::get('sample::pdo::CdDao');
$rows = $dao->findAll();
$rows = $dao->findById(1);
$rows = $dao->findByTitle();

/**
 * SQLファイル、condition_id(プロパティ)の使用
 * SqliteDB : sqlite_a.db
 */
$condition = new stdclass;
$condition->id = 1;
$rows = $dao->findBySqlFile1($condition);

/**
 * SQLファイル、condition_id(メソッド)の使用
 * SqliteDB : sqlite_a.db
 */
class Hoge {
    public function id() {
        return 1;
    }
}
$rows = $dao->findBySqlFile2(new Hoge);

/**
 * SQLファイル、condition_id(配列)の使用
 * SqliteDB : sqlite_a.db
 */
$condition = array();
$condition['id'] = 1;
$rows = $dao->findBySqlFile3($condition);

/**
 * SQLファイル、condition_id_0(プロパティ、配列)の使用
 * SqliteDB : sqlite_a.db
 */
$condition = new stdclass;
$condition->id = array(1);
$rows = $dao->findBySqlFile4($condition);

/**
 * トランザクション関連
 * SqliteDB : sqlite_b.db
 */
$dao->sampleTransaction();
$dao->errorTransaction();

/**
 * EMPテーブルに対してページング
 * SqliteDB : sqlite_c.db
 */
$dao = s2app::get('sample::pdo::EmpDao');
$paginate = new Paginate;
$paginate->setLimit(3);
$rows = $paginate->find($dao, 'findAll');

$paginate->next();
$rows = $dao->byPaginate($paginate);

