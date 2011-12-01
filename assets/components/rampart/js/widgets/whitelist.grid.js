
Rampart.grid.WhiteLists = function(config) {
    config = config || {};
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config,{
        url: Rampart.config.connector_url
        ,baseParams: { action: 'mgr/whitelist/getList' }
        ,save_action: 'mgr/whitelist/updateFromGrid'
        ,fields: ['id','ip','notes','active','createdon','createdby','editedon','editedby']
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,viewConfig: {
            forceFit:true
            ,enableRowBody:true
            ,scrollOffset: 0
            ,autoFill: true
            ,showPreview: true
            ,getRowClass : function(rec, ri, p){
                return rec.data.active ? 'grid-row-active' : 'grid-row-inactive';
            }
        }
        ,sm: this.sm
        ,columns: [this.sm,{
            header: _('id')
            ,dataIndex: 'id'
            ,sortable: false
            ,hidden: true
        },{
            header: _('rampart.ip')
            ,dataIndex: 'ip'
            ,sortable: true
            ,width: 120
            ,editor: { xtype: 'textfield' }
        },{
            header: _('rampart.notes')
            ,dataIndex: 'notes'
            ,sortable: true
            ,width: 120
            ,editor: { xtype: 'textfield' }
        },{
            header: _('rampart.createdon')
            ,dataIndex: 'createdon'
            ,sortable: true
            ,width: 120
        }]
        ,tbar: [{
            text: _('rampart.bulk_actions')
            ,menu: [{
                text: _('rampart.activate_selected')
                ,handler: this.activateSelected
                ,scope: this
            },{
                text: _('rampart.deactivate_selected')
                ,handler: this.deactivateSelected
                ,scope: this
            },'-',{
                text: _('rampart.remove_selected')
                ,handler: this.removeSelected
                ,scope: this
            }]
        },'-',{
            text: _('rampart.whitelist_add_new')
            ,handler: this.addNewWhiteList
            ,scope: this
        },'->',{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'rpt-whitelist-tf-search'
            ,emptyText: _('search')+'...'
            ,listeners: {
                'change': {fn: this.search, scope: this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: function() {
                            this.fireEvent('change',this.getValue());
                            this.blur();
                            return true;}
                        ,scope: cmp
                    });
                },scope:this}
            }
        },{
            xtype: 'button'
            ,id: 'rpt-whitelist-filter-clear'
            ,text: _('filter_clear')
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this}
            }
        }]
    });
    Rampart.grid.WhiteLists.superclass.constructor.call(this,config)
};
Ext.extend(Rampart.grid.WhiteLists,MODx.grid.Grid,{
    _addEnterKeyHandler: function() {
        this.getEl().addKeyListener(Ext.EventObject.ENTER,function() {
            this.fireEvent('change');
        },this);
    }
    ,clearFilter: function() {
    	var s = this.getStore();
        s.baseParams.search = '';
        Ext.getCmp('rpt-whitelist-tf-search').reset();
    	this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,search: function(tf,newValue,oldValue) {
        var nv = newValue || tf;
        this.getStore().baseParams.search = nv;
        this.getBottomToolbar().changePage(1);
        this.refresh();
        return true;
    }
    ,getSelectedAsList: function() {
        var sels = this.getSelectionModel().getSelections();
        if (sels.length <= 0) return false;

        var cs = '';
        for (var i=0;i<sels.length;i++) {
            cs += ','+sels[i].data[this.config.primaryKey || 'id'];
        }

        if (cs[0] == ',') {
            cs = cs.substr(1);
        }
        return cs;
    }
    ,addNewWhiteList: function(btn,e) {
        if (!this.addWhiteListWindow) {
            this.addWhiteListWindow = MODx.load({
                xtype: 'rpt-window-whitelist-create'
                ,record: {}
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        }
        this.addWhiteListWindow.reset();
        this.addWhiteListWindow.show(e.target);
    }
    ,updateWhiteList: function(btn,e) {
        if (!this.updateWhiteListWindow) {
            this.updateWhiteListWindow = MODx.load({
                xtype: 'rpt-window-whitelist-update'
                ,record: this.menu.record
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        }
        this.updateWhiteListWindow.reset();
        this.updateWhiteListWindow.setValues(this.menu.record);
        this.updateWhiteListWindow.show(e.target);
    }
    ,removeWhiteList: function() {
        MODx.msg.confirm({
            title: _('warning')
            ,text: _('rampart.whitelist_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/whitelist/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }
    ,removeSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            title: _('rampart.whitelist_remove_selected')
            ,text: _('rampart.whitelist_remove_selected_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/whitelist/removeMultiple'
                ,whitelists: cs
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getSelectionModel().clearSelections(true);
                    this.refresh();
                },scope:this}
            }
        });
        return true;
    }

    ,activateWhiteList: function(btn,e) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'mgr/whitelist/activate'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,duplicateWhiteList: function(btn,e) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'mgr/whitelist/duplicate'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,activateSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'mgr/whitelist/activateMultiple'
                ,whitelists: cs
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getSelectionModel().clearSelections(true);
                    this.refresh();
                },scope:this}
            }
        });
        return true;
    }
    ,deactivateWhiteList: function(btn,e) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'mgr/whitelist/deactivate'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }
    ,deactivateSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'mgr/whitelist/deactivateMultiple'
                ,whitelists: cs
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getSelectionModel().clearSelections(true);
                    this.refresh();
                },scope:this}
            }
        });
        return true;
    }

    ,getMenu: function() {

        var m = [];
        if (this.getSelectionModel().getCount() > 1) {
            var rs = this.getSelectionModel().getSelections();

            m.push({
                text: _('rampart.activate_selected')
                ,handler: this.activateSelected
                ,scope: this
            });
            m.push({
                text: _('rampart.deactivate_selected')
                ,handler: this.deactivateSelected
                ,scope: this
            });
            m.push('-');
            m.push({
                text: _('rampart.remove_selected')
                ,handler: this.removeSelected
            });
        } else {
            var r = this.getSelectionModel().getSelected();

            m.push({
                text: _('rampart.whitelist_update')
                ,handler: this.updateWhiteList
            });
            m.push({
                text: _('rampart.whitelist_duplicate')
                ,handler: this.duplicateWhiteList
            });
            m.push('-');
            if (!this.menu.record.active) {
                m.push({
                    text: _('rampart.whitelist_activate')
                    ,handler: this.activateWhiteList
                });
            } else {
                m.push({
                    text: _('rampart.whitelist_deactivate')
                    ,handler: this.deactivateWhiteList
                });
            }
            m.push('-');
            m.push({
                text: _('rampart.whitelist_remove')
                ,handler: this.removeWhiteList
            });
        }
        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
    }
});
Ext.reg('rpt-grid-whitelist',Rampart.grid.WhiteLists);



Rampart.window.CreateWhiteList = function(config) {
    config = config || {};
    this.ident = config.ident || 'rpt-cwl-'+Ext.id();
    Ext.applyIf(config,{
        title: _('rampart.whitelist_add_new')
        ,height: 150
        ,width: 500
        ,url: Rampart.config.connectorUrl
        ,action: 'mgr/whitelist/create'
        ,fields: [{
            fieldLabel: _('rampart.ip')
            ,description: MODx.expandHelp ? '' : _('rampart.whitelist_ip_desc')
            ,name: 'ip'
            ,id: this.ident+'-ip'
            ,xtype: 'textfield'
            ,allowBlank: false
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: this.ident+'-ip'
            ,html: _('rampart.whitelist_ip_desc')
            ,cls: 'desc-under'

        },{
            boxLabel: _('rampart.active')
            ,description: MODx.expandHelp ? '' : _('rampart.whitelist_active_desc')
            ,name: 'active'
            ,id: this.ident+'-active'
            ,inputValue: 1
            ,xtype: 'checkbox'
            ,checked: true
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: this.ident+'-active'
            ,html: _('rampart.whitelist_active_desc')
            ,cls: 'desc-under'

        },{
            fieldLabel: _('rampart.notes')
            ,description: MODx.expandHelp ? '' : _('rampart.whitelist_notes_desc')
            ,name: 'notes'
            ,id: this.ident+'-notes'
            ,xtype: 'textarea'
            ,allowBlank: true
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: this.ident+'-notes'
            ,html: _('rampart.whitelist_notes_desc')
            ,cls: 'desc-under'

        }]
    });
    Rampart.window.CreateWhiteList.superclass.constructor.call(this,config);
};
Ext.extend(Rampart.window.CreateWhiteList,MODx.Window);
Ext.reg('rpt-window-whitelist-create',Rampart.window.CreateWhiteList);


Rampart.window.UpdateWhiteList = function(config) {
    config = config || {};
    this.ident = config.ident || 'rpt-uwl-'+Ext.id();
    Ext.applyIf(config,{
        title: _('rampart.whitelist_update')
        ,height: 150
        ,width: 500
        ,url: Rampart.config.connectorUrl
        ,action: 'mgr/whitelist/update'
        ,fields: [{
            name: 'id'
            ,xtype: 'hidden'
        },{
            fieldLabel: _('rampart.ip')
            ,description:  MODx.expandHelp ? '' : _('rampart.ip_desc')
            ,name: 'ip'
            ,id: this.ident+'-ip'
            ,xtype: 'textfield'
            ,allowBlank: false
            ,anchor: '90%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: this.ident+'-ip'
            ,html: _('rampart.whitelist_ip_desc')
            ,cls: 'desc-under'

        },{
            boxLabel: _('rampart.active')
            ,description: MODx.expandHelp ? '' : _('rampart.active_desc')
            ,name: 'active'
            ,id: this.ident+'-active'
            ,inputValue: 1
            ,xtype: 'checkbox'
            ,checked: true
            ,anchor: '90%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: this.ident+'-active'
            ,html: _('rampart.whitelist_active_desc')
            ,cls: 'desc-under'

        },{
            fieldLabel: _('rampart.notes')
            ,description:  MODx.expandHelp ? '' : _('rampart.notes_desc')
            ,name: 'notes'
            ,id: this.ident+'-notes'
            ,xtype: 'textarea'
            ,allowBlank: true
            ,anchor: '90%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: this.ident+'-notes'
            ,html: _('rampart.whitelist_notes_desc')
            ,cls: 'desc-under'

        }]
    });
    Rampart.window.UpdateWhiteList.superclass.constructor.call(this,config);
};
Ext.extend(Rampart.window.UpdateWhiteList,MODx.Window);
Ext.reg('rpt-window-whitelist-update',Rampart.window.UpdateWhiteList);