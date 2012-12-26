var searchFeed = [];
var searchReply = [];
    
var searchSetting = {
    flex : 2,
    tpl : postingTemplate,
    
    listeners: {
        added: function(self, container , pos){
            //LOADING REPLY
            var temp = self.value.split(',');
            var key = temp[0];
            var index = temp[1];
            //alert(key + '  '+index);
            var searchStore = new Ext.data.Store({
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
                        searchStore.each(function(rec){
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
                        searchStore.each(function(rec){
                            var commentTemp = new Ext.Panel({
                                tpl : commentTemplate,
                                cls : 'card',
                            });
                            commentTemp.update(comments[i]);
                            searchReply[index].add(commentTemp);
                            i++;
                        });  
                        searchReply[index].doLayout();            
                    }   
                }    
            });
            searchStore.load(function(){appMask.hide()});
        //FINISH LOADING REPLY
        }
    }
}

//the root panel for friendSection
var searchSection = new Ext.Panel({
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
   