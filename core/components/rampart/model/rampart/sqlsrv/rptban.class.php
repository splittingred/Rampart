<?php
/**
 * @package rampart
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/rptban.class.php');
class rptBan_sqlsrv extends rptBan {
    function rptBan_sqlsrv(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>