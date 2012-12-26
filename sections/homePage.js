var mode;
var homePage = new Ext.Panel({
    style : 'background-image:url("images/concept.png"); background-color: black'    
});
if(connectionStatus)
	mode = "Online Mode";
else
	mode = 'Offline Mode';

homePage.html = '<div style ="color:white; font-family:lucida grande,tahoma,verdana,arial,sans-serif; font-size:30px; position: relative; top: 80%; left: 40%;">'+mode+'</div>'
