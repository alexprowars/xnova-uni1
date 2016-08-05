<?

die();

function bcmul ($a1, $a2, $a3 = 0) {

	$r = round($a1*$a2, $a3);

	return $r;
}

function rnd1() {
	$dig = mt_rand(0,36);
	
	return $dig;
}

function rnd2($cell,$dig){

	$masvet0=array(1,2,27,51,76,100,125);
	$masvet1=array(76,77,78,100,101,102,124,125,126,127,149,152,154,156);
	$masvet2=array(27,28,29,51,52,53,75,76,77,78,125,126,127,149,152,153,155);
	$masvet3=array(2,3,4,26,27,28,29,125,126,127,149,152,154,156);
	$masvet4=array(78,79,80,102,103,104,124,127,128,129,149,152,153,155);
	$masvet5=array(29,30,31,53,54,55,75,78,79,80,127,128,129,149,152,154,156);
	$masvet6=array(4,5,6,26,29,30,31,127,128,129,149,152,153,155);
	$masvet7=array(80,81,82,104,105,106,124,129,130,131,149,152,154,156);
	$masvet8=array(31,32,33,55,56,57,75,80,81,82,129,130,131,149,152,153,155);
	$masvet9=array(7,8,26,31,32,33,129,130,131,149,152,154,156);
	$masvet10=array(82,83,84,106,107,108,124,131,132,133,149,152,153,155);
	$masvet11=array(33,34,35,57,58,59,75,82,83,84,131,132,133,149,152,155,156);
	$masvet12=array(8,9,10,26,33,34,35,131,132,133,149,152,153,154);
	$masvet13=array(84,85,86,108,109,110,124,133,134,135,150,152,155,156);
	$masvet14=array(35,36,37,59,60,61,75,84,85,86,133,134,135,150,152,153,154);
	$masvet15=array(10,11,12,26,35,36,37,133,134,135,150,152,155,156);
	$masvet16=array(86,87,88,110,111,112,124,135,136,137,150,152,153,154);
	$masvet17=array(37,38,39,61,62,63,75,86,87,88,135,136,137,150,152,155,156);
	$masvet18=array(12,13,14,26,37,38,39,135,136,137,150,152,153,154);
	$masvet19=array(88,89,90,112,113,114,124,137,138,139,150,154,156,157);
	$masvet20=array(39,40,41,63,64,65,75,88,89,90,137,138,139,150,153,155,157);
	$masvet21=array(14,15,16,26,39,40,41,137,138,139,150,154,156,157);
	$masvet22=array(90,91,92,114,115,116,124,139,140,141,150,153,155,157);
	$masvet23=array(41,42,43,65,66,67,75,90,91,92,139,140,141,150,154,156,157);
	$masvet24=array(16,17,18,26,41,42,43,139,140,141,150,153,155,157);
	$masvet25=array(92,93,94,116,117,118,124,141,142,143,151,154,156,157);
	$masvet26=array(43,44,45,67,68,69,75,92,93,94,141,142,143,151,153,155,157);
	$masvet27=array(18,19,20,26,43,44,45,141,142,143,151,154,156,157);
	$masvet28=array(94,95,96,118,119,120,124,143,144,145,151,153,155,157);
	$masvet29=array(45,46,47,69,70,71,75,94,95,96,143,144,145,151,155,156,157);
	$masvet30=array(20,21,22,26,45,46,47,143,144,145,151,153,154,157);
	$masvet31=array(96,97,98,120,121,122,124,145,146,147,151,155,156,157);
	$masvet32=array(47,48,49,71,72,73,75,96,97,98,145,146,147,151,153,154,157);
	$masvet33=array(22,23,24,26,47,48,49,145,146,147,151,155,156,157);
	$masvet34=array(98,99,122,123,124,147,148,151,153,154,157);
	$masvet35=array(49,50,73,74,75,98,99,147,148,151,155,156,157);
	$masvet36=array(24,25,26,49,50,147,148,151,153,154,157);

	$mas = ${"masvet".$dig};
	$aa=in_array($cell, $mas);
	return $aa;
}

if (isset($_GET['enter'])) {

	$l = $user['id'];
	$ll = $user['username'];
	$lll = $user['credits'];
	
	$s 		= intval($_GET['s']);
	$stav 	= intval($_GET['stav']);
	$cell 	= intval($_GET['cell']);
	$mon 	= intval($_GET['mon']);
	$dig 	= intval($_GET['dig']);
	$bet2 	= intval($_GET['bet2']);

	if ($stav > 5 || $stav < 1) 
		$stav = 2;
		
	if ($cell > 157)
		$cell = 0;

	if ($bet2 != 0.2 AND $bet2 != 1 AND $bet2 != 5 AND $bet2 != 10 AND $bet2 != 50)
		$bet2=0.2;
		
	if ($stav == 1)
		$bet2=0.2;
	elseif ($stav == 2)
		$bet2=1;
	elseif ($stav == 3)
		$bet2=5;
	elseif ($stav == 4)
		$bet2=10;
	elseif ($stav == 5)
		$bet2=50;
	else
		$bet2=0.2;
		
	$bet2 *= 10;

	if ($s != 1){
		$mon="0";
		$win="0.00";
		echo "<DIV id=credit>$lll</DIV><DIV id=bet>$stav</DIV><DIV id=dig>$dig</DIV><DIV id=win>$win</DIV><DIV id=mon>$mon</DIV>";
	}



	if ($s == 1 && $lll >= $bet2 && $cell > 0){
		$mon="1";
		$win="0.00";
		
		$loh = 0;

		$dig 	= rnd1();
		$aa 	= rnd2($cell, $dig);
		
		if ($aa == true && $cell >= 152 && rand(1, 2) == 1) 
			$loh = 1;
		
		if($aa == true && $loh == 1){
			$dig 	= rnd1(); 
			$aa		= rnd2($cell,$dig); 
		}

		if($aa == true){
			if ($cell==157){ $win=bcmul($bet2, 2, 2); }
			if ($cell==156){ $win=bcmul($bet2, 2, 2); }
			if ($cell==155){ $win=bcmul($bet2, 2, 2); }
			if ($cell==154){ $win=bcmul($bet2, 2, 2); }
			if ($cell==153){ $win=bcmul($bet2, 2, 2); }
			if ($cell==152){ $win=bcmul($bet2, 2, 2); }
			if ($cell==151){ $win=bcmul($bet2, 3, 2); }
			if ($cell==150){ $win=bcmul($bet2, 3, 2); }
			if ($cell==149){ $win=bcmul($bet2, 3, 2); }
			if ($cell==148){ $win=bcmul($bet2, 12, 2); }
			if ($cell==147){ $win=bcmul($bet2, 6, 2); }
			if ($cell==146){ $win=bcmul($bet2, 12, 2); }
			if ($cell==145){ $win=bcmul($bet2, 6, 2); }
			if ($cell==144){ $win=bcmul($bet2, 12, 2); }
			if ($cell==143){ $win=bcmul($bet2, 6, 2); }
			if ($cell==142){ $win=bcmul($bet2, 12, 2); }
			if ($cell==141){ $win=bcmul($bet2, 6, 2); }
			if ($cell==140){ $win=bcmul($bet2, 12, 2); }
			if ($cell==139){ $win=bcmul($bet2, 6, 2); }
			if ($cell==138){ $win=bcmul($bet2, 12, 2); }
			if ($cell==137){ $win=bcmul($bet2, 6, 2); }
			if ($cell==136){ $win=bcmul($bet2, 12, 2); }
			if ($cell==135){ $win=bcmul($bet2, 6, 2); }
			if ($cell==134){ $win=bcmul($bet2, 12, 2); }
			if ($cell==133){ $win=bcmul($bet2, 6, 2); }
			if ($cell==132){ $win=bcmul($bet2, 12, 2); }
			if ($cell==131){ $win=bcmul($bet2, 6, 2); }
			if ($cell==130){ $win=bcmul($bet2, 12, 2); }
			if ($cell==129){ $win=bcmul($bet2, 6, 2); }
			if ($cell==128){ $win=bcmul($bet2, 12, 2); }
			if ($cell==127){ $win=bcmul($bet2, 6, 2); }
			if ($cell==126){ $win=bcmul($bet2, 12, 2); }
			if ($cell==125){ $win=bcmul($bet2, 9, 2); }
			if ($cell==124){ $win=bcmul($bet2, 3, 2); }
			if ($cell==123){ $win=bcmul($bet2, 36, 2); }
			if ($cell==122){ $win=bcmul($bet2, 18, 2); }
			if ($cell==121){ $win=bcmul($bet2, 36, 2); }
			if ($cell==120){ $win=bcmul($bet2, 18, 2); }
			if ($cell==119){ $win=bcmul($bet2, 36, 2); }
			if ($cell==118){ $win=bcmul($bet2, 18, 2); }
			if ($cell==117){ $win=bcmul($bet2, 36, 2); }
			if ($cell==116){ $win=bcmul($bet2, 18, 2); }
			if ($cell==115){ $win=bcmul($bet2, 36, 2); }
			if ($cell==114){ $win=bcmul($bet2, 18, 2); }
			if ($cell==113){ $win=bcmul($bet2, 36, 2); }
			if ($cell==112){ $win=bcmul($bet2, 18, 2); }
			if ($cell==111){ $win=bcmul($bet2, 36, 2); }
			if ($cell==110){ $win=bcmul($bet2, 18, 2); }
			if ($cell==109){ $win=bcmul($bet2, 36, 2); }
			if ($cell==108){ $win=bcmul($bet2, 18, 2); }
			if ($cell==107){ $win=bcmul($bet2, 36, 2); }
			if ($cell==106){ $win=bcmul($bet2, 18, 2); }
			if ($cell==105){ $win=bcmul($bet2, 36, 2); }
			if ($cell==104){ $win=bcmul($bet2, 18, 2); }
			if ($cell==103){ $win=bcmul($bet2, 36, 2); }
			if ($cell==102){ $win=bcmul($bet2, 18, 2); }
			if ($cell==101){ $win=bcmul($bet2, 36, 2); }
			if ($cell==100){ $win=bcmul($bet2, 18, 2); }
			if ($cell==99){ $win=bcmul($bet2, 18, 2); }
			if ($cell==98){ $win=bcmul($bet2, 9, 2); }
			if ($cell==97){ $win=bcmul($bet2, 18, 2); }
			if ($cell==96){ $win=bcmul($bet2, 9, 2); }
			if ($cell==95){ $win=bcmul($bet2, 18, 2); }
			if ($cell==94){ $win=bcmul($bet2, 9, 2); }
			if ($cell==93){ $win=bcmul($bet2, 18, 2); }
			if ($cell==92){ $win=bcmul($bet2, 9, 2); }
			if ($cell==91){ $win=bcmul($bet2, 18, 2); }
			if ($cell==90){ $win=bcmul($bet2, 9, 2); }
			if ($cell==89){ $win=bcmul($bet2, 18, 2); }
			if ($cell==88){ $win=bcmul($bet2, 9, 2); }
			if ($cell==87){ $win=bcmul($bet2, 18, 2); }
			if ($cell==86){ $win=bcmul($bet2, 9, 2); }
			if ($cell==85){ $win=bcmul($bet2, 18, 2); }
			if ($cell==84){ $win=bcmul($bet2, 9, 2); }
			if ($cell==83){ $win=bcmul($bet2, 18, 2); }
			if ($cell==82){ $win=bcmul($bet2, 9, 2); }
			if ($cell==81){ $win=bcmul($bet2, 18, 2); }
			if ($cell==80){ $win=bcmul($bet2, 9, 2); }
			if ($cell==79){ $win=bcmul($bet2, 18, 2); }
			if ($cell==78){ $win=bcmul($bet2, 9, 2); }
			if ($cell==77){ $win=bcmul($bet2, 18, 2); }
			if ($cell==76){ $win=bcmul($bet2, 12, 2); }
			if ($cell==75){ $win=bcmul($bet2, 3, 2); }
			if ($cell==74){ $win=bcmul($bet2, 36, 2); }
			if ($cell==73){ $win=bcmul($bet2, 18, 2); }
			if ($cell==72){ $win=bcmul($bet2, 36, 2); }
			if ($cell==71){ $win=bcmul($bet2, 18, 2); }
			if ($cell==70){ $win=bcmul($bet2, 36, 2); }
			if ($cell==69){ $win=bcmul($bet2, 18, 2); }
			if ($cell==68){ $win=bcmul($bet2, 36, 2); }
			if ($cell==67){ $win=bcmul($bet2, 18, 2); }
			if ($cell==66){ $win=bcmul($bet2, 36, 2); }
			if ($cell==65){ $win=bcmul($bet2, 18, 2); }
			if ($cell==64){ $win=bcmul($bet2, 36, 2); }
			if ($cell==63){ $win=bcmul($bet2, 18, 2); }
			if ($cell==62){ $win=bcmul($bet2, 36, 2); }
			if ($cell==61){ $win=bcmul($bet2, 18, 2); }
			if ($cell==60){ $win=bcmul($bet2, 36, 2); }
			if ($cell==59){ $win=bcmul($bet2, 18, 2); }
			if ($cell==58){ $win=bcmul($bet2, 36, 2); }
			if ($cell==57){ $win=bcmul($bet2, 18, 2); }
			if ($cell==56){ $win=bcmul($bet2, 36, 2); }
			if ($cell==55){ $win=bcmul($bet2, 18, 2); }
			if ($cell==54){ $win=bcmul($bet2, 36, 2); }
			if ($cell==53){ $win=bcmul($bet2, 18, 2); }
			if ($cell==52){ $win=bcmul($bet2, 36, 2); }
			if ($cell==51){ $win=bcmul($bet2, 18, 2); }
			if ($cell==50){ $win=bcmul($bet2, 18, 2); }
			if ($cell==49){ $win=bcmul($bet2, 9, 2); }
			if ($cell==48){ $win=bcmul($bet2, 18, 2); }
			if ($cell==47){ $win=bcmul($bet2, 9, 2); }
			if ($cell==46){ $win=bcmul($bet2, 18, 2); }
			if ($cell==45){ $win=bcmul($bet2, 9, 2); }
			if ($cell==44){ $win=bcmul($bet2, 18, 2); }
			if ($cell==43){ $win=bcmul($bet2, 9, 2); }
			if ($cell==42){ $win=bcmul($bet2, 18, 2); }
			if ($cell==41){ $win=bcmul($bet2, 9, 2); }
			if ($cell==40){ $win=bcmul($bet2, 18, 2); }
			if ($cell==39){ $win=bcmul($bet2, 9, 2); }
			if ($cell==38){ $win=bcmul($bet2, 18, 2); }
			if ($cell==37){ $win=bcmul($bet2, 9, 2); }
			if ($cell==36){ $win=bcmul($bet2, 18, 2); }
			if ($cell==35){ $win=bcmul($bet2, 9, 2); }
			if ($cell==34){ $win=bcmul($bet2, 18, 2); }
			if ($cell==33){ $win=bcmul($bet2, 9, 2); }
			if ($cell==32){ $win=bcmul($bet2, 18, 2); }
			if ($cell==31){ $win=bcmul($bet2, 9, 2); }
			if ($cell==30){ $win=bcmul($bet2, 18, 2); }
			if ($cell==29){ $win=bcmul($bet2, 9, 2); }
			if ($cell==28){ $win=bcmul($bet2, 18, 2); }
			if ($cell==27){ $win=bcmul($bet2, 12, 2); }
			if ($cell==26){ $win=bcmul($bet2, 3, 2); }
			if ($cell==25){ $win=bcmul($bet2, 36, 2); }
			if ($cell==24){ $win=bcmul($bet2, 18, 2); }
			if ($cell==23){ $win=bcmul($bet2, 36, 2); }
			if ($cell==22){ $win=bcmul($bet2, 18, 2); }
			if ($cell==21){ $win=bcmul($bet2, 36, 2); }
			if ($cell==20){ $win=bcmul($bet2, 18, 2); }
			if ($cell==19){ $win=bcmul($bet2, 36, 2); }
			if ($cell==18){ $win=bcmul($bet2, 18, 2); }
			if ($cell==17){ $win=bcmul($bet2, 36, 2); }
			if ($cell==16){ $win=bcmul($bet2, 18, 2); }
			if ($cell==15){ $win=bcmul($bet2, 36, 2); }
			if ($cell==14){ $win=bcmul($bet2, 18, 2); }
			if ($cell==13){ $win=bcmul($bet2, 36, 2); }
			if ($cell==12){ $win=bcmul($bet2, 18, 2); }
			if ($cell==11){ $win=bcmul($bet2, 36, 2); }
			if ($cell==10){ $win=bcmul($bet2, 18, 2); }
			if ($cell==9){ $win=bcmul($bet2, 36, 2); }
			if ($cell==8){ $win=bcmul($bet2, 18, 2); }
			if ($cell==7){ $win=bcmul($bet2, 36, 2); }
			if ($cell==6){ $win=bcmul($bet2, 18, 2); }
			if ($cell==5){ $win=bcmul($bet2, 36, 2); }
			if ($cell==4){ $win=bcmul($bet2, 18, 2); }
			if ($cell==3){ $win=bcmul($bet2, 36, 2); }
			if ($cell==2){ $win=bcmul($bet2, 18, 2); }
			if ($cell==1){ $win=bcmul($bet2, 36, 2); }

			$chisto = $win - $bet2;
			$ostal = $lll + $chisto;
			mysql_query("UPDATE game_users SET credits = '".$ostal."' where id = '".$l."'");

			echo "<DIV id=credit>$ostal</DIV><DIV id=bet>$stav</DIV><DIV id=dig>$dig</DIV><DIV id=win>$win</DIV><DIV id=mon>$mon</DIV>";
		} else {

			$mon="1";
			$win="0.00";

			$ostal = $lll - $bet2;
			mysql_query("UPDATE game_users SET credits = '".$ostal."' where id = '".$l."'");
			mysql_query("UPDATE game_config SET config_value = config_value + '".$bet2."' where config_name = 'credits'");

			echo "<DIV id=credit>$ostal</DIV><DIV id=bet>$stav</DIV><DIV id=dig>$dig</DIV><DIV id=win>$win</DIV><DIV id=mon>$mon</DIV>";
		}
	} else {
		echo "<DIV id=credit>$lll</DIV><DIV id=bet>$stav</DIV><DIV id=dig>$dig</DIV><DIV id=win>$win</DIV><DIV id=mon>3</DIV>";
	}
	
	die();
}


	$page .= '  <link href="css/gmrl.css" rel="stylesheet" type="text/css">
				<script language="JavaScript" src="scripts/gmrl.js"></script>

				<iframe src="?set=ruletka&enter=true" type="content-primary" name="gframe" width="0" height="0" scrolling="no" frameborder="0" onLoad="start();"></iframe>

				<br><br><table width="70%" border="0" align="center" cellpadding="7" cellspacing="0">
				<tr>

				</tr>
				<tr>
				<td align="center"><table border="0" cellpadding="0" cellspacing="1" bgcolor="#4F6A4A">
				<tr>
				<td bgcolor="#163D0E"><table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td width="611" height="150" valign="top" style="background-image: url(images/gmrlbg.jpg); background-repeat: no-repeat; 	background-position: left top;"><table border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td width="359">&nbsp;</td>
				<td height="20">&nbsp;</td>
				</tr>
				<tr>
				<td>&nbsp;</td>

				<td width="100" height="100" align="center"  bgcolor="#13330C" class="rdig" id="rulet">&nbsp;</td>
				<td width="150" height="100" valign="top" align="center" class="upmenu">
				<br><br>
				<b>Баланс: <br><span id="creditsum"></span></b>&nbsp;<br>кредитов</td>


				</tr>
				</table></td>
				</tr>
				<tr>
				<td align="center"><table width="95%" border="0" cellpadding="0" cellspacing="0">
				<tr>
				<td width="44" height="44" align="right" valign="top"><img src="images/bord1.gif" width="44" height="44"></td>
				<td width="100%" height="44"><img src="images/bord2.gif" width="500" height="44"></td>
				<td width="44" height="44" valign="top"><img src="images/bord3.gif" width="44" height="44"></td>
				</tr>
				<tr>
				<td background="images/bord4.gif">&nbsp;</td>
				<td align="center" bgcolor="#285B20">

				<span class="inf" id="infotxt">&nbsp;</span>
				<br>

				<div style="position:relative; width:390px; height:215px; visibility: visible;">

				<span id="cap1" style="position:absolute; left:0px; top:0px; width:23px; height:22px; visibility: hidden;"></span>

				<img src="images/gmertbl.gif" width="390" height="215" border="0" alt="" usemap="#tbl1_flat_Map">
				<map name="tbl1_flat_Map">
				<area shape="rect" alt="Большие 19-36 (1 к 1)" coords="301,169,351,203" href="#c" onClick="cl(157)">
				<area shape="rect" alt="Нечетные (1 к 1)" coords="249,169,299,203" href="#c" onClick="cl(156)">
				<area shape="rect" alt="Черное (1 к 1)" coords="197,169,247,203" href="#c" onClick="cl(155)">
				<area shape="rect" alt="Красное (1 к 1)" coords="146,169,196,203" href="#c" onClick="cl(154)">
				<area shape="rect" alt="Четные (1 к 1)" coords="93,169,143,203" href="#c" onClick="cl(153)">
				<area shape="rect" alt="Маленькие 1-18 (1 к 1)" coords="41,169,91,203" href="#c" onClick="cl(152)">
				<area shape="rect" alt="3-я дюжина (2 к 1)" coords="249,139,351,167" href="#c" onClick="cl(151)">
				<area shape="rect" alt="2-я дюжина (2 к 1)" coords="145,139,247,167" href="#c" onClick="cl(150)">
				<area shape="rect" alt="1-я дюжина (2 к 1)" coords="41,139,143,167" href="#c" onClick="cl(149)">
				<area shape="rect" alt="Тройка: 34, 35, 36 (11 к 1)" coords="331,126,351,138" href="#c" onClick="cl(148)">
				<area shape="rect" alt="Линия: 31, 32, 33, 34, 35, 36 (1 к 5)" coords="322,126,330,138" href="#c" onClick="cl(147)">
				<area shape="rect" alt="Тройка: 31, 32, 33 (11 к 1)" coords="305,126,321,138" href="#c" onClick="cl(146)">
				<area shape="rect" alt="Линия: 28, 29, 30, 31, 32, 33 (1 к 5)" coords="296,126,304,138" href="#c" onClick="cl(145)">
				<area shape="rect" alt="Тройка: 28, 29, 30 (11 к 1)" coords="279,126,295,138" href="#c" onClick="cl(144)">
				<area shape="rect" alt="Линия: 25, 26, 27, 28, 29, 30 (1 к 5)" coords="270,126,278,138" href="#c" onClick="cl(143)">
				<area shape="rect" alt="Тройка: 25, 26, 27 (11 к 1)" coords="253,126,269,138" href="#c" onClick="cl(142)">
				<area shape="rect" alt="Линия: 22, 23, 24, 25, 26, 27 (1 к 5)" coords="244,126,252,138" href="#c" onClick="cl(141)">
				<area shape="rect" alt="Тройка: 22, 23, 24 (11 к 1)" coords="227,126,243,138" href="#c" onClick="cl(140)">
				<area shape="rect" alt="Линия: 19, 20, 21, 22, 23, 24 (1 к 5)" coords="218,126,226,138" href="#c" onClick="cl(139)">
				<area shape="rect" alt="Тройка: 19, 20, 21 (11 к 1)" coords="201,126,217,138" href="#c" onClick="cl(138)">
				<area shape="rect" alt="Линия: 16, 17, 18, 19, 20, 21 (1 к 5)" coords="192,126,200,138" href="#c" onClick="cl(137)">
				<area shape="rect" alt="Тройка: 16, 17, 18 (11 к 1)" coords="175,126,191,138" href="#c" onClick="cl(136)">
				<area shape="rect" alt="Линия: 13, 14, 15, 16, 17, 18 (1 к 5)" coords="166,126,174,138" href="#c" onClick="cl(135)">
				<area shape="rect" alt="Тройка: 13, 14, 15 (11 к 1)" coords="149,126,165,138" href="#c" onClick="cl(134)">
				<area shape="rect" alt="Линия: 10, 11, 12, 13, 14, 15 (1 к 5)" coords="140,126,148,138" href="#c" onClick="cl(133)">
				<area shape="rect" alt="Тройка: 10, 11, 12 (11 к 1)" coords="123,126,139,138" href="#c" onClick="cl(132)">
				<area shape="rect" alt="Линия: 7, 8, 9, 10, 11, 12 (1 к 5)" coords="114,126,122,138" href="#c" onClick="cl(131)">
				<area shape="rect" alt="Тройка: 7, 8, 9 (11 к 1)" coords="97,126,113,138" href="#c" onClick="cl(130)">
				<area shape="rect" alt="Линия: 4, 5, 6, 7, 8, 9 (1 к 5)" coords="88,126,96,138" href="#c" onClick="cl(129)">
				<area shape="rect" alt="Тройка: 4, 5, 6 (11 к 1)" coords="71,126,87,138" href="#c" onClick="cl(128)">
				<area shape="rect" alt="Линия: 1, 2, 3, 4, 5, 6 (1 к 5)" coords="62,126,70,138" href="#c" onClick="cl(127)">
				<area shape="rect" alt="Тройка: 1, 2, 3 (11 к 1)" coords="45,126,61,138" href="#c" onClick="cl(126)">
				<area shape="rect" alt="Четверка: 0, 1, 2, 3 (8 к 1)" coords="36,126,44,138" href="#c" onClick="cl(125)">
				<area shape="rect" alt="Колонка 1 (2 к 1)" coords="353,92,377,131" href="#c" onClick="cl(124)">
				<area shape="rect" alt="Число: 34 (35 к 1)" coords="331,98,351,125" href="#c" onClick="cl(123)">
				<area shape="rect" alt="Пара: 31, 34 (17 к 1)" coords="322,98,330,125" href="#c" onClick="cl(122)">
				<area shape="rect" alt="Число: 31 (35 к 1)" coords="305,98,321,125" href="#c" onClick="cl(121)">
				<area shape="rect" alt="Пара: 28, 31 (17 к 1)" coords="296,98,304,125" href="#c" onClick="cl(120)">
				<area shape="rect" alt="Число: 28 (35 к 1)" coords="279,98,295,125" href="#c" onClick="cl(119)">
				<area shape="rect" alt="Пара: 25, 28 (17 к 1)" coords="270,98,278,125" href="#c" onClick="cl(118)">
				<area shape="rect" alt="Число: 25 (35 к 1)" coords="253,98,269,125" href="#c" onClick="cl(117)">
				<area shape="rect" alt="Пара: 22, 25 (17 к 1)" coords="244,98,252,125" href="#c" onClick="cl(116)">
				<area shape="rect" alt="Число: 22 (35 к 1)" coords="227,98,243,125" href="#c" onClick="cl(115)">
				<area shape="rect" alt="Пара: 19, 22 (17 к 1)" coords="218,98,226,125" href="#c" onClick="cl(114)">
				<area shape="rect" alt="Число: 19 (35 к 1)" coords="201,98,217,125" href="#c" onClick="cl(113)">
				<area shape="rect" alt="Пара: 16, 19 (17 к 1)" coords="192,98,200,125" href="#c" onClick="cl(112)">
				<area shape="rect" alt="Число: 16 (35 к 1)" coords="175,98,191,125" href="#c" onClick="cl(111)">
				<area shape="rect" alt="Пара: 13, 16 (17 к 1)" coords="166,98,174,125" href="#c" onClick="cl(110)">
				<area shape="rect" alt="Число: 13 (35 к 1)" coords="149,98,165,125" href="#c" onClick="cl(109)">
				<area shape="rect" alt="Пара: 10, 13 (17 к 1)" coords="140,98,148,125" href="#c" onClick="cl(108)">
				<area shape="rect" alt="Число: 10 (35 к 1)" coords="123,98,139,125" href="#c" onClick="cl(107)">
				<area shape="rect" alt="Пара: 7, 10 (17 к 1)" coords="114,98,122,125" href="#c" onClick="cl(106)">
				<area shape="rect" alt="Число: 7 (35 к 1)" coords="97,98,113,125" href="#c" onClick="cl(105)">
				<area shape="rect" alt="Пара: 4, 7 (17 к 1)" coords="88,98,96,125" href="#c" onClick="cl(104)">
				<area shape="rect" alt="Число: 4 (35 к 1)" coords="71,98,87,125" href="#c" onClick="cl(103)">
				<area shape="rect" alt="Пара: 1, 4 (17 к 1)" coords="62,98,70,125" href="#c" onClick="cl(102)">
				<area shape="rect" alt="Число: 1 (35 к 1)" coords="45,98,61,125" href="#c" onClick="cl(101)">
				<area shape="rect" alt="Пара: 0, 1 (17 к 1)" coords="36,98,44,125" href="#c" onClick="cl(100)">
				<area shape="rect" alt="Пара: 34, 35 (17 к 1)" coords="331,85,351,97" href="#c" onClick="cl(99)">
				<area shape="rect" alt="Четверка: 31, 32, 34, 35 (8 к 1)" coords="322,85,330,97" href="#c" onClick="cl(98)">
				<area shape="rect" alt="Пара: 31, 32 (17 к 1)" coords="305,85,321,97" href="#c" onClick="cl(97)">
				<area shape="rect" alt="Четверка: 28, 29, 31, 32 (8 к 1)" coords="296,85,304,97" href="#c" onClick="cl(96)">
				<area shape="rect" alt="Пара: 28, 29 (17 к 1)" coords="279,85,295,97" href="#c" onClick="cl(95)">
				<area shape="rect" alt="Четверка: 25, 26, 28, 29 (8 к 1)" coords="270,85,278,97" href="#c" onClick="cl(94)">
				<area shape="rect" alt="Пара: 25, 26 (17 к 1)" coords="253,85,269,97" href="#c" onClick="cl(93)">
				<area shape="rect" alt="Четверка: 22, 23, 25, 26 (8 к 1)" coords="244,85,252,97" href="#c" onClick="cl(92)">
				<area shape="rect" alt="Пара: 22, 23 (17 к 1)" coords="227,85,243,97" href="#c" onClick="cl(91)">
				<area shape="rect" alt="Четверка: 19, 20, 22, 23 (8 к 1)" coords="218,85,226,97" href="#c" onClick="cl(90)">
				<area shape="rect" alt="Пара: 19, 20 (17 к 1)" coords="201,85,217,97" href="#c" onClick="cl(89)">
				<area shape="rect" alt="Четверка: 16, 17, 19, 20 (8 к 1)" coords="192,85,200,97" href="#c" onClick="cl(88)">
				<area shape="rect" alt="Пара: 16, 17 (17 к 1)" coords="175,85,191,97" href="#c" onClick="cl(87)">
				<area shape="rect" alt="Четверка: 13, 14, 16, 17 (8 к 1)" coords="166,85,174,97" href="#c" onClick="cl(86)">
				<area shape="rect" alt="Пара: 13, 14 (17 к 1)" coords="149,85,165,97" href="#c" onClick="cl(85)">
				<area shape="rect" alt="Четверка: 10, 11, 13, 14 (8 к 1)" coords="140,85,148,97" href="#c" onClick="cl(84)">
				<area shape="rect" alt="Пара: 10, 11 (17 к 1)" coords="123,85,139,97" href="#c" onClick="cl(83)">
				<area shape="rect" alt="Четверка: 7, 8, 10, 11 (8 к 1)" coords="114,85,122,97" href="#c" onClick="cl(82)">
				<area shape="rect" alt="Пара: 7, 8 (17 к 1)" coords="97,85,113,97" href="#c" onClick="cl(81)">
				<area shape="rect" alt="Четверка: 4, 5, 7, 8 (8 к 1)" coords="88,85,96,97" href="#c" onClick="cl(80)">
				<area shape="rect" alt="Пара: 4, 5 (17 к 1)" coords="71,85,87,97" href="#c" onClick="cl(79)">
				<area shape="rect" alt="Четверка: 1, 2, 4, 5 (8 к 1)" coords="62,85,70,97" href="#c" onClick="cl(78)">
				<area shape="rect" alt="Пара: 1, 2 (17 к 1)" coords="45,85,61,97" href="#c" onClick="cl(77)">
				<area shape="rect" alt="Тройка: 0, 1, 2 (11 к 1)" coords="36,85,44,97" href="#c" onClick="cl(76)">
				<area shape="rect" alt="Колонка 2 (2 к 1)" coords="353,51,377,90" href="#c" onClick="cl(75)">
				<area shape="rect" alt="Число: 35 (35 к 1)" coords="331,57,351,84" href="#c" onClick="cl(74)">
				<area shape="rect" alt="Пара: 32, 35 (17 к 1)" coords="322,57,330,84" href="#c" onClick="cl(73)">
				<area shape="rect" alt="Число: 32 (35 к 1)" coords="305,57,321,84" href="#c" onClick="cl(72)">
				<area shape="rect" alt="Пара: 29, 32 (17 к 1)" coords="296,57,304,84" href="#c" onClick="cl(71)">
				<area shape="rect" alt="Число: 29 (35 к 1)" coords="279,57,295,84" href="#c" onClick="cl(70)">
				<area shape="rect" alt="Пара: 26, 29 (17 к 1)" coords="270,57,278,84" href="#c" onClick="cl(69)">
				<area shape="rect" alt="Число: 26 (35 к 1)" coords="253,57,269,84" href="#c" onClick="cl(68)">
				<area shape="rect" alt="Пара: 23, 26 (17 к 1)" coords="244,57,252,84" href="#c" onClick="cl(67)">
				<area shape="rect" alt="Число: 23 (35 к 1)" coords="227,57,243,84" href="#c" onClick="cl(66)">
				<area shape="rect" alt="Пара: 20, 23 (17 к 1)" coords="218,57,226,84" href="#c" onClick="cl(65)">
				<area shape="rect" alt="Число: 20 (35 к 1)" coords="201,57,217,84" href="#c" onClick="cl(64)">
				<area shape="rect" alt="Пара: 17, 20 (17 к 1)" coords="192,57,200,84" href="#c" onClick="cl(63)">
				<area shape="rect" alt="Число: 17 (35 к 1)" coords="175,57,191,84" href="#c" onClick="cl(62)">
				<area shape="rect" alt="Пара: 14, 17 (17 к 1)" coords="166,57,174,84" href="#c" onClick="cl(61)">
				<area shape="rect" alt="Число: 14 (35 к 1)" coords="149,57,165,84" href="#c" onClick="cl(60)">
				<area shape="rect" alt="Пара: 11, 14 (17 к 1)" coords="140,57,148,84" href="#c" onClick="cl(59)">
				<area shape="rect" alt="Число: 11 (35 к 1)" coords="123,57,139,84" href="#c" onClick="cl(58)">
				<area shape="rect" alt="Пара: 8, 11 (17 к 1)" coords="114,57,122,84" href="#c" onClick="cl(57)">
				<area shape="rect" alt="Число: 8 (35 к 1)" coords="97,57,113,84" href="#c" onClick="cl(56)">
				<area shape="rect" alt="Пара: 5, 8 (17 к 1)" coords="88,57,96,84" href="#c" onClick="cl(55)">
				<area shape="rect" alt="Число: 5 (35 к 1)" coords="71,57,87,84" href="#c" onClick="cl(54)">
				<area shape="rect" alt="Пара: 2, 5 (17 к 1)" coords="62,57,70,84" href="#c" onClick="cl(53)">
				<area shape="rect" alt="Число: 2 (35 к 1)" coords="45,57,61,84" href="#c" onClick="cl(52)">
				<area shape="rect" alt="Пара: 0, 2 (17 к 1)" coords="36,57,44,84" href="#c" onClick="cl(51)">
				<area shape="rect" alt="Пара: 35, 36 (17 к 1)" coords="331,44,351,56" href="#c" onClick="cl(50)">
				<area shape="rect" alt="Четверка: 32, 33, 35, 36 (8 к 1)" coords="322,44,330,56" href="#c" onClick="cl(49)">
				<area shape="rect" alt="Пара: 32, 33 (17 к 1)" coords="305,44,321,56" href="#c" onClick="cl(48)">
				<area shape="rect" alt="Четверка: 29, 30, 32, 33 (8 к 1)" coords="296,44,304,56" href="#c" onClick="cl(47)">
				<area shape="rect" alt="Пара: 29, 30 (17 к 1)" coords="279,44,295,56" href="#c" onClick="cl(46)">
				<area shape="rect" alt="Четверка: 26, 27, 29, 30 (8 к 1)" coords="270,44,278,56" href="#c" onClick="cl(45)">
				<area shape="rect" alt="Пара: 26, 27 (17 к 1)" coords="253,44,269,56" href="#c" onClick="cl(44)">
				<area shape="rect" alt="Четверка: 23, 24, 26, 27 (8 к 1)" coords="244,44,252,56" href="#c" onClick="cl(43)">
				<area shape="rect" alt="Пара: 23, 24 (17 к 1)" coords="227,44,243,56" href="#c" onClick="cl(42)">
				<area shape="rect" alt="Четверка: 20, 21, 23, 24 (8 к 1)" coords="218,44,226,56" href="#c" onClick="cl(41)">
				<area shape="rect" alt="Пара: 20, 21 (17 к 1)" coords="201,44,217,56" href="#c" onClick="cl(40)">
				<area shape="rect" alt="Четверка: 17, 18, 20, 21 (8 к 1)" coords="192,44,200,56" href="#c" onClick="cl(39)">
				<area shape="rect" alt="Пара: 17, 18 (17 к 1)" coords="175,44,191,56" href="#c" onClick="cl(38)">
				<area shape="rect" alt="Четверка: 14, 15, 17, 18 (8 к 1)" coords="166,44,174,56" href="#c" onClick="cl(37)">
				<area shape="rect" alt="Пара: 14, 15 (17 к 1)" coords="149,44,165,56" href="#c" onClick="cl(36)">
				<area shape="rect" alt="Четверка: 11, 12, 14, 15 (8 к 1)" coords="140,44,148,56" href="#c" onClick="cl(35)">
				<area shape="rect" alt="Пара: 11, 12 (17 к 1)" coords="123,44,139,56" href="#c" onClick="cl(34)">
				<area shape="rect" alt="Четверка: 8, 9, 11, 12 (8 к 1)" coords="114,44,122,56" href="#c" onClick="cl(33)">
				<area shape="rect" alt="Пара: 8, 9 (17 к 1)" coords="97,44,113,56" href="#c" onClick="cl(32)">
				<area shape="rect" alt="Четверка: 5, 6, 8, 9 (8 к 1)" coords="88,44,96,56" href="#c" onClick="cl(31)">
				<area shape="rect" alt="Пара: 5, 6 (17 к 1)" coords="71,44,87,56" href="#c" onClick="cl(30)">
				<area shape="rect" alt="Четверка: 2, 3, 5, 6 (8 к 1)" coords="62,44,70,56" href="#c" onClick="cl(29)">
				<area shape="rect" alt="Пара: 2, 3 (17 к 1)" coords="45,44,61,56" href="#c" onClick="cl(28)">
				<area shape="rect" alt="Тройка: 0, 2, 3 (11 к 1)" coords="36,44,44,56" href="#c" onClick="cl(27)">
				<area shape="rect" alt="Колонка 3 (2 к 1)" coords="353,10,377,49" href="#c" onClick="cl(26)">
				<area shape="rect" alt="Число: 36 (35 к 1)" coords="331,10,351,43" href="#c" onClick="cl(25)">
				<area shape="rect" alt="Пара: 33, 36 (17 к 1)" coords="322,10,330,43" href="#c" onClick="cl(24)">
				<area shape="rect" alt="Число: 33 (35 к 1)" coords="305,10,321,43" href="#c" onClick="cl(23)">
				<area shape="rect" alt="Пара: 30, 33 (17 к 1)" coords="296,10,304,43" href="#c" onClick="cl(22)">
				<area shape="rect" alt="Число: 30 (35 к 1)" coords="279,10,295,43" href="#c" onClick="cl(21)">
				<area shape="rect" alt="Пара: 27, 30 (17 к 1)" coords="270,10,278,43" href="#c" onClick="cl(20)">
				<area shape="rect" alt="Число: 27 (35 к 1)" coords="253,10,269,43" href="#c" onClick="cl(19)">
				<area shape="rect" alt="Пара: 24, 27 (17 к 1)" coords="244,10,252,43" href="#c" onClick="cl(18)">
				<area shape="rect" alt="Число: 24 (35 к 1)" coords="227,10,243,43" href="#c" onClick="cl(17)">
				<area shape="rect" alt="Пара: 21, 24 (17 к 1)" coords="218,10,226,43" href="#c" onClick="cl(16)">
				<area shape="rect" alt="Число: 21 (35 к 1)" coords="201,10,217,43" href="#c" onClick="cl(15)">
				<area shape="rect" alt="Пара: 18, 21 (17 к 1)" coords="192,10,200,43" href="#c" onClick="cl(14)">
				<area shape="rect" alt="Число: 18 (35 к 1)" coords="175,10,191,43" href="#c" onClick="cl(13)">
				<area shape="rect" alt="Пара: 15, 18 (17 к 1)" coords="166,10,174,43" href="#c" onClick="cl(12)">
				<area shape="rect" alt="Число: 15 (35 к 1)" coords="149,10,165,43" href="#c" onClick="cl(11)">
				<area shape="rect" alt="Пара: 12, 15 (17 к 1)" coords="140,10,148,43" href="#c" onClick="cl(10)">
				<area shape="rect" alt="Число: 12 (35 к 1)" coords="123,10,139,43" href="#c" onClick="cl(9)">
				<area shape="rect" alt="Пара: 9, 12 (17 к 1)" coords="114,10,122,43" href="#c" onClick="cl(8)">
				<area shape="rect" alt="Число: 9 (35 к 1)" coords="97,10,113,43" href="#c" onClick="cl(7)">
				<area shape="rect" alt="Пара: 6, 9 (17 к 1)" coords="88,10,96,43" href="#c" onClick="cl(6)">
				<area shape="rect" alt="Число: 6 (35 к 1)" coords="71,10,87,43" href="#c" onClick="cl(5)">
				<area shape="rect" alt="Пара: 3, 6 (17 к 1)" coords="62,10,70,43" href="#c" onClick="cl(4)">
				<area shape="rect" coords="45,10,61,43" href="#c" alt="Число: 3 (35 к 1)" onClick="cl(3)">
				<area shape="rect" alt="Пара: 0, 3 (17 к 1)" coords="36,10,44,43" href="#c" onClick="cl(2)">
				<area shape="poly" alt="Число: 0 (35 к 1)" coords="36,10, 25,10, 8,71, 25,132, 36,132" href="#c" onClick="cl(1)">
				</map>
				</div>

				<table><tr><td>

				<table border="0" cellspacing="0">
				<tr align="center">
				<td width="25" id="cstav1"><img src="images/emp.gif" alt="" width="25" height="10"></td>
				<td width="25" id="cstav2"><img src="images/emp.gif" alt="" width="25" height="10"></td>
				<td width="25" id="cstav3"><img src="images/emp.gif" alt="" width="25" height="10"></td>
				<td width="25" id="cstav4"><img src="images/emp.gif" alt="" width="25" height="10"></td>
				<td width="25" id="cstav5"><img src="images/emp.gif" alt="" width="25" height="10"></td>
				</tr>
				<tr align="center">
				<td><a href="#c"><img src="images/cap1.gif" alt="Ставка" width="23" height="22" border="0" onMouseOver="window.status=\'Ставка\'; return true" onClick="stv(1)"></a></td>
				<td><a href="#c"><img src="images/cap2.gif" alt="Ставка" width="23" height="22" border="0" onMouseOver="window.status=\'Ставка\'; return true" onClick="stv(2)"></a></td>
				<td><a href="#c"><img src="images/cap3.gif" alt="Ставка" width="23" height="22" border="0" onMouseOver="window.status=\'Ставка\'; return true" onClick="stv(3)"></a></td>
				<td><a href="#c"><img src="images/cap4.gif" alt="Ставка" width="23" height="22" border="0" onMouseOver="window.status=\'Ставка\'; return true" onClick="stv(4)"></a></td>
				<td><a href="#c"><img src="images/cap5.gif" alt="Ставка" width="23" height="22" border="0" onMouseOver="window.status=\'Ставка\'; return true" onClick="stv(5)"></a></td>
				</tr>
				</table>

				</td><td width="15">&nbsp;</td><td>

				<br>
				<span id="butshow"></span>

				</td></tr></table>

				</td>
				<td background="images/bord8.gif">&nbsp;</td>
				</tr>
				<tr>
				<td width="44" height="44" align="right" valign="bottom"><img src="images/bord5.gif" width="44" height="44"></td>
				<td width="100%" height="44"><img src="images/bord6.gif" width="500" height="44"></td>
				<td width="44" height="44" valign="bottom"><img src="images/bord7.gif" width="44" height="44"></td>
				</tr>
				</table>
				<br>
				<font color="red"><b>Ставка с фишек умножается на 10</b></font>
				<br>
				</td>
				</tr>
				</table></td>
				</tr>
				</table></td>
				</tr>
				</table>';

	display($page, "Рулетка", false);
?>
