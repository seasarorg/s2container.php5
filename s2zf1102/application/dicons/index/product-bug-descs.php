<?php
/**
 * S2Container設定ファイル内では、次の変数が使用可能です。
 *   - @var Zend_Controller_Request_Abstract $request
 *   - @var string $module モジュール名
 *   - @var string $controller コントローラ名
 *   - @var string $action アクション名
 *   - @var string $moduleDir モジュールディレクトリパス
 */


/**
 * コンポーネント登録をs2component関数で実施する場合は、次のようにコンポーネント名を設定します。
 */
s2component('Service_Sample')->setName('sample');

