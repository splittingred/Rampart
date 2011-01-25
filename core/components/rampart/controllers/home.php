<?php
/**
 * @package rampart
 * @subpackage controllers
 */
$modx->regClientStartupScript($rampart->config['jsUrl'].'widgets/home.panel.js');
$modx->regClientStartupScript($rampart->config['jsUrl'].'sections/home.js');
$output = '<div id="rpt-panel-home-div">HOME</div>';

return $output;
