<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003-2004 The Seasar Project.                          |
// +----------------------------------------------------------------------+
// | The Seasar Software License, Version 1.1                             |
// |   This product includes software developed by the Seasar Project.    |
// |   (http://www.seasar.org/)                                           |
// +----------------------------------------------------------------------+
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * S2Container‚ÅŽg‚¤’è”‚ð’è‹`‚µ‚Ü‚·B
 * 
 * @package org.seasar.framework.container
 * @author klove
 */
interface ContainerConstants {

    const INSTANCE_SINGLETON = "singleton";

    const INSTANCE_PROTOTYPE = "prototype";

    const INSTANCE_REQUEST = "request";

    const INSTANCE_SESSION = "session";

    const INSTANCE_OUTER = "outer";

    const AUTO_BINDING_AUTO = "auto";

    const AUTO_BINDING_CONSTRUCTOR = "constructor";

    const AUTO_BINDING_PROPERTY = "property";

    const AUTO_BINDING_NONE = "none";

    /**
     * preg_match(/"(.+)". ContainerConstants::NS_SEP ."(.+)"/);
     */
    const NS_SEP = '\.';

    const CONTAINER_NAME = "container";

    const REQUEST_NAME = "request";

    const RESPONSE_NAME = "response";

    const SESSION_NAME = "session";

    const SERVLET_CONTEXT_NAME = "servletContext";

    const COMPONENT_DEF_NAME = "componentDef";
}
?>