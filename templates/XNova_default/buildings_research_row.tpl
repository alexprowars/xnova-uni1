{td1}
<td>
	<table width="350" style="border-spacing:0px;"><tr>
	<td class="l" width="120">
		<a href="?set=infos&gid={tech_id}"><img src="{dpath}gebaeude/{tech_id}.gif" align="top" width="120" height="120"  onmouseover="return overlib('<center>{tech_descr}</center>',LEFT,WIDTH,150,FGCOLOR,'#465673');" onmouseout="nd()"></a>
	</td>
	<th style="text-align:left;vertical-align:top;">
		<a href="?set=infos&gid={tech_id}">{tech_name}</a><br>
		<b>Уровень:</b> <u>&nbsp;{tech_level}&nbsp;</u><br>		
		{search_time}
		<br>{add}
	</th>
	</tr><tr>
	<td colspan="2" class="c" align="center"><b>Необходимые ресурсы</b><br>{tech_price}</td>
	</tr><tr>
	<td colspan="2" class="k" align="center" height="30">{tech_link}</td>
	</tr></table>
</td>
{td2}