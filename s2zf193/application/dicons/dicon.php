<?php
/**
 * S2Container設定ファイル内では、次の変数が使用可能です。
 *   - @var Zend_Controller_Request_Abstract $request
 *   - @var string $module モジュール名
 *   - @var string $controller コントローラ名
 *   - @var string $action アクション名
 *   - @var string $moduleDir モジュールディレクトリパス
 */

use seasar\container\S2ApplicationContext as s2app;

\seasar\log\S2Logger::getLogger(__CLASS__)->debug('common dicon file.');
s2app::init();

