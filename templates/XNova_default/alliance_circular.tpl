<script src="scripts/cntchar.js" type="text/javascript"></script>
<SCRIPT LANGUAGE="JavaScript">
function smiles() {
  var x = event.screenX - 100;
  var y = event.screenY - 350;
  var sFeatures = 'left='+x+',top='+y+',height=600,width=365,scrollbars=yes';
  window.open("/smiles.php?form=1", "Смайлы", sFeatures);
}
</script>
<br>
<form action="?set=alliance&mode=circular&sendmail=1" method="post">
  <table width=719>
	<tr>
	  <td class="c" colspan=2>Отправить сообщение в чат альянса</td>
	</tr>
	<tr>
	  <th>Рангу:</th>
	  <th>
		<select name="r">
		  {r_list}
		</select>
	  </th>
	</tr>

	<tr>
	  <th>{Text_mail} (<span id="cntChars">0</span> / 5000 {characters})<br><center><a href="#" onclick="smiles();">Смайлы</a></center></th>
	  <th>
	    <textarea name="text" cols="60" rows="10" onkeyup="javascript:cntchar(5000)"></textarea>
	  </th>
	</tr>
	<tr>
	  <td class="c"><a href="?set=alliance">{Back}</a></td>
	  <td class="c">
		<input type="reset" value="{Clear}">
		<input type="submit" value="{Send}">
	  </td>
	</tr>
  </table>
</form>
