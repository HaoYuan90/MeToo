Ext.regModel('postings', {
    fields: [
        { name : 'id', type : 'int' },
        { name : 'from_facebook_userid', type : 'string' },
        { name : 'description', type : 'string' },
        { name : 'status', type : 'string' },
        { name : 'post_date', type : 'int' },
        { name : 'type', type : 'string' },
        { name : 'like_count', type : 'int' },
        { name : 'comment_count', type : 'int' }
    ]
});

Ext.regModel('commits', {
    fields: [
        { name : 'mistake_id', type : 'int' },
        { name : 'date', type : 'string' }
    ]
});

Ext.regModel('comments', {
    fields: [
        { name : 'comment_id', type : 'string' },
        { name : 'mistake_post_id', type : 'string' },
        { name : 'content', type : 'string' },
        { name : 'from_facebook_user_id', type : 'string' },
        { name : 'date', type : 'string' },
	{ name : 'from_facebook_user_name', type : 'string' }
    ]
});

Ext.regModel('notification', {
    fields: [
        { name : 'id', type : 'int' },
        { name : 'content', type : 'string' }, 
        { name : 'status', type : 'string' }, 
        { name : 'facebook_userid', type : 'string' }, 
        { name : 'date', type : 'string'},
        { name : 'mistake_id', type : 'int'}
    ]
});

var postingTemplate = new Ext.XTemplate(
    '<p style="overflow:hidden;line-height: 1.2em; height: 6.5em;">{description}</p>',
    '<button id = {id} onclick="reply(this)">reply</button>',
    '<button id = {id} onclick= "viewMistake(this)">view</button>',
    '<button id = {id} onclick= "commitAction(this)">meToo</button>'
    
);
var commentTemplate = new Ext.XTemplate(
    '<img src = {dpURL} ></img>',
    '<p>{from_facebook_user_name}</p>',
    '<p>{content}</p>'
);

var postingTemplateOne = new Ext.XTemplate(
    '<p style="overflow:hidden;line-height: 1.2em; height: 8em;">{description}</p>',
    '<button id = {id} onclick="reply(this)">reply</button>',
    '<button id = {id} onclick= "viewMistake(this)">view</button>',
    '<button id = {id} onclick= "commitAction(this)">meToo</button>'
    
);
var commentTemplateOne = new Ext.XTemplate(
    '<img src = {dpURL} ></img>',
    '<p>{from_facebook_user_name}</p>',
    '<p>{content}</p>'
);
