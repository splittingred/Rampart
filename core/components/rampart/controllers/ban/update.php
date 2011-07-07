<?php
/**
 * @package rampart
 * @subpackage controllers
 */

$id = $modx->getOption('id',$_REQUEST,'');
if (empty($id)) return $modx->lexicon('rampart.ban_err_ns');
$ban = $modx->getObject('rptBan',$id);
if (empty($ban)) return $modx->lexicon('rampart.ban_err_nf');

$banArray = $ban->toArray();

$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/util/datetime.js');
$modx->regClientStartupScript($rampart->config['jsUrl'].'widgets/ban/ban.panel.js');
$modx->regClientStartupScript($rampart->config['jsUrl'].'widgets/ban/ban.matches.grid.js');
$modx->regClientStartupScript($rampart->config['jsUrl'].'sections/ban/update.js');
$modx->regClientStartupHTMLBlock('<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
        xtype: "rampart-page-ban-update"
        ,ban: "'.$banArray['id'].'"
        ,record: '.$modx->toJSON($banArray).'
    });
});
// ]]>
</script>');
$output = '<div id="rampart-panel-ban-div"></div>';

return $output;
