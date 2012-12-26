//this part of code deals with friend table. 
var friendFeed = [];
var friendReply = [];
    
//the template for cover
var coverTemplate = new Ext.XTemplate(
    '<img src = {dpURL} ></img>',
    '<p>{userName}</p>'
);
        
//default setting for horizontal carousel of friendSection  
var postingSetting = {
    flex : 2,
    listeners: {
        cardswitch: function(container, newCard, oldCard, index){
            //retrieve comment's id
            var postID = newCard.valueOf(0).value;
            var thisIndex = friendFeed.indexOf(container);
            friendReply[thisIndex].removeAll();
            
             //this page is cover page
            if(postID == 0)
                return;
            
            var temp = new Ext.data.Store({
                model: 'comments',
                proxy: {
                    type: 'ajax',
                    url: 'comment/'+postID,
                    reader: {
                        type: 'json'
                    }
                },
                listeners: {
                    single : true,
                    datachanged : function(){
                        var items = [];
    
                        temp.each(function(rec){
                           // alert(rec.get('content'));
                            if(rec.get('from_facebook_user_id')>0){
                                items.push({
                                    content: rec.get('content'),
                                    dpURL: "http://graph.facebook.com/"+rec.get('from_facebook_user_id')+"/picture",
				    from_facebook_user_name : rec.get('from_facebook_user_name')
                                });}
				else{
				items.push({
                                    content: rec.get('content'),
                                    dpURL: "images/offline.gif",
				    from_facebook_user_name : rec.get('from_facebook_user_name')
                                });}
                        });
                        
                        var i = 0;
                        temp.each(function(rec){
                            var commentTemp = new Ext.Panel({
                                tpl : commentTemplate,
                                cls: 'card',
                                style:'padding-left:10px;',

                            })
                            commentTemp.update(items[i]);
                            friendReply[thisIndex].add(commentTemp);
                            i++;
                        });              
                        friendReply[friendFeed.indexOf(container)].doLayout();
                    }   
                }    
            });
            temp.load();
        }
    }
}
//default setting for vertical carousel 
var commentSetting = {
    flex : 1,
    direction : 'vertical',
    listeners: {
        cardswitch: function(container, newCard, oldCard, index){
            console.log(container, newCard, oldCard, index);
        }
    }
}
//the root panel for friendSection
var friendSection = new Ext.Panel({
    scroll: 'vertical', 
    layout : {
                type : 'vbox',
               align : 'stretch'
            },
    defaults : {
        height:200,
        width:700,
        bodyStyle:'padding-left:5px; ',
    }
});

function loadFriendSection(){
    friendSection.removeAll();
    
    //render cover page
    for(var i = 0; i< friends.length ; i++){
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
                friendFeed[i] = new Ext.Carousel(Ext.apply(postingSetting)),
                friendReply[i] = new Ext.Carousel(Ext.apply(commentSetting))
            ]
        });
        renderCoverPage(friends[i],i);
        friendSection.add(panel);
        friendSection.doLayout();
    }
    
    for(var i = 0; i < friends.length ; i++){
        renderFriendData(friends[i],i);
    }
    rootPanel.setActiveItem(friendSection);
}

function renderCoverPage(id,i){
    FB.api('/'+id, function(response) {
        var cover = new Ext.Panel({
            tpl : coverTemplate,
            value : 0 
        });
        var coverItem = {
            userName : response.name,
            dpURL : "http://graph.facebook.com/"+id+"/picture"
        }

        cover.update(coverItem);
        friendFeed[i].insert(0,cover);
        friendFeed[i].doLayout();
        friendFeed[i].setActiveItem(0); 
    });
}

function renderFriendData(id, index){
    var friendTempStore = new Ext.data.Store({
        model: 'postings',
        proxy: {
            type: 'ajax',
            url: 'mistake/user/'+id,
            reader: {
                type: 'json'
            }
        },
        listeners: {
            single : true,
            datachanged : function(){
                var items = [];
                
                friendTempStore.each(function(rec){
                    items.push({
                        from_facebook_userid: rec.get('from_facebook_userid'),
                        description: rec.get('description'),
                        id: rec.get('id')
                    });
                });
                                
                for(var i=0 ; i < items.length ; i++){
                    var feedTemp = new Ext.Panel({
                        tpl : postingTemplate,
                        //store the posing's id inorder to get corresponding comment
                        value : items[i].id
                    })
                    feedTemp.update(items[i]);
                    friendFeed[index].add(feedTemp);
                    friendFeed[index].doLayout();
                }    
                friendFeed[index].setActiveItem(0);          
            }
        }    
    });
    friendTempStore.read(function(){appMask.hide()});
}



        
   