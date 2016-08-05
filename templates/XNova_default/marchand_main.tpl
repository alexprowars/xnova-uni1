<form action="?set=marchand" method="post">
<input type="hidden" name="action" value="2">
<br>
<table width="700">
<tr>
	<td class="c" align="center"><b>Обмен сырья</b><td>
</tr><tr>
	<th>{mod_ma_typer} 
	<select name="choix"><option value="metal">{Metal}</option><option value="cristal">{Crystal}</option><option value="deut">{Deuterium}</option>
	</select>
	<br>{mod_ma_rates}<br>
	</th>
</tr>
<tr>
	<td class="c" align="center"><input type="submit" value="[ Обменять ]" /></td>
</tr>
</table>
</form>
<br></br>
<script>
function calcul () {

     kr = document.forms['kred'].elements['credits'].value;

     if (kr == "") kr = 0;

     if (!isNaN(document.forms['kred'].elements['credits'].value)) {

          document.getElementById("m").innerHTML = Math.round({shopmet}*kr);
          document.getElementById("k").innerHTML = Math.round({shopkris}*kr);
          document.getElementById("d").innerHTML = Math.round({shopdeyt}*kr);

          document.getElementById("krr").innerHTML = kr;
     }
}

</script>
<form action="?set=marchand" method="post" name="kred">
<input type="hidden" name="action" value="3">
<br>
<table width="700">
<tr>
     <td class="c" colspan="2" align="center"><b>Продажа кредитов (Кол-во получаемых ресурсов зависит от уровня развития производства)</b></td>
</tr>
<tr>
     <th class="c" width="430">Купить: <font color="#90EE90"><b id="m">0</b></font> металла, <font color="#90EE90"><b id="k">0</b></font> кристалла, <font color="#90EE90"><b id="d">0</b></font> дейтерия</th>
     <th width="170">за <font color="#FF9900"><b id="krr">0</b></font> кредитов</th>
</tr>
<tr>
     <th class="c">Введите сумму кредитов для продажи:<br></th>
     <th><input name="credits" type="text" size="10" maxlength="9" value="0" onkeyup="calcul()"></th>
</tr>
<tr>
     <td class="c" colspan="2" align="center"><input type="submit" value="[ Купить ресурсы ]" /></td>
</tr>
</table>
</form>