<style>
.date1	{ font-family: Tahoma, Verdana; font-size: 11px; text-decoration:none; font-weight:normal; color: #007000; }
.date2	{ font-family: Tahoma, Verdana; font-size: 11px; text-decoration:none; font-weight:normal; color: #007000; background-color: #00FFAA }
.to 	{ CURSOR: Hand; COLOR: #FFFFFF; font-weight: bold; }
.player	{ color: #0046D5; font-weight:bold; cursor:hand; }
.private 	{ COLOR: Red; font-weight:bold; cursor:hand; }
</style>
<input type="hidden" name="message_id" value="1">

<br><br>

<table align="center" width='95%'><tbody>

<tr><td class="c"><b>Межгалактический чат</b></td><td class="c" width=20%>Список собеседников <a href="#" onclick="online();">[обновить]</a></td></tr>

<tr><th><div id="shoutbox" style="margin: 5px; vertical-align: text-top; height: 360px; overflow:auto;"></div></th>

<th>
<div id="online" style="vertical-align: text-top; height: 360px; overflow:auto;">Загрузка...</div>
</th>

</tr>

<tr><th colspan=2 nowrap>
<input name="msg" type="text" id="msg" style="width:95%" maxlength="750"><br>
<input type="button" name="send" value="Отправить" id="send" onClick="addMessage()">
<input type="button" name="smils" value="Смайлы" id="smils" onClick="ShowSmiles()">
<input type="button" name="clear" value="Очистить" id="clear" onClick="ClearChat()">
<br><div id="new_msg"></div>
</th>
</tr>
</table>
<br>
<div id="sm" style="display:none">
<table align="center" width='95%'>
<tr><td class="c" ><b>Панель смайлов</b></td></tr>
<tr><th><div id="smiles"></div></th></tr>
</table>
</div>

<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/smiles.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/chat.js"></script>
<script>
function doSomething(e){
 	if (!e) var e = window.event;
	if (e.keyCode == 13)
		addMessage();
  	return true;
}
window.document.onkeydown = doSomething;
var onlinetime = setTimeout(online, 5000);
</script>