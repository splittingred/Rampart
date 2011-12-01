<?php
/**
 * @package rampart
 * @subpackage controllers
 */

class RampartBanUpdateManagerController extends RampartManagerController {
    /** @var rptBan $ban */
    public $ban;

    public function process(array $scriptProperties = array()) {
        $id = $this->modx->getOption('id',$_REQUEST,'');
        if (empty($id)) return $this->failure($this->modx->lexicon('rampart.ban_err_ns'));
        $this->ban = $this->modx->getObject('rptBan',$id);
        if (empty($this->ban)) {
            return $this->failure($this->modx->lexicon('rampart.ban_err_nf',array('id' => $id)));
        }
        return array();
    }
    public function getPageTitle() { return $this->modx->lexicon('rampart'); }
    public function loadCustomCssJs() {
        $banArray = $this->ban->toArray();
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/util/datetime.js');
        $this->addJavascript($this->rampart->config['jsUrl'].'widgets/ban/ban.panel.js');
        $this->addJavascript($this->rampart->config['jsUrl'].'widgets/ban/ban.matches.grid.js');
        $this->addJavascript($this->rampart->config['jsUrl'].'sections/ban/update.js');
        $this->addHtml('<script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "rampart-page-ban-update"
                ,ban: "'.$banArray['id'].'"
                ,record: '.$this->modx->toJSON($banArray).'
            });
        });
        // ]]>
        </script>');
    }
    public function getTemplateFile() { return $this->rampart->config['templatesPath'].'ban.tpl'; }
}