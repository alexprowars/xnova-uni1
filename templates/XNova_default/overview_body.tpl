<center>
<table><tr><td valign="top">
<table width="550">
<tr><font size="5" color="#FF0000"></font>
	<td class="c" colspan="4">
		<a href="?set=overview&mode=renameplanet" title="{Planet_menu}">{Planet} "{planet_name}"</a> ({user_username})
	</td>
</tr>
{Have_new_message}
{Have_new_level}
<tr>
	<th>{Server_time}</th>
	<th colspan=3><div id="clock">{time}</div><script>UpdateClock();</script></th>
</tr>

<tr>
	<td colspan="4" class="c">�������</td>
</tr>
{fleet_list}
<tr>
	<th>{moon_img}<br>{moon}</th>
	<th colspan="2"><img src="{dpath}planeten/{planet_image}.jpg" height="200" width="200"><br>����������: <a href="?set=galaxy&mode=0&galaxy={galaxy_galaxy}&system={galaxy_system}">[{galaxy_galaxy}:{galaxy_system}:{galaxy_planet}]</a><br>{building}</th>
	<th class="s" valign="top">
		<table align="center" border="0">
		<tr>
			<th width="40%">�����:</th><th>{user_username}</th>
		</tr><tr>
			<th width="90">���������:</th><th><font color="green">{user_points}</font></th>
		</tr><tr>
			<th width="90">����:</th><th><font color="green">{user_fleet}</font></th>
		</tr><tr>
			<th width="90">������������:</th><th><font color="green">{player_points_tech}</font></th>
		</tr><tr>
			<th width="90">�����:</th><th><font color="green">{total_points}</font></th>
		</tr><tr>
			<th width="90">�����:</th><th><a href="?set=stat&range={user_rank}">{user_rank}</a> �� {max_users} ({ile})</th>
		</tr><tr>
			<th colspan="2">������������ �������:</th>
		</tr><tr>
			<th width="90">�������:</th><th>{lvl_minier}</th>
		</tr><tr>
			<th width="90">����:</th><th>{xpminier} / {lvl_up_minier}</th>
		</tr><tr>
			<th colspan="2">������� �������:</th>
		</tr><tr>
			<th width="90">�������:</th><th>{lvl_raid}</th>
		</tr><tr>
			<th width="90">����:</th><th>{xpraid} / {lvl_up_raid}</th>
		</tr>
		</table>
	</th>
</tr>
<tr>
	<th>{Diameter}</th>
	<th colspan="3">{planet_diameter} �� (<a title="{Developed_fields}">{planet_field_current}</a> / <a title="{max_eveloped_fields}">{planet_field_max}</a> {fields})</th>
</tr>
	<th >��������� �����</th>
	<th colspan="3" align="center">
		<div  style="border: 1px solid rgb(153, 153, 255); width: 100%;" align="center">
		<div  id="CaseBarre" style="background-color: {case_barre_barcolor}; width: {case_pourcentage}%;">
		<font color="#CCF19F">{case_pourcentage}%</font>&nbsp;</div>
	</th>
</tr>
<tr>
	<th>�����������</th>
	<th colspan="3">��. {planet_temp_min}&deg;C �� {planet_temp_max}&deg;C</th>
</tr>
<tr>
	<th>�������</th>
	<th colspan="3">������: {metal_debris} / ��������: {crystal_debris}{get_link}</th>
</tr>
<tr>
	<th rowspan="2">���</th>
	<th colspan="2" align="center">��������: {raids_win}</th>
	<th align="center">���������: {raids_lose}</th>
</tr>
<tr>
	<th colspan="4" align="center">��������� ���: {raids}</th>
</tr>
<tr>
	<th>�.�.</th>
	<th colspan="2" align="center"><a href="http://uni1.xnova.su/go.php?{user_id}" target="_blank">http://uni1.xnova.su/go.php?{user_id}</a></th>
	<th>�������: {links}</th>
</tr>
</table>
</td><td valign="top"><table class="s" valign="top" border="0"><tr>{anothers_planets}</tr></table></td></tr></table>
<br>
{banner}