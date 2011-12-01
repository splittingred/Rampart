<?php
/**
 * @package rampart
 */
require_once dirname(dirname(__FILE__)) . '/model/rampart/rampart.class.php';
class ControllersIndexManagerController extends modExtraManagerController {
    public static function getDefaultController() { return 'home'; }
}

abstract class RampartManagerController extends modManagerController {
    /** @var Rampart $rampart */
    public $rampart;
    public function initialize() {
        $this->rampart = new Rampart($this->modx);

        $this->addJavascript($this->rampart->config['jsUrl'].'rampart.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            Rampart.config = '.$this->modx->toJSON($this->rampart->config).';
            Rampart.config.connector_url = "'.$this->rampart->config['connectorUrl'].'";
        });
        </script>');
        return parent::initialize();
    }
    public function getLanguageTopics() {
        return array('rampart:default');
    }
    public function checkPermissions() { return true;}
}