Rampart.panel.Ban = function(config) {
    config = config || {};
    Ext.apply(config,{
        id: 'rampart-panel-ban'
        ,cls: 'container form-with-labels'
        ,url: Rampart.config.connector_url
        ,baseParams: {}
        ,items: [{
            html: '<h2>'+_('rampart.ban')+'</h2>'
            ,border: false
            ,id: 'rampart-ban-header'
            ,cls: 'modx-page-header'
        },MODx.getPageStructure([{
            title: _('rampart.ban_info')
            ,xtype: 'panel'
            ,defaults: { border: false }
            ,items: [{
                html: '<p>'+_('rampart.ban.intro_msg')+'</p>'
                ,id: 'rampart-ban-msg'
                ,bodyCssClass: 'panel-desc'
            },{
                layout: 'form'
                ,labelAlign: 'top'
                ,id: 'rampart-ban-form'
                ,cls: 'main-wrapper'
                ,labelWidth: 150
                ,items: [{
                    layout: 'column'
                    ,border: false
                    ,defaults: {
                        layout: 'form'
                        ,labelAlign: 'top'
                        ,anchor: '100%'
                        ,border: false
                    }
                    ,items: [{
                        columnWidth: .5
                        ,cls: 'main-content'
                        ,items: [{
                            name: 'id'
                            ,xtype: 'hidden'
                        },{
                            fieldLabel: _('rampart.reason')
                            ,description: MODx.expandHelp ? '' : _('rampart.reason_desc')
                            ,name: 'reason'
                            ,id: 'rpt-ban-reason'
                            ,xtype: 'textarea'
                            ,allowBlank: true
                            ,anchor: '100%'
                        },{
                            xtype: MODx.expandHelp ? 'label' : 'hidden'
                            ,forId: 'rpt-ban-reason'
                            ,html: _('rampart.reason_desc')
                            ,cls: 'desc-under'

                        },{
                            fieldLabel: _('rampart.email')
                            ,description: MODx.expandHelp ? '' : _('rampart.email_desc')
                            ,name: 'email'
                            ,id: 'rpt-ban-email'
                            ,xtype: 'textfield'
                            ,allowBlank: true
                            ,anchor: '100%'
                        },{
                            xtype: MODx.expandHelp ? 'label' : 'hidden'
                            ,forId: 'rpt-ban-email'
                            ,html: _('rampart.email_desc')
                            ,cls: 'desc-under'

                        },{
                            fieldLabel: _('rampart.username')
                            ,description: MODx.expandHelp ? '' : _('rampart.username_desc')
                            ,name: 'username'
                            ,id: 'rpt-ban-username'
                            ,xtype: 'textfield'
                            ,allowBlank: true
                            ,anchor: '100%'
                        },{
                            xtype: MODx.expandHelp ? 'label' : 'hidden'
                            ,forId: 'rpt-ban-username'
                            ,html: _('rampart.username_desc')
                            ,cls: 'desc-under'
                        }]
                    },{
                        columnWidth: .5
                        ,cls: 'main-content'
                        ,items: [{
                            fieldLabel: _('rampart.ip_range')
                            ,description: MODx.expandHelp ? '' : _('rampart.ip_range_desc')
                            ,name: 'ip'
                            ,id: 'rpt-ban-ip-range'
                            ,xtype: 'textfield'
                            ,allowBlank: true
                            ,anchor: '100%'
                        },{
                            xtype: MODx.expandHelp ? 'label' : 'hidden'
                            ,forId: 'rpt-ban-ip-range'
                            ,html: _('rampart.ip_range_desc')
                            ,cls: 'desc-under'

                        },{
                            fieldLabel: _('rampart.hostname')
                            ,description: MODx.expandHelp ? '' : _('rampart.hostname_desc')
                            ,name: 'hostname'
                            ,id: 'rpt-ban-hostname'
                            ,xtype: 'textfield'
                            ,allowBlank: true
                            ,anchor: '100%'
                        },{
                            xtype: MODx.expandHelp ? 'label' : 'hidden'
                            ,forId: 'rpt-ban-hostname'
                            ,html: _('rampart.hostname_desc')
                            ,cls: 'desc-under'

                        },{
                            fieldLabel: _('rampart.expireson')
                            ,description: _('rampart.expireson_desc')
                            ,name: 'expireson'
                            ,id: 'rpt-ban-expireson'
                            ,xtype: 'xdatetime'
                            ,allowBlank: true
                            ,width: 250
                        },{
                            xtype: MODx.expandHelp ? 'label' : 'hidden'
                            ,forId: 'rpt-ban-expireson'
                            ,html: _('rampart.expireson_desc')
                            ,cls: 'desc-under'
                        }]
                    }]
                },{
                    fieldLabel: _('rampart.notes')
                    ,description: MODx.expandHelp ? '' : _('rampart.notes_desc')
                    ,name: 'notes'
                    ,id: 'rpt-ban-notes'
                    ,xtype: 'textarea'
                    ,allowBlank: true
                    ,anchor: '90%'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'rpt-ban-notes'
                    ,html: _('rampart.notes_desc')
                    ,cls: 'desc-under'
                }]
            }]
        },{
            title: _('rampart.ban_matches')
            ,xtype: 'panel'
            ,defaults: { border: false }
            ,items: [{
                html: '<p>'+_('rampart.ban_matches.intro_msg')+'</p>'
                ,id: 'rampart-ban-matches-msg'
                ,border: false
                ,bodyCssClass: 'panel-desc'
            },{
                cls: 'main-wrapper'
                ,id: 'rampart-ban-matches-tab'
                ,labelWidth: 150
                ,items: [{
                    xtype: 'rpt-grid-ban-matches'
                    ,preventRender: true
                }]
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