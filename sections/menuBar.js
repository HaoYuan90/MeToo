var icon = new Ext.Panel({
	html: "<img src=\"images/icon.jpg\",length = 150px, width = 150px>"
});
var myButton = new Ext.Button ({
    ui  : 'round',
    cls :'myButton',
    text: 'Mine',
    style:'width:145px;',
    handler : function() {
        appMask.show();
        if(connectionStatus)
            loadSelfSection();
        else
            offlineLoad();
    }
});
var friendButton = new Ext.Button ({
    ui  : 'round',
    cls :'friendButton',
    text: 'Friend',
        style:'width:145px;',

    handler : function() {
        appMask.show();
        loadFriendSection();
    }
});
var searchButton = new Ext.Button ({
    ui  : 'round',
    cls :'searchButton',
    text: 'Search',
        style:'width:145px;',

    handler : function() {
        performSearch();
    }

});
var newButton = new Ext.Button ({
    ui  : 'round',
    cls :'newButton',
    text: 'New',
        style:'width:145px;',

    handler : function() {
        createNewPost();
    }
});
var logoutButton = new Ext.Button ({
    ui  : 'round',
    cls :'logoutButton',
    text: 'Logout',
        style:'width:145px;',

    handler : function() {
        fb_logout();
    }
});
var notificationButton = new Ext.Button ({
    ui  : 'round',
    cls : 'noteButton',
    text: 'Notification',
        style:'width:145px;',

    handler : function() {
        loadNotification();
    }
});

var button_bar = new Ext.Panel({
    width : 200,
    dock : 'left',
    cls : 'leftPanel',
    layout : {
        type : 'vbox',
        align : 'center'
    },
    defaults:
    {
    bodyStyle:'padding-bottom:15px;',
    },
    items : [
	icon,
        myButton, 
        friendButton,
        searchButton,
        newButton,
        notificationButton,
                logoutButton

    ]
});        
