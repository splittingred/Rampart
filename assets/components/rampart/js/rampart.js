var Rampart = function(config) {
    config = config || {};
    Rampart.superclass.constructor.call(this,config);
};
Ext.extend(Rampart,Ext.Component,{
    page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {}
});
Ext.reg('rampart',Rampart);

var Rampart = new Rampart();