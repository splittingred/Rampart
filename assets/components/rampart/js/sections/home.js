Ext.onReady(function() {
    MODx.load({ xtype: 'rpt-page-home'});
});

Rampart.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'rpt-panel-home'
            ,renderTo: 'rpt-panel-home-div'
        }]
    });
    Rampart.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(Rampart.page.Home,MODx.Component);
Ext.reg('rpt-page-home',Rampart.page.Home);