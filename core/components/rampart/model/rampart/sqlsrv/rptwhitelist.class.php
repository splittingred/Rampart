<?php
/**
 * @package rampart
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/rptwhitelist.class.php');
class rptWhiteList_sqlsrv extends rptWhiteList {
    function rptWhiteList_sqlsrv(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>