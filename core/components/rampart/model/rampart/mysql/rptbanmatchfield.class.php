<?php
/**
 * @package rampart
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/rptbanmatchfield.class.php');
class rptBanMatchField_mysql extends rptBanMatchField {}