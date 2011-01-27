<?php
/**
 * @package rampart
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/rptbanmatch.class.php');
class rptBanMatch_mysql extends rptBanMatch {
    function rptBanMatch_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>