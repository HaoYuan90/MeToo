var fbLike = new Ext.Toolbar({
    items: [
	{
	    xtype: 'button',
	    ui: 'back',
	    text: 'return',
	    handler : function(){
		window.location = 'http://ec2-50-17-68-237.compute-1.amazonaws.com/MobileCloud/appPage.html'
	    }
	},
	{
	    xtype: 'spacer',
	    width: 150
	},
	{
	    xtype:'box', 
	    el:'fb-login-button', 
	    autoShow:true 
	},
    {
	    xtype:'button', 
	    text:'reply', 
	    handler : function(){
            var stub =[];
            stub.id = mistakeid;
            reply(stub);
        } 
	},
    {
	    xtype:'button', 
	    text:'metoo', 
	    handler : function(){
            var stub = [];
            stub.id = mistakeid;
            commitAction(stub);
        } 
	}
    ]
});

var leftTemplate = new Ext.XTemplate(
    '<p style="padding-left:20px;padding-top:5px;padding-right:5px;word-wrap:break-word;">{description}</p>'
);

var leftSection = new Ext.Panel({
    tpl : leftTemplate,
    height : 330,
    scroll : 'vertical',
    style: 'background-color:#C2FFA3;'

});

var rightSection = new Ext.Panel({
    dock : 'right',
    width : 250,
    scroll : 'vertical',
    layout : {
        type : 'vbox',
        alignment : 'stretch'
    },

     defaults : {
        height:160,
        width:230,
        margin:10
    },



});

var listSetting = {
    itemTpl: '<div>{date}</div>',
    selModel: {
        mode: 'SINGLE',
        allowDeselect: true
    },
    grouped: false,
    indexBar: false,

    onItemDisclosure: false,
    
    store: new Ext.data.Store({
        model: 'commits',
        proxy: {
            type: 'ajax',
            url: "commit/" + mistakeid,
            reader: {
                type: 'json'
            }
        }
    })
};

var commitList = new Ext.List(Ext.apply(listSetting));
commitList.store.load();

var mistake;
var comments;

// get mistake details
$.ajax({
    type: "GET",
    url: "mistake/" + mistakeid,
    
     success: function (response) {
        // render mistake here
       mistake = response;	
       mistake = Ext.decode(mistake);
       leftSection.update(mistake[0]);								
    },
    error: function (hqXHR, textStatus, errorThrown) {
        alert("fail");
    }					
});			
// get mistake comments
$.ajax({
    type: "GET",
    url: "comment/" + mistakeid,
    
    success: function (response) {
       comments = response;
       comments = Ext.decode(comments);
       for(var i=0;i<comments.length;i++){
		if(comments[i].from_facebook_user_id == -1)
			comments[i].dpURL = "images/offline.gif"
		else
            		comments[i].dpURL = "http://graph.facebook.com/"+comments[i].from_facebook_user_id+"/picture";
            var temp = new Ext.Panel({
                tpl : commentTemplate,
                height:160,
                width:230,
                scroll : 'vertical',
                style :'padding-left:15px;padding-right:15px;background-color: #FFFFCC;overflow:hidden;border-radius: 15px;',
                   
            })
            temp.update(comments[i]);
            rightSection.add(temp);
       } 
       rightSection.doLayout();
    },
    
    error: function (hqXHR, textStatus, errorThrown) {
        alert("fail");
    }					
});									