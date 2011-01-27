<?php
/**
 * @package rampart
 * @subpackage controllers
 */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/util/datetime.js');
$modx->regClientStartupScript($rampart->config['jsUrl'].'widgets/moderated.users.grid.js');
$modx->regClientStartupScript($rampart->config['jsUrl'].'widgets/ban.matches.grid.js');
$modx->regClientStartupScript($rampart->config['jsUrl'].'widgets/home.panel.js');
$modx->regClientStartupScript($rampart->config['jsUrl'].'sections/home.js');
$output = '<div id="rpt-panel-home-div"></div>';

return $output;
