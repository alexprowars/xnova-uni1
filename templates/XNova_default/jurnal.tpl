<br>
<center>
	<form action="?set=logs" method="post">
	<center>
	<table width="640" border="0" cellpadding="0" cellspacing="1">
		<tr height="20">
			<td colspan="1" class="c" width=15%><center>������:</center></td>
			<td colspan="1" class="c" width=15%><center>
			<select name="journal" onChange="javascript:document.forms[1].submit()">{type}
			</select></td>
			<td colspan="1" class="c" width=10%><center>�����:</center></td>
			<td colspan="1" class="c" width=10%><center>
			<select name="days" onChange="javascript:document.forms[1].submit()">{day}
			</select></td>{count}
		</tr>
	</table>
	</center>
	</form>
</center>
{log}