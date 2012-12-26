var notificationSetting = {
    itemTpl: '<div>{content}</div>',
    selModel: {
        mode: 'SINGLE',
        allowDeselect: true
    },
    grouped: true,
    indexBar: false,

    onItemDisclosure: {
       handler: function(record, btn, index) {
            viewMistakeNotification(record.data.mistake_id);
        }
    },

    store: new Ext.data.Store({
        model: 'notification',
	getGroupString : function(record) {
                    return record.get('status');
                },
        proxy: {
            type: 'ajax',
            reader: {
                type: 'json'
            }
        }
    })
};

var notificationSection = new Ext.List(Ext.apply(notificationSetting));

function loadNotification(){
    //change this field to new during actual launch :D
    notificationSection.store.proxy.url = 'notification/all/'+userID,
    notificationSection.store.load();
    rootPanel.setActiveItem(notificationSection);
}