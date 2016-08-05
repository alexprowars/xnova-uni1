<table border="0" cellpadding="0" cellspacing="1" align="left" style="padding-left:40px;padding-top:20px;padding-bottom:2px;">
<tr valign="left"><td class="c" colspan="{mount}">{imperium_vision}</td></tr>
<tr height="75"><th colspan="2">&nbsp;</th>{file_images}<th width=90>Сумма</th></tr>

<tr><th colspan="2">{name}</th>{file_names}<th>&nbsp;</th></tr>
<tr><th colspan="2">{coordinates}</th>{file_coordinates}<th>&nbsp;</th></tr>
<tr><th colspan="2">{fields}</th>{file_fields}<th>{file_fields_c} / {file_fields_t}</th></tr>

<tr><td class="c" colspan="{mount}" align="left">{resources}</td></tr>
<tr><th rowspan="4">на планете</th><th>{metal}</th>{file_metal}<th>{file_metal_t}</th></tr>
<tr><th>{crystal}</th>{file_crystal}<th>{file_crystal_t}</th></tr>
<tr><th>{deuterium}</th>{file_deuterium}<th>{file_deuterium_t}</th></tr>
<tr><th>Заряд</th>{file_zar}<th><font color="#00ff00">100</font>%</th></tr>

<tr><td class="c" colspan="{mount}"><font style="font-size:1px;">&nbsp;</font></td></tr>

<tr><th rowspan="3">в час</th><th>{metal}</th>{file_metal_ph}<th>{file_metal_ph_t}</th></tr>
<tr><th>{crystal}</th>{file_crystal_ph}<th>{file_crystal_ph_t}</th></tr>
<tr><th>{deuterium}</th>{file_deuterium_ph}<th>{file_deuterium_ph_t}</th></tr>
<tr><td class="c" colspan="{mount}"><font style="font-size:1px;">&nbsp;</font></td></tr>

<tr><th rowspan="6">Производство</th><th>Металл</th>{file_metal_p}
<th rowspan="6">&nbsp;</th>
</tr>
<tr><th>Кристаллы</th>{file_crystal_p}</th></tr>
<tr><th>Дейтерий</th>{file_deuterium_p}</tr>
<tr><th>Солн. ст.</th>{file_solar_p}</tr>
<tr><th>Терм. ст.</th>{file_fusion_p}</tr>
<tr><th>Спутники</th>{file_solar2_p}</tr>
<tr><th colspan="{mount1}">Кредиты</th><th><font color=#FFFF00>{file_kredits}</font></th></tr>
<tr><td class="c" colspan="{mount}" align="left">{buildings}</td></tr>
	{building_row}
<tr><td class="c" colspan="{mount}" align="left">{ships}</td></tr>
	{fleet_row}
<tr><td class="c" colspan="{mount}" align="left">{defense}</td></tr>
	{defense_row}
<tr><td class="c" colspan="{mount}" align="left">{investigation}</td></tr>
	{technology_row}
</table>