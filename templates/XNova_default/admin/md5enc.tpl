<h2>{md5_title}</h2>
<form method="post" action="?set=admin&mode=md5enc">
<table width="500" border="0" cellspacing="2" cellpadding="0" style="color:#FFFFFF">
<tr>
	<td class="c" colspan="6">{md5_pswcyp}</td>
</tr>
<tr>
	<th width="130">{md5_psw}</th>
	<th width="171"><input size=40 type="text" name="md5q" value="{md5_md5}"></th>
</tr><tr>
	<th width="130">{md5_pswenc}</th>
	<th width="171"><input size=40 type="text" name="md5w" value="{md5_enc}"></th>
</tr><tr>
	<th width="130">&nbsp;</th>
	<th width="171"><input type="submit" name="ok" value="{md5_doit}"></th>
</tr>
</table>
</form>