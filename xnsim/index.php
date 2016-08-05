<html><head>
<meta http-equiv="content-type" content="text/html; windows-1251">
<meta http-equiv="content-language" content="ru">
<title>XNova SIM (0.3 beta)</title>
<link rel="stylesheet" href="xnsim.css" type="text/css">
</head>
<body>

<script>

var groups = new Array(100);

function vis_row(TAG,gID) {
  	var coll = document.getElementsByTagName(TAG);
  	if (!groups[gID]==null || groups[gID]==0) {
    		groups[gID] = 1;
  	} else {
    		groups[gID] = 0;
  	} 
  	if (coll!=null ) {
    		if (coll.length!=null) {
      			if (groups[gID]==0) {
        				for (var i=0; i<coll.length; i++) {
          					if (coll[i].id==gID) 
            						coll[i].style.display = 'none';
        				}

      			} else {
        				for (var i=0; i<coll.length; i++) {
          					if (coll[i].id==gID) 
            						coll[i].style.display = '';
        				}
      			}
   	 	}  
  	}
}

function vis_cols(TAG,PRE,sID,gID) {
  	var s = parseInt(sID);
  	var g = parseInt(gID);
  	for (var i=s; i<s+5; i++) {
    		if (i<s+g) {
      			groups[PRE+i] = 0;
      			vis_row(TAG,PRE+i);
    		} else {
      			groups[PRE+i] = 1;
      			vis_row(TAG,PRE+i);
   	 	}
  	}
}

function opt() {
  	var txt = "", tstr = "", tkey, tval;
  	var coll = document.getElementsByTagName("INPUT");
  	tkey = new Array();
  	if (coll!=null ) {
    		if (coll.length!=null) {
      			for (var i=0; i<coll.length; i++) {
        				if (coll[i].value>0) {
          					tstr = coll[i].name;
          					tval = tstr.split("-");
          					tval[0] = parseInt(tval[0].charAt(2));
          					if (tkey[tval[0]]) {
            						tkey[tval[0]] += parseInt(tval[1])+','+coll[i].value+';';
          					} else {
            						tkey[tval[0]] = parseInt(tval[1])+','+coll[i].value+';';
          					}
        				}
      			}
    		}
  	}
  	if (tkey!=null ) {
    		if (tkey.length!=null) {
      			for (var i=0; i<tkey.length; i++) {
        				if (tkey[i]) {
          					txt += tkey[i] + '|';
        				} else {
          					txt += '|';
        				}  
      			}
   		}
  	}
  	form.r.value = txt;
  	form.submit;
}





function gclear(gID) {
  	var tstr = "", tval;
  	var coll = document.getElementsByTagName("INPUT");
  	tkey = new Array();
  	if (coll!=null ) {
    		if (coll.length!=null) {
      			for (var i=0; i<coll.length; i++) {
        				tstr = coll[i].name;
        				tval = tstr.split("-");
        				tval[0] = parseInt(tval[0].charAt(2));
        				if (gID=="all") {
          					coll[i].value = 0;
        				} else if (tval[0]==gID) {
          					coll[i].value = 0;
        				} 
      			}
    		}
  	}
}

</script>

<form method="post" action="sim.php" name="form" autocomplete="off" target="_blank">
<input type="hidden" name="r" value="">
<table cellspacing="0" cellpadding="0" border="0" class="maintable">
<tr valign="top" class="main">
<td class="body leftcol main">
<table cellspacing="2" cellpadding="0" align="center">
<thead>
<tr>
<th class="spezial"> XNova SIM </th>
<th colspan="11" class="spezial">

	<SELECT NAME="Att" SIZE="1" onchange='vis_cols("TD","gr",0,this.value);'>
	<OPTION VALUE="1" SELECTED>1
	<OPTION VALUE="2">2
	<OPTION VALUE="3">3
	<OPTION VALUE="4">4
	<OPTION VALUE="5">5
	</SELECT>

 Исходная ситуация 

	<SELECT NAME="Def" SIZE="1" onchange='vis_cols("TD","gr",5,this.value);'>
	<OPTION VALUE="1" SELECTED>1
	<OPTION VALUE="2">2
	<OPTION VALUE="3">3
	<OPTION VALUE="4">4
	<OPTION VALUE="5">5
	</SELECT>

</th>
</tr>
<tr>
<th align="center" class="typ leftcol_type typ_td"> Тип </th>

	<th class="angreifer leftcol_data"> Ведущий </th>
    	<td class="angreifer leftcol_data" id='gr1'>Атакующий&nbsp;1</td>
    	<td class="angreifer leftcol_data" id='gr2'>Атакующий&nbsp;2</td>
    	<td class="angreifer leftcol_data" id='gr3'>Атакующий&nbsp;3</td>
    	<td class="angreifer leftcol_data" id='gr4'>Атакующий&nbsp;4</td>

	<th class="verteidiger leftcol_data "> Планета </th>
    	<td class="verteidiger leftcol_data " id='gr6'>Защитник&nbsp;1</td>
    	<td class="verteidiger leftcol_data " id='gr7'>Защитник&nbsp;2</td>
    	<td class="verteidiger leftcol_data " id='gr8'>Защитник&nbsp;3</td>
    	<td class="verteidiger leftcol_data " id='gr9'>Защитник&nbsp;4</td>
</tr>
</thead>
<tbody>
<tr>
<td colspan="12" class="spezial" id="tech_td"><b>Исследования и офицеры</b></td>
</tr>
<tr align=center><td><b>Оружейная техника</b></td>
<td id="gr0"><input class="number" value="0" type="text" name="gr0-109" maxlength="2"></td>
<td id="gr1"><input class="number" value="0" type="text" name="gr1-109" maxlength="2"></td>
<td id="gr2"><input class="number" value="0" type="text" name="gr2-109" maxlength="2"></td>
<td id="gr3"><input class="number" value="0" type="text" name="gr3-109" maxlength="2"></td>
<td id="gr4"><input class="number" value="0" type="text" name="gr4-109" maxlength="2"></td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-109" maxlength="2"></td>
<td id="gr6"><input class="number" value="0" type="text" name="gr6-109" maxlength="2"></td>
<td id="gr7"><input class="number" value="0" type="text" name="gr7-109" maxlength="2"></td>
<td id="gr8"><input class="number" value="0" type="text" name="gr8-109" maxlength="2"></td>
<td id="gr9"><input class="number" value="0" type="text" name="gr9-109" maxlength="2"></td>
</tr>
<tr align=center><td><b>Щитовая техника</b></td>
<td id="gr0"><input class="number" value="0" type="text" name="gr0-111" maxlength="2"></td>
<td id="gr1"><input class="number" value="0" type="text" name="gr1-111" maxlength="2"></td>
<td id="gr2"><input class="number" value="0" type="text" name="gr2-111" maxlength="2"></td>
<td id="gr3"><input class="number" value="0" type="text" name="gr3-111" maxlength="2"></td>
<td id="gr4"><input class="number" value="0" type="text" name="gr4-111" maxlength="2"></td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-111" maxlength="2"></td>
<td id="gr6"><input class="number" value="0" type="text" name="gr6-111" maxlength="2"></td>
<td id="gr7"><input class="number" value="0" type="text" name="gr7-111" maxlength="2"></td>
<td id="gr8"><input class="number" value="0" type="text" name="gr8-111" maxlength="2"></td>
<td id="gr9"><input class="number" value="0" type="text" name="gr9-111" maxlength="2"></td>
</tr>
<tr align=center><td><b>Броня космических кораблей</b></td>
<td id="gr0"><input class="number" value="0" type="text" name="gr0-110" maxlength="2"></td>
<td id="gr1"><input class="number" value="0" type="text" name="gr1-110" maxlength="2"></td>
<td id="gr2"><input class="number" value="0" type="text" name="gr2-110" maxlength="2"></td>
<td id="gr3"><input class="number" value="0" type="text" name="gr3-110" maxlength="2"></td>
<td id="gr4"><input class="number" value="0" type="text" name="gr4-110" maxlength="2"></td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-110" maxlength="2"></td>
<td id="gr6"><input class="number" value="0" type="text" name="gr6-110" maxlength="2"></td>
<td id="gr7"><input class="number" value="0" type="text" name="gr7-110" maxlength="2"></td>
<td id="gr8"><input class="number" value="0" type="text" name="gr8-110" maxlength="2"></td>
<td id="gr9"><input class="number" value="0" type="text" name="gr9-110" maxlength="2"></td>
</tr>
<tr><td colspan="12" class="spezial" id="fleet_td"><b>Флот</b></td></tr>
<tr align=center><td><b>Малый транспорт</b></td>
<td id="gr0"><input class="number" value="0" type="text" name="gr0-202" maxlength="7"></td>
<td id="gr1"><input class="number" value="0" type="text" name="gr1-202" maxlength="7"></td>
<td id="gr2"><input class="number" value="0" type="text" name="gr2-202" maxlength="7"></td>
<td id="gr3"><input class="number" value="0" type="text" name="gr3-202" maxlength="7"></td>
<td id="gr4"><input class="number" value="0" type="text" name="gr4-202" maxlength="7"></td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-202" maxlength="7"></td>
<td id="gr6"><input class="number" value="0" type="text" name="gr6-202" maxlength="7"></td>
<td id="gr7"><input class="number" value="0" type="text" name="gr7-202" maxlength="7"></td>
<td id="gr8"><input class="number" value="0" type="text" name="gr8-202" maxlength="7"></td>
<td id="gr9"><input class="number" value="0" type="text" name="gr9-202" maxlength="7"></td>
</tr>
<tr align=center><td><b>Большой транспорт</b></td>
<td id="gr0"><input class="number" value="0" type="text" name="gr0-203" maxlength="7"></td>
<td id="gr1"><input class="number" value="0" type="text" name="gr1-203" maxlength="7"></td>
<td id="gr2"><input class="number" value="0" type="text" name="gr2-203" maxlength="7"></td>
<td id="gr3"><input class="number" value="0" type="text" name="gr3-203" maxlength="7"></td>
<td id="gr4"><input class="number" value="0" type="text" name="gr4-203" maxlength="7"></td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-203" maxlength="7"></td>
<td id="gr6"><input class="number" value="0" type="text" name="gr6-203" maxlength="7"></td>
<td id="gr7"><input class="number" value="0" type="text" name="gr7-203" maxlength="7"></td>
<td id="gr8"><input class="number" value="0" type="text" name="gr8-203" maxlength="7"></td>
<td id="gr9"><input class="number" value="0" type="text" name="gr9-203" maxlength="7"></td>
</tr>
<tr align=center><td><b>Лёгкий истребитель</b></td>
<td id="gr0"><input class="number" value="0" type="text" name="gr0-204" maxlength="7"></td>
<td id="gr1"><input class="number" value="0" type="text" name="gr1-204" maxlength="7"></td>
<td id="gr2"><input class="number" value="0" type="text" name="gr2-204" maxlength="7"></td>
<td id="gr3"><input class="number" value="0" type="text" name="gr3-204" maxlength="7"></td>
<td id="gr4"><input class="number" value="0" type="text" name="gr4-204" maxlength="7"></td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-204" maxlength="7"></td>
<td id="gr6"><input class="number" value="0" type="text" name="gr6-204" maxlength="7"></td>
<td id="gr7"><input class="number" value="0" type="text" name="gr7-204" maxlength="7"></td>
<td id="gr8"><input class="number" value="0" type="text" name="gr8-204" maxlength="7"></td>
<td id="gr9"><input class="number" value="0" type="text" name="gr9-204" maxlength="7"></td>
</tr>
<tr align=center><td><b>Тяжёлый истребитель</b></td>
<td id="gr0"><input class="number" value="0" type="text" name="gr0-205" maxlength="7"></td>
<td id="gr1"><input class="number" value="0" type="text" name="gr1-205" maxlength="7"></td>
<td id="gr2"><input class="number" value="0" type="text" name="gr2-205" maxlength="7"></td>
<td id="gr3"><input class="number" value="0" type="text" name="gr3-205" maxlength="7"></td>
<td id="gr4"><input class="number" value="0" type="text" name="gr4-205" maxlength="7"></td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-205" maxlength="7"></td>
<td id="gr6"><input class="number" value="0" type="text" name="gr6-205" maxlength="7"></td>
<td id="gr7"><input class="number" value="0" type="text" name="gr7-205" maxlength="7"></td>
<td id="gr8"><input class="number" value="0" type="text" name="gr8-205" maxlength="7"></td>
<td id="gr9"><input class="number" value="0" type="text" name="gr9-205" maxlength="7"></td>
</tr>
<tr align=center><td><b>Крейсер</b></td>
<td id="gr0"><input class="number" value="0" type="text" name="gr0-206" maxlength="7"></td>
<td id="gr1"><input class="number" value="0" type="text" name="gr1-206" maxlength="7"></td>
<td id="gr2"><input class="number" value="0" type="text" name="gr2-206" maxlength="7"></td>
<td id="gr3"><input class="number" value="0" type="text" name="gr3-206" maxlength="7"></td>
<td id="gr4"><input class="number" value="0" type="text" name="gr4-206" maxlength="7"></td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-206" maxlength="7"></td>
<td id="gr6"><input class="number" value="0" type="text" name="gr6-206" maxlength="7"></td>
<td id="gr7"><input class="number" value="0" type="text" name="gr7-206" maxlength="7"></td>
<td id="gr8"><input class="number" value="0" type="text" name="gr8-206" maxlength="7"></td>
<td id="gr9"><input class="number" value="0" type="text" name="gr9-206" maxlength="7"></td>
</tr>
<tr align=center><td><b>Линкор</b></td>
<td id="gr0"><input class="number" value="0" type="text" name="gr0-207" maxlength="7"></td>
<td id="gr1"><input class="number" value="0" type="text" name="gr1-207" maxlength="7"></td>
<td id="gr2"><input class="number" value="0" type="text" name="gr2-207" maxlength="7"></td>
<td id="gr3"><input class="number" value="0" type="text" name="gr3-207" maxlength="7"></td>
<td id="gr4"><input class="number" value="0" type="text" name="gr4-207" maxlength="7"></td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-207" maxlength="7"></td>
<td id="gr6"><input class="number" value="0" type="text" name="gr6-207" maxlength="7"></td>
<td id="gr7"><input class="number" value="0" type="text" name="gr7-207" maxlength="7"></td>
<td id="gr8"><input class="number" value="0" type="text" name="gr8-207" maxlength="7"></td>
<td id="gr9"><input class="number" value="0" type="text" name="gr9-207" maxlength="7"></td>
</tr>
<tr align=center><td><b>Колонизатор</b></td>
<td id="gr0"><input class="number" value="0" type="text" name="gr0-208" maxlength="7"></td>
<td id="gr1"><input class="number" value="0" type="text" name="gr1-208" maxlength="7"></td>
<td id="gr2"><input class="number" value="0" type="text" name="gr2-208" maxlength="7"></td>
<td id="gr3"><input class="number" value="0" type="text" name="gr3-208" maxlength="7"></td>
<td id="gr4"><input class="number" value="0" type="text" name="gr4-208" maxlength="7"></td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-208" maxlength="7"></td>
<td id="gr6"><input class="number" value="0" type="text" name="gr6-208" maxlength="7"></td>
<td id="gr7"><input class="number" value="0" type="text" name="gr7-208" maxlength="7"></td>
<td id="gr8"><input class="number" value="0" type="text" name="gr8-208" maxlength="7"></td>
<td id="gr9"><input class="number" value="0" type="text" name="gr9-208" maxlength="7"></td>
</tr>
<tr align=center><td><b>Переработчик</b></td>
<td id="gr0"><input class="number" value="0" type="text" name="gr0-209" maxlength="7"></td>
<td id="gr1"><input class="number" value="0" type="text" name="gr1-209" maxlength="7"></td>
<td id="gr2"><input class="number" value="0" type="text" name="gr2-209" maxlength="7"></td>
<td id="gr3"><input class="number" value="0" type="text" name="gr3-209" maxlength="7"></td>
<td id="gr4"><input class="number" value="0" type="text" name="gr4-209" maxlength="7"></td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-209" maxlength="7"></td>
<td id="gr6"><input class="number" value="0" type="text" name="gr6-209" maxlength="7"></td>
<td id="gr7"><input class="number" value="0" type="text" name="gr7-209" maxlength="7"></td>
<td id="gr8"><input class="number" value="0" type="text" name="gr8-209" maxlength="7"></td>
<td id="gr9"><input class="number" value="0" type="text" name="gr9-209" maxlength="7"></td>
</tr>
<tr align=center><td><b>Шпионский зонд</b></td>
<td id="gr0"><input class="number" value="0" type="text" name="gr0-210" maxlength="7"></td>
<td id="gr1"><input class="number" value="0" type="text" name="gr1-210" maxlength="7"></td>
<td id="gr2"><input class="number" value="0" type="text" name="gr2-210" maxlength="7"></td>
<td id="gr3"><input class="number" value="0" type="text" name="gr3-210" maxlength="7"></td>
<td id="gr4"><input class="number" value="0" type="text" name="gr4-210" maxlength="7"></td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-210" maxlength="7"></td>
<td id="gr6"><input class="number" value="0" type="text" name="gr6-210" maxlength="7"></td>
<td id="gr7"><input class="number" value="0" type="text" name="gr7-210" maxlength="7"></td>
<td id="gr8"><input class="number" value="0" type="text" name="gr8-210" maxlength="7"></td>
<td id="gr9"><input class="number" value="0" type="text" name="gr9-210" maxlength="7"></td>
</tr>
<tr align=center><td><b>Бомбардировщик</b></td>
<td id="gr0"><input class="number" value="0" type="text" name="gr0-211" maxlength="7"></td>
<td id="gr1"><input class="number" value="0" type="text" name="gr1-211" maxlength="7"></th>
<td id="gr2"><input class="number" value="0" type="text" name="gr2-211" maxlength="7"></td>
<td id="gr3"><input class="number" value="0" type="text" name="gr3-211" maxlength="7"></td>
<td id="gr4"><input class="number" value="0" type="text" name="gr4-211" maxlength="7"></td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-211" maxlength="7"></td>
<td id="gr6"><input class="number" value="0" type="text" name="gr6-211" maxlength="7"></td>
<td id="gr7"><input class="number" value="0" type="text" name="gr7-211" maxlength="7"></td>
<td id="gr8"><input class="number" value="0" type="text" name="gr8-211" maxlength="7"></td>
<td id="gr9"><input class="number" value="0" type="text" name="gr9-211" maxlength="7"></td>
</tr>
<tr align=center><td><b>Солнечный спутник</b></td>
<td id="gr0">-</th>
<td id="gr1">-</td>
<td id="gr2">-</td>
<td id="gr3">-</td>
<td id="gr4">-</td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-212" maxlength="7"></td>
<td id="gr6">-</td>
<td id="gr7">-</td>
<td id="gr8">-</td>
<td id="gr9">-</td>
</tr>
<tr align=center><td><b>Уничтожитель</b></td>
<td id="gr0"><input class="number" value="0" type="text" name="gr0-213" maxlength="7"></td>
<td id="gr1"><input class="number" value="0" type="text" name="gr1-213" maxlength="7"></td>
<td id="gr2"><input class="number" value="0" type="text" name="gr2-213" maxlength="7"></td>
<td id="gr3"><input class="number" value="0" type="text" name="gr3-213" maxlength="7"></td>
<td id="gr4"><input class="number" value="0" type="text" name="gr4-213" maxlength="7"></td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-213" maxlength="7"></td>
<td id="gr6"><input class="number" value="0" type="text" name="gr6-213" maxlength="7"></td>
<td id="gr7"><input class="number" value="0" type="text" name="gr7-213" maxlength="7"></td>
<td id="gr8"><input class="number" value="0" type="text" name="gr8-213" maxlength="7"></td>
<td id="gr9"><input class="number" value="0" type="text" name="gr9-213" maxlength="7"></td>
</tr>
<tr align=center><td><b>Звезда смерти</b></td>
<td id="gr0"><input class="number" value="0" type="text" name="gr0-214" maxlength="7"></td>
<td id="gr1"><input class="number" value="0" type="text" name="gr1-214" maxlength="7"></td>
<td id="gr2"><input class="number" value="0" type="text" name="gr2-214" maxlength="7"></td>
<td id="gr3"><input class="number" value="0" type="text" name="gr3-214" maxlength="7"></td>
<td id="gr4"><input class="number" value="0" type="text" name="gr4-214" maxlength="7"></td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-214" maxlength="7"></td>
<td id="gr6"><input class="number" value="0" type="text" name="gr6-214" maxlength="7"></td>
<td id="gr7"><input class="number" value="0" type="text" name="gr7-214" maxlength="7"></td>
<td id="gr8"><input class="number" value="0" type="text" name="gr8-214" maxlength="7"></td>
<td id="gr9"><input class="number" value="0" type="text" name="gr9-214" maxlength="7"></td>
</tr>
<tr align=center><td><b>Линейный крейсер</b></td>
<td id="gr0"><input class="number" value="0" type="text" name="gr0-215" maxlength="7"></td>
<td id="gr1"><input class="number" value="0" type="text" name="gr1-215" maxlength="7"></td>
<td id="gr2"><input class="number" value="0" type="text" name="gr2-215" maxlength="7"></td>
<td id="gr3"><input class="number" value="0" type="text" name="gr3-215" maxlength="7"></td>
<td id="gr4"><input class="number" value="0" type="text" name="gr4-215" maxlength="7"></td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-215" maxlength="7"></td>
<td id="gr6"><input class="number" value="0" type="text" name="gr6-215" maxlength="7"></td>
<td id="gr7"><input class="number" value="0" type="text" name="gr7-215" maxlength="7"></td>
<td id="gr8"><input class="number" value="0" type="text" name="gr8-215" maxlength="7"></td>
<td id="gr9"><input class="number" value="0" type="text" name="gr9-215" maxlength="7"></td>
</tr>
<tr>
<td colspan="12" class="spezial" id="def_td"><b>Защита</b></td>
</tr>
<tr align=center><td><b>Ракетная установка</b></td>
<td id="gr0">-</td>
<td id="gr1">-</td>
<td id="gr2">-</td>
<td id="gr3">-</td>
<td id="gr4">-</td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-401" maxlength="7"></td>
<td id="gr6">-</td>
<td id="gr7">-</td>
<td id="gr8">-</td>
<td id="gr9">-</td>
</tr>
<tr align=center><td><b>Лёгкий лазер</b></td>
<td id="gr0">-</td>
<td id="gr1">-</td>
<td id="gr2">-</td>
<td id="gr3">-</td>
<td id="gr4">-</td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-402" maxlength="7"></td>
<td id="gr6">-</td>
<td id="gr7">-</td>
<td id="gr8">-</td>
<td id="gr9">-</td>
</tr>
<tr align=center><td><b>Тяжёлый лазер</b></td>
<td id="gr0">-</td>
<td id="gr1">-</td>
<td id="gr2">-</td>
<td id="gr3">-</td>
<td id="gr4">-</td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-403" maxlength="7"></td>
<td id="gr6">-</td>
<td id="gr7">-</td>
<td id="gr8">-</td>
<td id="gr9">-</td>
</tr>
<tr align=center><td><b>Пушка Гаусса</b></td>
<td id="gr0">-</td>
<td id="gr1">-</td>
<td id="gr2">-</td>
<td id="gr3">-</td>
<td id="gr4">-</td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-404" maxlength="7"></td>
<td id="gr6">-</td>
<td id="gr7">-</td>
<td id="gr8">-</td>
<td id="gr9">-</td>
</tr><tr align=center><td><b>Ионное орудие</b></td>
<td id="gr0">-</td>
<td id="gr1">-</td>
<td id="gr2">-</td>
<td id="gr3">-</td>
<td id="gr4">-</td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-405" maxlength="7"></td>
<td id="gr6">-</td>
<td id="gr7">-</td>
<td id="gr8">-</td>
<td id="gr9">-</td>
</tr>
<tr align=center><td><b>Плазменное орудие</b></td>
<td id="gr0">-</td>
<td id="gr1">-</td>
<td id="gr2">-</td>
<td id="gr3">-</td>
<td id="gr4">-</td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-406" maxlength="7"></td>
<td id="gr6">-</td>
<td id="gr7">-</td>
<td id="gr8">-</td>
<td id="gr9">-</td>
</tr>
<tr align=center><td><b>Малый щитовой купол</b></td>
<td id="gr0">-</th>
<td id="gr1">-</td>
<td id="gr2">-</td>
<td id="gr3">-</td>
<td id="gr4">-</td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-407" maxlength="7"></td>
<td id="gr6">-</td>
<td id="gr7">-</td>
<td id="gr8">-</td>
<td id="gr9">-</td>
</tr>
<tr align=center><td><b>Большой щитовой купол</b></td>
<td id="gr0">-</td>
<td id="gr1">-</td>
<td id="gr2">-</td>
<td id="gr3">-</td>
<td id="gr4">-</td>
<td id="gr5"><input class="number" value="0" type="text" name="gr5-408" maxlength="7"></td>
<td id="gr6">-</td>
<td id="gr7">-</td>
<td id="gr8">-</td>
<td id="gr9">-</td>
</tr>
  <tr align="center">
    <td>&nbsp;</td>
    <td id='gr0'><a href='#' onClick='gclear("0");'>Очистить</a></td>
    <td id='gr1'><a href='#' onClick='gclear("1");'>Очистить</a></td>
    <td id='gr2'><a href='#' onClick='gclear("2");'>Очистить</a></td>
    <td id='gr3'><a href='#' onClick='gclear("3");'>Очистить</a></td>
    <td id='gr4'><a href='#' onClick='gclear("4");'>Очистить</a></td>
    <td id='gr5'><a href='#' onClick='gclear("5");'>Очистить</a></td>
    <td id='gr6'><a href='#' onClick='gclear("6");'>Очистить</a></td>
    <td id='gr7'><a href='#' onClick='gclear("7");'>Очистить</a></td>
    <td id='gr8'><a href='#' onClick='gclear("8");'>Очистить</a></td>
    <td id='gr9'><a href='#' onClick='gclear("9");'>Очистить</a></td>
  </tr>
    <tr> 
      <td colspan="12" align="center">
              <input name="SendBtn" type="submit" id="SendBtn" value="Расчитать!" onclick="opt()">
    </tr>
</tbody>
</table>
</form>
<script>vis_cols("TD","gr",0,1);vis_cols("TD","gr",5,1);vis_row("TR","ts");vis_row("TR","sp");vis_row("TR","gb");vis_row("TR","of");</script>
<center>Made by AlexPro for <a href="http://xnova.su/" target="_blank">XNova Game</a></center>
<center><a target="_top" href="http://top.mail.ru/jump?from=1436203"><img src="http://da.ce.b5.a1.top.list.ru/counter?id=1436203;t=50" border="0" height="31" width="88" alt="@Mail.ru"/></a></center>
</body>
</html>