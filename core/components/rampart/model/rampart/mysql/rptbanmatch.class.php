<?php
/**
 * @package rampart
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/rptbanmatch.class.php');
class rptBanMatch_mysql extends rptBanMatch {}