<?php
/**
 * @package rampart
 */
class rptWhiteList extends xPDOSimpleObject {
    function rptWhiteList(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>