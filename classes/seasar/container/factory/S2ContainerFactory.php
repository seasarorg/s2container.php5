<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2008 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
/**
 * S2コンテナファクトリは、 diconファイルなどの設定ファイルから新たにS2コンテナを構築する機能を提供します。
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.factory
 * @author    klove
 */
namespace seasar::container::factory;
class S2ContainerFactory {

    /**
     * @var array ダイコンファイルの拡張子ごとのS2ContainerBuilder群
     */
    private static $builders = array();

    /**
     * @var array 処理中のダイコンファイルのパス群
     */
    private static $processingPaths = array();

    /**
     * S2ContainerFactoryの構築は許可されていません。
     */
    private function __construct() {}

    /**
     * 指定された設定ファイルに基づき、 S2コンテナを構築して返します。
     *
     * @param string dicon path 
     * @return S2Container
     */
    public static function create($diconPath) {
        return self::createInternal($diconPath);
    }

    /**
     * @see seasar::conatiner::factory::S2ContainerFactory::create()
     */
    private static function createInternal($path) {
        self::enter($path);
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $container = null;
        try {
            $container = self::getBuilder($ext)->build($path);
            self::leave($path);
        } catch (Exception $e) {
            self::leave($path);
            throw $e;
        }

        return $container;
    }

    /**
     * 指定された設定ファイルからS2コンテナを構築し、 親S2コンテナに対してインクルードします。
     *
     * @param seasar::container::S2Container $parent 親となるS2コンテナインスタンス
     * @param string $path ダイコンファイルのパス
     */
    public static function includeChild(seasar::container::S2Container $parent, $path) {
        self::enter($path);
        $root = $parent->getRoot();
        $child = null;
        try {
            if ($root->hasDescendant($path)) {
                $child = $root->getDescendant($path);
                $parent->includeChild($child);
            } else {
                $ext = pathinfo($path,PATHINFO_EXTENSION);
                $builder = self::getBuilder($ext);
                $child = $builder->includeChild($parent,$path);
                $root->registerDescendant($child);
            }
            self::leave($path);
        } catch (Exception $e) {
            self::leave($path);
            throw $e;
        }
        return $child;
    }

    /**
     * S2ContainerBuilderを返します。ダイコンファイルの拡張子ごとにS2ContainerBuilderを生成します。
     *
     * @param string $ext ダイコンファイルの拡張子
     */
    private static function getBuilder($ext) {
        $builder = null;

        if (array_key_exists($ext, self::$builders)) {
            return self::$builders[$ext];
        }

        if (array_key_exists($ext, seasar::container::Config::$CONTAINER_BUILDERS)) {
            $builderClassName = seasar::container::Config::$CONTAINER_BUILDERS[$ext];
        } else {
            throw new seasar::container::exception::IllegalContainerBuilderRuntimeException($ext);
        }
        self::$builders[$ext] = new $builderClassName;
        return self::$builders[$ext];
    }

    /**
     * ダイコンファイルからS2Containerを生成する前にダイコンファイルのパスをスタックに保存します。
     * ダイコンファイルのパスが既にスタックに存在する場合は、CircularIncludeRuntimeExceptionをスローします。
     *
     * @param string $path
     * @throw seasar::container::exception::CircularIncludeRuntimeException
     */
    private static function enter($path) {
        if (in_array($path,self::$processingPaths)) {
            throw new seasar::container::exception::CircularIncludeRuntimeException($path, self::$processingPaths);
        }
        array_push(self::$processingPaths,$path);
    }

    /**
     * ダイコンファイルからS2Containerを生成した後にダイコンファイルのパスをスタックから取り除きます。
     *
     * @param string $path
     * @throw seasar::container::exception::CircularIncludeRuntimeException
     */
    private static function leave($path) {
        array_pop(self::$processingPaths);
    }
}
