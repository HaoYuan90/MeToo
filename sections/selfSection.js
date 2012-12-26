//this part of code deals with self table. 
//related api are Ext.apply, Xtemplate and store
//self - explainable :D
//apologize for all the vulga names i give to poor panels :X
    
var selfFeed = [];
var selfReply = [];
        
//default setting for horizontal carousel 
//oncardswitch event to reload the comments (vertical carousel) to the corresponding one  
var selfSetting = {
    flex : 2,
    tpl : postingTemplateOne,
    
    listeners: {
        added: function(self, container , pos){
            
            var temp = self.value.split(',');
            var key = temp[0];
            var index = temp[1];
                
            if(connectionStatus){
                //LOADING REPLY in online mode
                //alert(key + '  '+index);
                var selfReplyStore = new Ext.data.Store({
                    model: 'comments',
                    proxy: {
                        type: 'ajax',
                        url: 'comment/'+key,
                        reader: {
                            type: 'json'
                        }
                    },
                    listeners: {
                        single : true,
                        datachanged : function(){
                            var comments = [];
                            selfReplyStore.each(function(rec){
				if(rec.get('from_facebook_user_id')>0){
                                comments.push({
                                    content: rec.get('content'),
                                    dpURL: "http://graph.facebook.com/"+rec.get('from_facebook_user_id')+"/picture",
				    from_facebook_user_name : rec.get('from_facebook_user_name')
                                });}
				else{
				comments.push({
                                    content: rec.get('content'),
                                    dpURL: "images/offline.gif",
				    from_facebook_user_name : rec.get('from_facebook_user_name')
                                });}
                            });
                            var i=0;
                            selfReplyStore.each(function(rec){
                                var commentTemp = new Ext.Panel({
                                    tpl : commentTemplateOne,
                                    cls : 'card',
                                    style:'padding-left:10px;',

                                });
                                commentTemp.update(comments[i]);
                                selfReply[index].add(commentTemp);
                                i++;
                            });  
                            selfReply[index].doLayout();            
                        }   
                    }    
                });
                selfReplyStore.load();
            //FINISH LOADING REPLY in online mode
            }
            else{
            //loading reply offline mode
                var tempStore = new Ext.data.Store({
                    model: 'comments',
                    proxy: new Ext.data.LocalStorageProxy({
                        id: 'meTooComment'
                    }),
                    autoLoad: false, 
                    listeners: {
                        single : true,
                        load : function(){
                            var comments = [];
                            tempStore.each(function(rec){
                                comments.push({
                                    content: rec.get('content'),
                                    dpURL: "images/offline.gif"
                                });
                            });
                            var i=0;
                            tempStore.each(function(rec){
                                if(rec.get('mistake_post_id') == key){
                                    var commentTemp = new Ext.Panel({
                                        tpl : commentTemplateOne,
                                        cls : 'card',
                                    });
                                    commentTemp.update(comments[i]);
                                    selfReply[index].add(commentTemp);
                                }
                                i++;
                            });  
                            selfReply[index].doLayout();            
                        }   
                    }    
                });
                tempStore.load();
            //finish loading reply offline mode
            }
        }
    }
}

//the root panel for friendSection
var selfSection = new Ext.Panel({
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
    
function loadSelfSection(){
    selfSection.removeAll();
    //LOAD MISTAKES
    var tracker = 0;
    
    var selfTempStore = new Ext.data.Store({
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
            datachanged : function(){
                var items = [];
                
                selfTempStore.each(function(rec){
                    items.push({
                        from_facebook_userid: rec.get('from_facebook_userid'),
                        description: rec.get('description'),
                        id: rec.get('id')
                    });
                });
            
                selfTempStore.each(function(rec){
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
    selfTempStore.load(function(){appMask.hide()});
    rootPanel.setActiveItem(selfSection);
}

function offlineLoad(){
    localMistakeStore.load(function(){appMask.hide()});
    rootPanel.setActiveItem(selfSection);
}

        
   