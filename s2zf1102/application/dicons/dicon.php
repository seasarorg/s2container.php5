<?php
/**
 * S2Container設定ファイル内では、次の変数が使用可能です。
 *   - @var Zend_Controller_Request_Abstract $request
 *   - @var string $module モジュール名
 *   - @var string $controller コントローラ名
 *   - @var string $action アクション名
 *   - @var string $moduleDir モジュールディレクトリパス
 */

\seasar\log\S2Logger::getLogger(__CLASS__)->debug('common dicon file.');
s2init();
s2component('Zend_Db_Adapter_Abstract')->setConstructClosure(function() { return Zend_Db_Table::getDefaultAdapter(); });


