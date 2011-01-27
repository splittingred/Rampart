<?php
/**
 * @package rampart
 */
class rptBanMatchBan extends xPDOObject {
    function rptBanMatchBan(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>