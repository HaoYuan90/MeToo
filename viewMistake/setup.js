 Ext.setup({
    onReady: function() {
        rootPanel = new Ext.Panel({
            fullscreen: true,
            scroll: 'vertical',
            dockedItems: [fbLike,rightSection,leftSection],
            items : [commitList]
        });
    }
});