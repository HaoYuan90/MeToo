 var appMask;
 
 Ext.setup({
    onReady: function() {

        appMask = new Ext.LoadMask(Ext.getBody(), {
                msg: "Loading..."
            });

        rootPanel = new Ext.Panel({
            fullscreen: true,
            cls : 'main_panel',
            scroll: 'vertical',
            layout : 'card',
            cardSwitchAnimation :{type: 'slide', duration: 500},
            dockedItems: [button_bar],
            items : [homePage, selfSection, friendSection, searchSection,notificationSection]
        });
    
    }
});