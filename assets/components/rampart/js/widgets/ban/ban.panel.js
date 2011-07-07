Rampart.panel.Ban = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,id: 'rampart-panel-ban'
        ,url: Rampart.config.connector_url
        ,baseParams: {}
        ,items: [{
            html: '<h2>'+_('rampart.ban')+'</h2>'
            ,border: false
            ,id: 'rampart-ban-header'
            ,cls: 'modx-page-header'
        },MODx.getPageStructure([{
            title: _('rampart.ban_info')
            ,bodyStyle: 'padding: 15px;'
            ,defaults: { border: false ,msgTarget: 'side' }
            ,layout: 'form'
            ,id: 'rampart-ban-form'
            ,labelWidth: 150
            ,items: [{
                html: '<p>'+_('rampart.ban.intro_msg')+'</p>'
                ,id: 'rampart-ban-msg'
                ,border: false
            },{
                name: 'id'
                ,xtype: 'hidden'
            },{
                fieldLabel: _('rampart.reason')
                ,description: _('rampart.reason_desc')
                ,name: 'reason'
                ,xtype: 'textarea'
                ,allowBlank: true
                ,anchor: '90%'
            },{
                fieldLabel: _('rampart.ip_range')
                ,description: _('rampart.ip_range_desc')
                ,name: 'ip'
                ,xtype: 'textfield'
                ,allowBlank: true
                ,anchor: '90%'
            },{
                fieldLabel: _('rampart.hostname')
                ,description: _('rampart.hostname_desc')
                ,name: 'hostname'
                ,xtype: 'textfield'
                ,allowBlank: true
                ,anchor: '90%'
            },{
                fieldLabel: _('rampart.email')
                ,description: _('rampart.email_desc')
                ,name: 'email'
                ,xtype: 'textfield'
                ,allowBlank: true
                ,anchor: '90%'
            },{
                fieldLabel: _('rampart.username')
                ,description: _('rampart.username_desc')
                ,name: 'username'
                ,xtype: 'textfield'
                ,allowBlank: true
                ,anchor: '90%'
            },{
                fieldLabel: _('rampart.expireson')
                ,description: _('rampart.expireson_desc')
                ,name: 'expireson'
                ,xtype: 'xdatetime'
                ,allowBlank: true
                ,anchor: '90%'
            },{
                fieldLabel: _('rampart.notes')
                ,description: _('rampart.notes_desc')
                ,name: 'notes'
                ,xtype: 'textarea'
                ,allowBlank: true
                ,anchor: '90%'

            }]
        },{

            title: _('rampart.ban_matches')
            ,bodyStyle: 'padding: 15px;'
            ,defaults: { border: false ,msgTarget: 'side' }
            ,id: 'rampart-ban-matches-tab'
            ,labelWidth: 150
            ,items: [{
                html: '<p>'+_('rampart.ban_matches.intro_msg')+'</p>'
                ,id: 'rampart-ban-matches-msg'
                ,border: false
            },{
                xtype: 'rpt-grid-ban-matches'
                ,preventRender: true
            }]
        }])]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    Rampart.panel.Ban.superclass.constructor.call(this,config);
};
Ext.extend(Rampart.panel.Ban,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        if (!this.initialized) { this.getForm().setValues(this.config.record); }
        if (!Ext.isEmpty(this.config.record.ip)) {
            Ext.getCmp('rampart-ban-header').getEl().update('<h2>'+_('rampart.ban')+': '+this.config.record.ip+'</h2>');
        }
        
        this.fireEvent('ready',this.config.record);
        this.initialized = true;
        MODx.fireEvent('ready');
        return true;
    }
    ,beforeSubmit: function(o) {
        Ext.apply(o.form.baseParams,{
            //propdata: Ext.getCmp('modx-grid-element-properties').encode()
        });
        return this.fireEvent('save',{
            values: this.getForm().getValues()
            ,stay: MODx.config.stay
        });
    }
    ,success: function(r) {

    }
});
Ext.reg('rampart-panel-ban',Rampart.panel.Ban);

/*
[{
        }]*/