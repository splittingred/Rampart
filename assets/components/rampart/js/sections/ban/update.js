Rampart.page.UpdateBan = function(config) {
	config = config || {};
	Ext.applyIf(config,{
	    formpanel: 'rampart-panel-ban'
        ,buttons: [{
            process: 'mgr/ban/update'
            ,text: _('save')
            ,method: 'remote'
            ,checkDirty: false
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        },'-',{
            process: 'cancel'
            ,text: _('cancel')
            ,params: {a:MODx.action['welcome']}
        }]
        ,loadStay: false
        ,components: [{
            xtype: 'rampart-panel-ban'
            ,renderTo: 'rampart-panel-ban-div'
            ,ban: config.record.id || MODx.request.id
            ,record: config.record || {}
            ,baseParams: { action: 'update' ,id: config.id }
        }]
	});
	Rampart.page.UpdateBan.superclass.constructor.call(this,config);
};
Ext.extend(Rampart.page.UpdateBan,MODx.Component);
Ext.reg('rampart-page-ban-update',Rampart.page.UpdateBan);