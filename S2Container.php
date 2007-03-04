<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @author klove
 *
 * S2Container System Definition
 *   S2Container define these definitions.
 *   - S2CONTAINER_PHP5 : S2Container.PHP5 ROOT Directory
 *       [ string default /path/to/S2Container ]
 * 
 * User Definition
 *   User could define these definitions.
 *   - S2CONTAINER_PHP5_DOM_VALIDATE : DICON XML format DTD validation
 *       [ boolean default true ]
 *   - S2CONTAINER_PHP5_APP_DICON : S2Container_SingletonS2ContainerFactory uses if defined.
 *       [ string default not defined ] 
 *   - S2CONTAINER_PHP5_LOG_LEVEL : S2Container.PHP5 Log Level
 *       [ integer default S2Container_SimpleLogger::WARN ]
 *   - S2CONTAINER_PHP5_DEBUG_EVAL : Logging eval script as debug log.
 *       [ boolean default false ]
 *   - S2CONTAINER_PHP5_SIMPLE_LOG_FILE : Logging simple log to a file defined.
 *       [ string default not defined ]
 *   - S2CONTAINER_PHP5_ANNOTATION_HANDLER : constant or comment annotation available
 *       [ string default S2Container_ConstantAnnotationHandler
 *         available handler S2Container_CommentAnnotationHandler ]
 *   - S2CONTAINER_PHP5_ENV : append suffix to dicon file.
 *       [ string default not defined ]
 */

require_once('build/s2container.php5/S2Container.php');
?>
