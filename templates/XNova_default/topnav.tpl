
<script type="text/javascript" language="javascript">
var ress = new Array({metal}, {crystal}, {deuterium});
var max = new Array({metal_m},{crystal_m},{deuterium_m});
var production = new Array({metal_pm}, {crystal_pm}, {deuterium_pm});
window.setInterval("Res_count()",1000);
var serverTime = {time}000 - Djs + 4*3600000;
</script>
<form name="ress" id="ress" style="display:inline">
<INPUT TYPE="hidden" ID="metall" value="0">
<INPUT TYPE="hidden" ID="crystall" value="0">
<INPUT TYPE="hidden" ID="deuterium" value="0">
<INPUT TYPE="hidden" ID="bmetall" value="0">
<INPUT TYPE="hidden" ID="bcrystall" value="0">
<INPUT TYPE="hidden" ID="bdeuterium" value="0">
</form>
<center>
<table>
<tr>
	<td>
		<table width="752" border="0" cellpadding="0" cellspacing="0" id="resources" style="width: 722px;" padding-right="10">
		<tr>
		    <td align="center" width="20%"><select size="1" onChange="eval('location=\''+this.options[this.selectedIndex].value+'\'');">{planetlist}</select></td>
			<td align="center" width="10%"><a style="cursor: pointer;" onmouseover="return overlib('<table width=150><tr><td width=50% align=left><font color=white>КПД:<font></td><td width=50% align=right><font color=white>{metal_mp}%<font></td></tr><tr><td width=50% align=left><font color=white>Производство:<font></td><td width=50% align=right><font color=white>{metal_ph}<font></td></tr><tr><td width=50% align=left><font color=white>День:<font></td><td width=50% align=right><font color=white>{metal_pd}<font></td></tr></table>');" onmouseout="return nd();"><img src="{dpath}images/metall.gif" border="0" height="22" width="42"></a></td>
			<td align="center" width="10%"><a style="cursor: pointer;" onmouseover="return overlib('<table width=150><tr><td width=50% align=left><font color=white>КПД:<font></td><td width=50% align=right><font color=white>{crystal_mp}%<font></td></tr><tr><td width=50% align=left><font color=white>Производство:<font></td><td width=50% align=right><font color=white>{crystal_ph}<font></td></tr><tr><td width=50% align=left><font color=white>День:<font></td><td width=50% align=right><font color=white>{crystal_pd}<font></td></tr></table>');" onmouseout="return nd();"><img src="{dpath}images/kristall.gif" border="0" height="22" width="42"></a></td>
			<td align="center" width="10%"><a style="cursor: pointer;" onmouseover="return overlib('<table width=150><tr><td width=50% align=left><font color=white>КПД:<font></td><td width=50% align=right><font color=white>{deuterium_mp}%<font></td></tr><tr><td width=50% align=left><font color=white>Производство:<font></td><td width=50% align=right><font color=white>{deuterium_ph}<font></td></tr><tr><td width=50% align=left><font color=white>День:<font></td><td width=50% align=right><font color=white>{deuterium_pd}<font></td></tr></table>');" onmouseout="return nd();"><img src="{dpath}images/deuterium.gif" border="0" height="22" width="42"></a></td>
			<td align="center" width="10%"><img src="{dpath}images/energie.gif" border="0" height="22" width="42"></td>
			<td align="center" width="10%"><a style="cursor: pointer;" onmouseover="return overlib('<table width=170><tr><td width=50% align=left><font color=white>Вместимость:<font></td><td width=50% align=right><font color=white>{ak}<font></td></tr></table>');" onmouseout="return nd();"><img src="/images/{energy}" border="0" height="22" width="42"></a></td>
			<td align="center" width="10%"><a href="?set=infokredits" onmouseover="return overlib('<table width=450><tr><td align=center width=14%>Адмирал<br><img src=/images/admiral{admiral_ikon}.gif></td><td align=center width=14%>Инженер<br><img src=/images/ingenieur{ingenieur_ikon}.gif></td><td align=center width=14%>Геолог<br><img src=/images/geologe{geologe_ikon}.gif></td><td align=center width=14%>Технократ<br><img src=/images/technokrat{technokrat_ikon}.gif></td><td align=center width=14%>Архитектор<br><img src=/images/architector{architector_ikon}.gif></td><td align=center width=14%>Метафизик<br><img src=/images/meta{meta_ikon}.gif></td><td align=center width=14%>Наёмник<br><img src=/images/komandir{komandir_ikon}.gif></td></tr><tr><td align=center>{admiral}</td><td align=center>{ingenieur}</td><td align=center>{geologe}</td><td align=center>{technokrat}</td><td align=center>{architector}</td><td align=center>{rpgmeta}</td><td align=center>{komandir}</td></tr></table>',LEFT,WIDTH,450,FGCOLOR,'#465673');" onmouseout="return nd();"><img src="{dpath}images/kredits.gif" border="0" height="22" width="42" alt="Получить кредиты"></a></td>
			<td align="center" width="10%"><img src="{dpath}images/message.gif" border="0" height="22" width="42"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td align="center"><b><font color="#FFFF00">Металл</font></b></td>
			<td align="center"><b><font color="#FFFF00">Кристалл</font></b></td>
			<td align="center"><b><font color="#FFFF00">Дейтерий</font></b></td>
			<td align="center"><b><font color="#FFFF00">Энергия</font></b></td>
			<td align="center"><b><font color="#FFFF00">Заряд</font></b></td>
			<td align="center"><b><font color="#FFFF00">Кредиты</font></b></td>
			<td align="center"><b><font color="#FFFF00">Сообщения</font></b></td>
		</tr>
		<tr>
		    <td align="center"><b><font color="#FFFF00">{Ressverf}</font></b></td>
			<td align="center"><div id="met"></div></td>
			<td align="center"><div id="cry"></div></td>
			<td align="center"><div id="deu"></div></td>
			<td align="center">{energy_total}</td>
			<td align="center">{energy_ak}%</td>
			<td align="center">{credits}</td>
			<td align="center">{message}</td>
		</tr>
			<td align="center"><b><font color="#FFFF00">{Store_max}</font></b></td>
			<td align="center">{metal_max}</td>
			<td align="center">{crystal_max}</td>
			<td align="center">{deuterium_max}</td>
			<td align="center"><font color="#00ff00">{energy_max}</font></td>
			<td colspan="3"></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<script>Res_count();</script>
</center>