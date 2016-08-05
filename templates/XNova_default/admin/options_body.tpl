<h2>{adm_opt_title}</h2>
<form action="?set=admin&mode=options" method="post">
<input type="hidden" name="opt_save" value="1">
<table width="519" style="color:#FFFFFF">
<tbody>
<tr>
	<td class="c" colspan="2">{adm_opt_plan_gala}</td>
</tr><tr>
	<th>{adm_opt_game_gpos}</th>
	<th><input name="LastSettedGalaxyPos" maxlength="1" size="5" value="{LastSettedGalaxyPos}" type="text"></th>
</tr><tr>
	<th>{adm_opt_game_spos}</th>
	<th><input name="LastSettedSystemPos" maxlength="1" size="5" value="{LastSettedSystemPos}" type="text"></th>
</tr><tr>
	<th colspan="2"><input value="{adm_opt_btn_save}" type="submit"></th>
</tr>
</tbody>
</table>
</form>