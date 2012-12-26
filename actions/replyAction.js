var toPost;

var replyForm = new Ext.form.FormPanel({
    url : "comment/",
    items : [{
            xtype: 'textareafield',
            name : 'content',
            label: 'comment:',
	    maxRows: 2
        },
        {
            xtype: 'hiddenfield',
            name : 'mistakeid',
            //value to be set by button
        },
        {
            xtype: 'hiddenfield',
            name : 'author',
            //value to be set by button
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
                        replySheet.hide();
                    }
                },
                {
                    text : 'Reset',
                    handler : function() {
                        replyForm.reset();
                    }
                },
                {
                    text: 'reply',
                    ui: 'confirm',
                    handler : function() {
                        replyForm.submit({
                            waitMsg : {message:'Submitting', cls : 'demos-loading'}
                        });
                        replySheet.hide();
                        replyForm.reset();
                    }
                }
            ]
        }
    ]
});

var replySheet = new Ext.ActionSheet({
    items: [replyForm]
});

function reply (event){
    toPost = event.id;
    //alert(toPost);
    replyForm.items.get(1).setValue(toPost);
    replyForm.items.get(2).setValue(userID);
    replySheet.show();
}