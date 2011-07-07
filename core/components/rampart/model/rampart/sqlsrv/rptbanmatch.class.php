<?php
/**
 * @package rampart
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/rptbanmatch.class.php');
class rptBanMatch_sqlsrv extends rptBanMatch {}