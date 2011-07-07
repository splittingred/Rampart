<?php
/**
 * @package rampart
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/rptwhitelist.class.php');
class rptWhiteList_mysql extends rptWhiteList {}