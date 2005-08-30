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
// $Id: EmptyRuntimeException.class.php,v 1.1 2005/05/28 16:50:13 klove Exp $
/**
 * ‘ÎÛ‚ªÝ’è‚³‚ê‚Ä‚¢‚È‚¢ê‡‚ÌŽÀsŽž—áŠO‚Å‚·B
 * 
 * @package org.seasar.framework.exception
 * @author klove
 */
final class EmptyRuntimeException extends S2RuntimeException {

    private $targetName_;

    /**
     * @param string 
     */
    public function EmptyRuntimeException($targetName) {
        parent::__construct("ESSR0007",array($targetName));
        $this->targetName_ = $targetName;
    }
    
    public function getTargetName() {
        return $this->targetName_;
    }
}
?>