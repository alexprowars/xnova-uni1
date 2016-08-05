<script type="text/javascript" language="javascript">
var max = new Array({metal_m},{crystal_m},{deuterium_m});
var serverTime = new Date({time}000);
</script>
<form name="ress" id="ress"></form>
<center>
<table>
<tr>
	<td>
		<table width="752" border="0" cellpadding="0" cellspacing="0" id="resources" style="width: 722px;" padding-right="10">
		<tr>
			<td width="23%"><select size="1" onChange="eval('location=\''+this.options[this.selectedIndex].value+'\'');">{planetlist}</select></td>
			<td align="center" width="11%"><b><font color="#FFFF00">Металл</font></b></td>
			<td align="center" width="11%"><b><font color="#FFFF00">Кристалл</font></b></td>
			<td align="center" width="11%"><b><font color="#FFFF00">Дейтерий</font></b></td>
			<td align="center" width="11%"><b><font color="#FFFF00">Энергия</font></b></td>
			<td align="center" width="11%"><b><font color="#FFFF00">Заряд</font></b></td>
			<td align="center" width="11%"><b><font color="#FFFF00">Кредиты</font></b></td>
			<td align="center" width="11%"><b><font color="#FFFF00">Сообщения</font></b></td>
		</tr>
		<tr>
		    <td align="center"><b><font color="#FFFF00">{Ressverf}</font></b></td>
			<td align="center"><script>document.write(format({metal}));</script></td>
			<td align="center"><script>document.write(format({crystal}));</script></td>
			<td align="center"><script>document.write(format({deuterium}));</script></td>
			<td align="center">{energy_total}</td>
			<td align="center">{energy_ak}%</td>
			<td align="center">{credits}</td>
			<td align="center">{message}</td>
		</tr>
		<tr>
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
</center>