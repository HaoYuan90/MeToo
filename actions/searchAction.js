var searchKey;
var searchResult;

var searchSheet = new Ext.ActionSheet({
    items: [
    {
        xtype: 'textfield',
        name : 'searchKey',
        label: 'search for:'
    }
    ],
    dockedItems : [
        {
            xtype: 'toolbar',
            dock : 'bottom',
            items : [
                {
                    text : 'cancel',
                    handler : function(event) {
                        searchSheet.hide();
                    }
                },
                {
                    text: 'search',
                    ui: 'confirm',
                    handler : function() {
                    
                        searchKey = searchSheet.items.get(0).getValue();  
                        
                        searchResult = new Ext.data.Store({
                        model: 'postings',
                        proxy: {
                            type: 'ajax',
                            url: 'search/mistake/'+searchKey,
                            reader: {
                                type: 'json'
                            }
                        },
                        listeners: {
                            single : true,
                            datachanged : function(){
                            var items = [];
                            var tracker = 0;
            
                            searchResult.each(function(rec){
                                items.push({
                                    from_facebook_userid: rec.get('from_facebook_userid'),
                                    description: rec.get('description'),
                                    id: rec.get('id')
                                });
                            });
            
                            searchResult.each(function(rec){
                                var panel = new Ext.Panel({
                                    layout : {
                                        type : 'hbox',
                                        align : 'stretch'
                                    },
                                     style:'margin:1em auto;color:black;border-radius: 15px;background-color:#C2FFA3;-webkit-box-shadow: 0 15px 10px #000000;word-wrap: break-word;',
                            defaults : {
        height:180,
                width:300,

        bodyStyle:'padding-left:20px;padding-top:10px;padding-bottom:10px; overflow:hidden;',
        
            },
                                    items : [
                                        searchFeed[tracker] = new Ext.Panel(Ext.apply(searchSetting,{value:items[tracker].id+','+tracker})),
                                        searchReply[tracker] = new Ext.Carousel(Ext.apply(commentSetting))
                                    ]
                                });
                
                                searchFeed[tracker].update(items[tracker]);
                                
                                searchSection.add(panel);
                                searchSection.doLayout();
                                tracker ++;
                            });
                        }
                    }                   
                });
                    
                searchResult.read();
                searchSheet.hide();
                searchSheet.items.get(0).setValue('');
                rootPanel.setActiveItem(searchSection);
                    }
                }
            ]
        }
    ]
});

function performSearch(){
    appMask.show();
    searchSection.removeAll();
    searchSheet.show();
}