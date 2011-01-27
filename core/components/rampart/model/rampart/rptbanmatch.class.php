<?php
/**
 * @package rampart
 */
class rptBanMatch extends xPDOSimpleObject {
    function rptBanMatch(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>