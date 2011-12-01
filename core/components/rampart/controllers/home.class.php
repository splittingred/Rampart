<?php
/**
 * @package rampart
 * @subpackage controllers
 */
class RampartHomeManagerController extends RampartManagerController {
    public function process(array $scriptProperties = array()) {
        
    }
    public function getPageTitle() { return $this->modx->lexicon('rampart'); }
    public function loadCustomCssJs() {
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/util/datetime.js');
        $this->addJavascript($this->rampart->config['jsUrl'].'widgets/moderated.users.grid.js');
        $this->addJavascript($this->rampart->config['jsUrl'].'widgets/ban.matches.grid.js');
        $this->addJavascript($this->rampart->config['jsUrl'].'widgets/whitelist.grid.js');
        $this->addJavascript($this->rampart->config['jsUrl'].'widgets/home.panel.js');
        $this->addLastJavascript($this->rampart->config['jsUrl'].'sections/home.js');
    }
    public function getTemplateFile() { return $this->rampart->config['templatesPath'].'home.tpl'; }
}