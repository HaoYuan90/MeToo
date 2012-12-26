var mistakeID = [];

var localMistakeStore = new Ext.data.Store({
    proxy: new Ext.data.LocalStorageProxy({
        id: 'meTooMistake'
    }),
    model: 'postings',
    autoLoad: false,
    listeners: {
        read : function(){
            localMistakeStore.each(function(rec){
              //  alert(rec.get('description'));
            });
        }, 
        load : function(){
            selfSection.removeAll();
            var items = [];
            var tracker = 0;
            localMistakeStore.each(function(rec){
                items.push({
                    from_facebook_userid: rec.get('from_facebook_userid'),
                    description: rec.get('description'),
                    id: rec.get('id')
                });
            });

            localMistakeStore.each(function(rec){
                var panel = new Ext.Panel({
                    layout : {
                        type : 'hbox',
                        align : 'stretch'
                    },
                    items : [
                        selfFeed[tracker] = new Ext.Panel(Ext.apply(selfSetting,{value:items[tracker].id+','+tracker})),
                        selfReply[tracker] = new Ext.Carousel(Ext.apply(commentSetting))
                    ]
                });
                
                selfFeed[tracker].update(items[tracker]);
                //alert(items[tracker].id);
                selfSection.add(panel);
                selfSection.doLayout();
                tracker ++;
            });
        }
    }    
});

var localCommentStore = new Ext.data.Store({
    proxy: new Ext.data.LocalStorageProxy({
        id: 'meTooComment'
    }),
    model: 'comments',
    autoLoad: false,
    listeners: {
        read : function(){
            localMistakeStore.each(function(rec){
                alert(rec.get('content'));
            });
        } 
    }    
});


function setupMode(){
    //disable a number of functionality when offline
    if (!connectionStatus){
        friendButton.disable();
        searchButton.disable();
        notificationButton.disable();
        logoutButton.disable();
    }
    else {
        localStorage.clear();
        //load own mistakes
        var tempStore = new Ext.data.Store({
            model: 'postings',
            proxy: {
                type: 'ajax',
                url: 'mistake/user/'+userID,
                reader: {
                    type: 'json'
                }
            },
            listeners: {
                single : true,
                load : function(){
                    tempStore.each(function(rec){
                        mistakeID.push({
                            id : rec.get('id')
                        });
                        localMistakeStore.add(rec);
                        localMistakeStore.sync();
                    });
                } 
            }    
        });
        tempStore.load(function(){
            for(var i=0;i<mistakeID.length;i++)
                loadComments(mistakeID[i].id);
        });
    }
}

function loadComments(mistakeID){
    var tempStore = new Ext.data.Store({
        model: 'comments',
        proxy: {
            type: 'ajax',
            url: 'comment/'+mistakeID,
            reader: {
                type: 'json'
            }
        },
        listeners: {
            single : true,
            load : function(){
                tempStore.each(function(rec){
                    localCommentStore.add(rec);
                    localCommentStore.sync();
                });
            } 
        }    
    });
    tempStore.load();
}