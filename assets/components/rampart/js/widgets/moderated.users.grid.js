
Rampart.grid.ModeratedUsers = function(config) {
    config = config || {};
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config,{
        url: Rampart.config.connector_url
        ,baseParams: { action: 'mgr/flagged/getList', status: '' }
        ,save_action: 'mgr/flagged/updateFromGrid'
        ,fields: ['id','username','fullname','email','ip','hostname','useragent','flaggedfor','flaggedon']
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,sm: this.sm
        ,columns: [this.sm,{
            header: _('id')
            ,dataIndex: 'id'
            ,sortable: false
            ,hidden: true
        },{
            header: _('rampart.username')
            ,dataIndex: 'username'
            ,sortable: true
            ,width: 70
        },{
            header: _('rampart.email')
            ,dataIndex: 'email'
            ,sortable: true
            ,width: 80
        },{
            header: _('rampart.ip')
            ,dataIndex: 'ip'
            ,sortable: true
            ,width: 60
        },{
            header: _('rampart.hostname')
            ,dataIndex: 'hostname'
            ,sortable: true
            ,width: 100
        },{
            header: _('rampart.useragent')
            ,dataIndex: 'useragent'
            ,sortable: true
            ,width: 120
        },{
            header: _('rampart.flaggedfor')
            ,dataIndex: 'flaggedfor'
            ,sortable: true
            ,width: 80
        },{
            header: _('rampart.flaggedon')
            ,dataIndex: 'flaggedon'
            ,sortable: true
            ,width: 80
        }]
        ,tbar: [{
            text: _('rampart.bulk_actions')
            ,menu: [{
                text: _('rampart.approve_selected')
                ,handler: this.approveSelected
                ,scope: this
            },{
                text: _('rampart.reject_selected')
                ,handler: this.rejectSelected
                ,scope: this
            }]
        },'-',{
            xtype: 'combo'
            ,name: 'status'
            ,hiddenName: 'status'
            ,id: 'rpt-mu-filter-status'
            ,value: ''
            ,editable: false
            ,triggerAction: 'all'
            ,emptyText: _('rampart.filter_by_status')
            ,store: [['',_('rampart.awaiting')],['approved','Approved'],['rejected','Rejected']]
            ,listeners: {
                'change': {fn: this.filterByStatus, scope: this}
                ,'select': {fn: this.filterByStatus, scope: this}
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
        },'->',{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'rpt-mu-tf-search'
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
            ,id: 'rpt-mu-filter-clear'
            ,text: _('filter_clear')
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this}
            }
        }]
    });
    Rampart.grid.ModeratedUsers.superclass.constructor.call(this,config)
};
Ext.extend(Rampart.grid.ModeratedUsers,MODx.grid.Grid,{
    _addEnterKeyHandler: function() {
        this.getEl().addKeyListener(Ext.EventObject.ENTER,function() {
            this.fireEvent('change');
        },this);
    }
    ,clearFilter: function() {
    	var s = this.getStore();
        s.baseParams.search = '';
        Ext.getCmp('rpt-mu-tf-search').reset();
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
    ,filterByStatus: function(cb) {
        this.getStore().baseParams.status = cb.getValue();
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
    ,updateBan: function(btn,e) {
        if (!this.updateBanWindow) {
            this.updateBanWindow = MODx.load({
                xtype: 'rpt-window-ban-update'
                ,record: this.menu.record
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        }
        this.updateBanWindow.reset();
        this.updateBanWindow.setValues(this.menu.record);
        this.updateBanWindow.show(e.target);
    }
    ,approveSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'mgr/flagged/approve'
                ,users: cs
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
    ,rejectSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'mgr/flagged/reject'
                ,users: cs
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
                text: _('rampart.approve_selected')
                ,handler: this.approveSelected
                ,scope: this
            });
            m.push({
                text: _('rampart.reject_selected')
                ,handler: this.rejectSelected
                ,scope: this
            });
        } else {
            var r = this.getSelectionModel().getSelected();

            m.push({
                text: _('rampart.flag_approve')
                ,handler: this.approveSelected
            });
            m.push({
                text: _('rampart.flag_reject')
                ,handler: this.rejectSelected
            });
        }
        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
    }
});
Ext.reg('rpt-grid-moderated-users',Rampart.grid.ModeratedUsers);
