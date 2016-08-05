function to(login) {
    	msg.focus();
        	msg.value = 'дл€ ['+login+'] ' + msg.value;
        	msg.focus();
}

function pp(login) {
        	msg.focus();
        	msg.value = 'приватно ['+login+'] ' + msg.value;
        	msg.focus();
}

var ChatTimer;
function StopChatTimer() {
  	if (ChatTimer)
    		clearTimeout(ChatTimer);
  	return 1;
}

function RefreshChat() {
  	StopChatTimer();
	showMessage();
  	ChatTimer = setTimeout(RefreshChat, 10000);
}

function MsgSent(msg_id) {

        document.all("message_id").value = msg_id;

        if (ChatTimer) clearTimeout(ChatTimer);
        ChatTimer = setTimeout(showMessage, 10000);
}

function ChatMsg (Time, Player, Msg, Me, My) {
	var str = "";

	var i,j=0;
	for (i = 0; i < sm.length; i += 3) {
		while(Msg.indexOf(':'+sm[i]+':') >= 0) {
			Msg = Msg.replace(':'+sm[i]+':', '<img border=0 src="/images/smile/' + sm[i] + '.gif" ' + 'width='+sm[i+1]+' height='+sm[i+2] +' style="cursor:pointer;cursor:hand;" onclick="S(\'' +sm[i]+'\')">');
			if (++j >= 5) break;
		}
		if(j>=5) break;
	}

	if (!Time) return;
	if (Me>0) str += "<FONT class=date2>";
	else str += "<FONT class=date1>";
	if (!Player) str += Time+"</FONT> ";
	else {
		str += Time+"</FONT> [";
		if (My==1) str += "<B style='COLOR: Red;'>";
		else str += "<B class=to onclick='to(\""+Player+"\");' style='cursor:pointer;cursor:hand;'>";
		str += Player+"</B>] ";
	}
	str += Msg+"<BR>";

	document.getElementById('shoutbox').innerHTML += '<div align="left">'+str+'</div>';
	descendreTchat();

}

function descendreTchat(){
 	var elDiv =document.getElementById('shoutbox');
 	elDiv.scrollTop = elDiv.scrollHeight-elDiv.offsetHeight;
}

function addMessage() {

	var data = msg.value;

	data = data.replace('%', '%25');
	while (data.indexOf('+')>=0) data = data.replace('+', '%2B');
	while (data.indexOf('#')>=0) data = data.replace('#', '%23');
	while (data.indexOf('&')>=0) data = data.replace('&', '%26');
	while (data.indexOf('?')>=0) data = data.replace('?', '%3F');
	while (data.indexOf('\'')>=0)data = data.replace('\'', '`');

	data = "msg="+data;
	msg.value = "";

    	new Ajax.Request('?set=chat', 
    	{
        		method: 'post',
		parameters: data,
        		onSuccess: function(transport) {}
    	});
}

function showMessage() {
	new Ajax.Request('?set=chat&message_id='+document.all("message_id").value+'&rnd=' + Math.random(), 
	{
		method: 'get',
		onSuccess: function(transport) 
		{
				eval(transport.responseText);	
		}
	});
}

function online() {
	new Ajax.Request('?set=chat&online=1&rnd=' + Math.random(), 
	{
		method: 'get',
		onSuccess: function(transport) 
		{
				document.getElementById('online').innerHTML = transport.responseText;	
				clearTimeout(onlinetime);
				onlinetime = setTimeout(online, 60000);
		}
	});
}

function S(name)
{       
        msg.value  += ':'+name+':';
msg.focus();	
}

var sml = 0;

function ShowSmiles () {
	str = ""

	if (sml == 1) {
		HideSmiles();
		return;
	}
	
	sml = 1;
	var i = 0;
	while(i < sm.length) {
       	var s = sm[i++];
        	str += '<IMG SRC=images/smile/'+s+'.gif WIDTH='+sm[i++]+' HEIGHT='+sm[i++]+' BORDER=0 ALT="'+s+'" onclick="S(\''+s+'\')" style="cursor:hand"> ';
	}
	$('smiles').innerHTML = str;
	$('sm').style.display = "block";
}
function HideSmiles () {
	$('smiles').innerHTML = "";
	$('sm').style.display = "none";
	sml = 0;
}

function ClearChat(){
	$("shoutbox").innerHTML = '';
}

function NewMessage (m1, m2) {
	str = "";

	if (m1 == 1)
		str += "” вас <b>"+m1+"</b> непрочитанное личное сообщение.<br>";
	else if (m1 > 1)
		str += "” вас <b>"+m1+"</b> непрочитанных личных сообщений.<br>";

	if (m2 == 1)
		str += "” вас <b>"+m2+"</b> непрочитанное сообщение аль€нса.";
	else if (m2 > 1)
		str += "” вас <b>"+m2+"</b> непрочитанных сообщений аль€нса.";

	$('new_msg').innerHTML = str;
}


setTimeout(RefreshChat, 3000);