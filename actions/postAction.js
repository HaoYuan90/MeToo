var postForm = new Ext.form.FormPanel({
    url : 'mistake/',
    items : [{
            xtype: 'textareafield',
            name : 'message',
            label: 'what did you do:',
            maxRows : 5
        },
        {
            xtype: 'hiddenfield',
            name : 'facebook_id',
            //set this value as the current userID
        },
        {
            xtype: 'togglefield',
            name : 'post_to_wall',
            label: 'post to wall on facebook'
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
                        postSheet.hide();
                    }
                },
                {
                    text : 'Reset',
                    handler : function() {
                        postForm.reset();
                    }
                },
                {
                    text: 'post',
                    ui: 'confirm',
                    handler : function() {
                        postForm.submit({
                            waitMsg : {message:'Submitting', cls : 'demos-loading'}
                        });
                        postSheet.hide();
                        postForm.reset();
                    }
                }
            ]
        }
    ]
});

var postSheet = new Ext.ActionSheet({
    items: [postForm]
});

function createNewPost (){
    postForm.items.get(1).setValue(userID);
    postSheet.show();
}