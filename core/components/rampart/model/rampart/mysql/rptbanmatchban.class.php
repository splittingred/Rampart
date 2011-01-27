<?php
/**
 * @package rampart
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/rptbanmatchban.class.php');
class rptBanMatchBan_mysql extends rptBanMatchBan {
    function rptBanMatchBan_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>