<?php
/**
 * @package rampart
 * @subpackage controllers
 */
$modx->regClientStartupScript($rampart->config['jsUrl'].'rampart.js');
$modx->regClientStartupHTMLBlock('<script type="text/javascript">
Ext.onReady(function() {
    Rampart.config = '.$modx->toJSON($rampart->config).';
    Rampart.config.connector_url = "'.$rampart->config['connectorUrl'].'";
});
</script>');

return '';