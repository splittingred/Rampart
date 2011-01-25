<?php
/**
 * @package rampart
 */
require_once dirname(dirname(__FILE__)).'/model/rampart/rampart.class.php';
$rampart= new Rampart($modx);
return $rampart->initialize('mgr');