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
	<td colspan="4" class="c">События</td>
</tr>
{fleet_list}
<tr>
	<th>{moon_img}<br>{moon}</th>
	<th colspan="2"><img src="{dpath}planeten/{planet_image}.jpg" height="200" width="200"><br>Координаты: <a href="?set=galaxy&mode=0&galaxy={galaxy_galaxy}&system={galaxy_system}">[{galaxy_galaxy}:{galaxy_system}:{galaxy_planet}]</a><br>{building}</th>
	<th class="s" valign="top">
		<table align="center" border="0">
		<tr>
			<th width="40%">Игрок:</th><th>{user_username}</th>
		</tr><tr>
			<th width="90">Постройки:</th><th><font color="green">{user_points}</font></th>
		</tr><tr>
			<th width="90">Флот:</th><th><font color="green">{user_fleet}</font></th>
		</tr><tr>
			<th width="90">Исследования:</th><th><font color="green">{player_points_tech}</font></th>
		</tr><tr>
			<th width="90">Всего:</th><th><font color="green">{total_points}</font></th>
		</tr><tr>
			<th width="90">Место:</th><th><a href="?set=stat&range={user_rank}">{user_rank}</a> из {max_users} ({ile})</th>
		</tr><tr>
			<th colspan="2">Промышленная отрасль:</th>
		</tr><tr>
			<th width="90">Уровень:</th><th>{lvl_minier}</th>
		</tr><tr>
			<th width="90">Опыт:</th><th>{xpminier} / {lvl_up_minier}</th>
		</tr><tr>
			<th colspan="2">Военная отрасль:</th>
		</tr><tr>
			<th width="90">Уровень:</th><th>{lvl_raid}</th>
		</tr><tr>
			<th width="90">Опыт:</th><th>{xpraid} / {lvl_up_raid}</th>
		</tr>
		</table>
	</th>
</tr>
<tr>
	<th>{Diameter}</th>
	<th colspan="3">{planet_diameter} км (<a title="{Developed_fields}">{planet_field_current}</a> / <a title="{max_eveloped_fields}">{planet_field_max}</a> {fields})</th>
</tr>
	<th >Занятость полей</th>
	<th colspan="3" align="center">
		<div  style="border: 1px solid rgb(153, 153, 255); width: 100%;" align="center">
		<div  id="CaseBarre" style="background-color: {case_barre_barcolor}; width: {case_pourcentage}%;">
		<font color="#CCF19F">{case_pourcentage}%</font>&nbsp;</div>
	</th>
</tr>
<tr>
	<th>Температура</th>
	<th colspan="3">от. {planet_temp_min}&deg;C до {planet_temp_max}&deg;C</th>
</tr>
<tr>
	<th>Обломки</th>
	<th colspan="3">Металл: {metal_debris} / Кристалл: {crystal_debris}{get_link}</th>
</tr>
<tr>
	<th rowspan="2">Бои</th>
	<th colspan="2" align="center">Выиграно: {raids_win}</th>
	<th align="center">Поражений: {raids_lose}</th>
</tr>
<tr>
	<th colspan="4" align="center">Проведено боёв: {raids}</th>
</tr>
<tr>
	<th>Р.П.</th>
	<th colspan="2" align="center"><a href="http://uni1.xnova.su/go.php?{user_id}" target="_blank">http://uni1.xnova.su/go.php?{user_id}</a></th>
	<th>Заходов: {links}</th>
</tr>
</table>
</td><td valign="top"><table class="s" valign="top" border="0"><tr>{anothers_planets}</tr></table></td></tr></table>
<br>
{banner}