<?php
/**
 * @package rampart
 */
class rptFlaggedUser extends xPDOSimpleObject {
    function rptFlaggedUser(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>