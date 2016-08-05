<script src="scripts/cntchar.js" type="text/javascript"></script>
<SCRIPT LANGUAGE="JavaScript">
function smiles() {
  var x = event.screenX - 100;
  var y = event.screenY - 350;
  var sFeatures = 'left='+x+',top='+y+',height=600,width=365,scrollbars=yes';
  window.open("/smiles.php?form=0", "ׁלאיכû", sFeatures);
}
</script>
<br />
<center>
<form action="?set=messages&mode=write&id={id}" method="post">
<table width="519">
<tr>
	<td class="c" colspan="2">{Send_message}</td>
</tr><tr>
	<th>{Recipient}</th>
	<th><input type="text" name="to" size="40" value="{to}" /></th>
</tr><tr>
	<th>{Message}(<span id="cntChars">0</span> / 5000 {characters})<br><center><a href="#" onclick="smiles();">ׁלאיכû</a></center></th>
	<th><textarea name="text" cols="40" rows="10" size="100" onkeyup="javascript:cntchar(5000)">{text}</textarea></th>
</tr><tr>
	<th colspan="2"><input type="submit" value="{Envoyer}" /></th>
</tr>
</table>
</form>
</center>