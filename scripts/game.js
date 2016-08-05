function format(zahl)
{
	var zahl;
	var zahl_tmp1;
	var zahl_tmp2;
	var zahl_tmp3;
	var html = "";
	max[0]--;
	
	if(zahl >= 1000000)
	{
		zahl_tmp1 = Math.floor(zahl / 1000000);
		html += "" + zahl_tmp1 + ".";
		zahl_tmp2 = Math.floor((zahl - (zahl_tmp1 * 1000000)) / 1000) + "";
		if(zahl_tmp2.length == 1)
		{
			html += "00" + zahl_tmp2 + ".";
		}
		else if(zahl_tmp2.length == 2)
		{
			html += "0" + zahl_tmp2 + ".";
		}
		else
		{
			html += "" + zahl_tmp2 + ".";
		}
		zahl_tmp3 = Math.floor(zahl - (zahl_tmp1 * 1000000) - (zahl_tmp2 * 1000)) + "";
		if(zahl_tmp3.length == 1)
		{
			html += "00" + zahl_tmp3 + "";
		}	
		else if(zahl_tmp3.length == 2)
		{
			html += "0" + zahl_tmp3 + "";
		}
		else
		{
			html += "" + zahl_tmp3 + "";
		}
	}
	else if(zahl >= 1000)
	{
		zahl_tmp1 = Math.floor(zahl / 1000);
		html += "" + zahl_tmp1 + ".";
		zahl_tmp2 = Math.floor(zahl - (zahl_tmp1 * 1000)) + "";
		if(zahl_tmp2.length == 1)
		{
			html += "00" + zahl_tmp2 + "";
		}
		else if(zahl_tmp2.length == 2)
		{
			html += "0" + zahl_tmp2 + "";
		}
		else
		{
			html += "" + zahl_tmp2 + "";
		}
	}
	else
	{
		html = zahl;
	}
	return html;
}

function Res_count()
{
	var metall = 0;
	var crystall = 0;
	var deuterium = 0;
	var darkmat = 0;
	var bold1_met = '<font color=#3abc55>';
	var bold2_met = '</font>';
	var bold1_cry = '<font color=#3abc55>';
	var bold2_cry = '</font>';
	var bold1_deu = '<font color=#3abc55>';
	var bold2_deu = '</font>';
	var faktor_met = 1;
	var faktor_cry = 1;
	var faktor_deu = 1;
	var ges_met = production[0];
	var ges_cry = production[1];
	var ges_deu = production[2];

	var rohstoffe = document.getElementById('ress');
	if(rohstoffe.metall.value >= max[0] - ress[0] || rohstoffe.bmetall.value == 1 || ress[0] >= max[0]) {
		bold1_met = '<font color=red>';
		bold2_met = '</font>';
		rohstoffe.bmetall.value = 1;
		faktor_met = 0;

	}
	metall = Math.floor(rohstoffe.metall.value) + Math.floor(ress[0]);
	rohstoffe.metall.value = (Math.floor(rohstoffe.metall.value * 10000)/10000) + (ges_met * faktor_met);
	
	if(rohstoffe.crystall.value >= max[1] - ress[1] || rohstoffe.bcrystall.value == 1 || ress[1] >= max[1]) {
		bold1_cry = '<font color=red>';
		bold2_cry = '</font>';
		rohstoffe.bcrystall.value = 1;
		faktor_cry = 0;
	}
	crystall = Math.floor(rohstoffe.crystall.value) + Math.floor(ress[1]);
	rohstoffe.crystall.value = (Math.floor(rohstoffe.crystall.value * 10000)/10000) + (ges_cry * faktor_cry);
	
	if(rohstoffe.deuterium.value >= max[2] - ress[2] || rohstoffe.bdeuterium.value == 1 || ress[2] >= max[2]) {
		bold1_deu = '<font color=red>';
		bold2_deu = '</font>';
		rohstoffe.bdeuterium.value = 1;
		faktor_deu = 0;
	}
	deuterium = Math.floor(rohstoffe.deuterium.value) + Math.floor(ress[2]);
	rohstoffe.deuterium.value = (Math.floor(rohstoffe.deuterium.value * 10000)/10000) + (ges_deu * faktor_deu);

	if (metall < 0) metall = 0;
	if (crystall < 0) crystall = 0;
	if (deuterium < 0) deuterium = 0;
	
    if(document.getElementById('met') && document.getElementById('cry') && document.getElementById('deu')){
    	document.getElementById('met').innerHTML = bold1_met+format(metall)+bold2_met;
    	document.getElementById('cry').innerHTML = bold1_cry+format(crystall)+bold2_cry;
    	document.getElementById('deu').innerHTML = bold1_deu+format(deuterium)+bold2_deu;
    }
    if(document.layers){
    	document.getElementById('met').document.write(bold1_met+format(metall)+bold2_met);
    	document.getElementById('met').document.close();
    	document.getElementById('cry').document.write(bold1_cry+format(crystall)+bold2_cry);
    	document.getElementById('cry').document.close();
    	document.getElementById('deu').document.write(bold1_deu+format(deuterium)+bold2_deu);
    	document.getElementById('deu').document.close();
    }
}

function FlotenTime (obj, time) {
	v = new Date();
	var divs = document.getElementById(obj);
	ttime = time;
	mfs1 = 0;
	hfs1 = 0;
	if (ttime < 1) {
		divs.innerHTML = "-";
	} else {
		if (ttime > 59) {
			mfs1 = Math.floor(ttime / 60);
			ttime = ttime - mfs1 * 60;
		}
		if (mfs1 > 59) {
			hfs1 = Math.floor(mfs1 / 60);
			mfs1 = mfs1 - hfs1 * 60;
		}
		if (ttime < 10) {
			ttime = "0" + ttime;
		}
		if (mfs1 < 10) {
			mfs1 = "0" + mfs1;
		}
		divs.innerHTML = hfs1 + ":" + mfs1 + ":" + ttime;
	}
	time = time - 1;
	window.setTimeout(function(){FlotenTime(obj,time)}, 999);
}
  	
Djs = (D = new Date()).getTime() - D.getTimezoneOffset()*60000;

function hms(layr, X) {
      var d,mn,m,s;
      document.getElementById(layr).innerHTML = ((d=X.getDate())<10?'0':'')+d+'-'+((mn=X.getMonth()+1)<10?'0':'')+mn+'-'+X.getFullYear()+' '+X.getHours()
      +':'+((m=X.getMinutes())<10?'0':'')+m
      +':'+((s=X.getSeconds())<10?'0':'')+s;
}

function UpdateClock(){    
   	var D0 = new Date;			      
    hms('clock', new Date(D0.getTime() + serverTime));
	
	setTimeout(UpdateClock, 999);
}
