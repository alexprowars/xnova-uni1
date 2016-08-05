<br>
<form action="?set=infos&gid=34" method="post">
<table border="1">
<tr>
	<th width=300>{msg}</th>
</tr>
</table>
<br>
<table border="1">
<tr>
	<th width=300>Флоты возле планеты</th>
</tr><tr>
	<th>
		<select name="jmpto">
		{gate_dest_moons}
		</select>
	</th>
</tr>
</table>
<br>
<table width="519">
<tr>
	<th colspan="2"><input value="{gate_jump_btn}" name="send" type="submit"></th>
</tr>
{gate_script_go}
</table>