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
 * S2ActionHelperでコンポーネントを取得する際に、コンポーネント名で取得する場合は、
 * あらかじめ、コンポーネントをインポートする必要があります。
 * コンポーネントのインポートを、S2ApplicationContextのimportメソッドで行う場合は、
 * クラスの@S2Componentアノテーションでコンポーネント名を設定します。
 */
//use \seasar\container\S2ApplicationContext as s2app;
//s2app::import("$moduleDir/services", 'Service', false, true);

/**
 * コンポーネント登録をs2component関数で実施する場合は、次のようにコンポーネント名を設定します。
 */
s2component('Service_CustomerService')->setName('customer');

