function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

MM_preloadImages('images/gmerza.gif', 'images/gmerz1.gif', 'images/gmerz2.gif', 'images/gmerz3.gif')

//////функции игры

var betarr=new Array(0.2, 1, 5, 10, 50);
var rdig = new Array(1, 3, 5, 7, 9, 12, 14, 16, 18, 19, 21, 23, 25, 27, 30, 32, 34, 36)

var bet=1;
var but1='';
var but2='';
var cell=0;
var cellhid=cell;
var cap='';

window.defaultStatus='Рулетка';

//////

function cl(pls)
{
	cap = document.getElementById('cap1');

	var arlft = new Array(0, 15, 30, 42, 54, 68, 83, 95, 105, 120, 132, 147, 160, 172, 186, 198, 210, 225, 236, 251, 262, 277, 288, 303, 314, 329, 355, 30, 42, 54, 68, 83, 95, 105, 120, 132, 147, 160, 172, 186, 198, 210, 225, 236, 251, 262, 277, 288, 303, 314, 329, 30, 42, 54, 68, 83, 95, 105, 120, 132, 147, 160, 172, 186, 198, 210, 225, 236, 251, 262, 277, 288, 303, 314, 329, 355, 30, 42, 54, 68, 83, 95, 105, 120, 132, 147, 160, 172, 186, 198, 210, 225, 236, 251, 262, 277, 288, 303, 314, 329, 30, 42, 54, 68, 83, 95, 105, 120, 132, 147, 160, 172, 186, 198, 210, 225, 236, 251, 262, 277, 288, 303, 314, 329, 355, 30, 42, 54, 68, 83, 95, 105, 120, 132, 147, 160, 172, 186, 198, 210, 225, 236, 251, 262, 277, 288, 303, 314, 329, 83, 186, 288, 56, 108, 160, 213, 265, 315)
	var artop = new Array(0, 61, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 41, 41, 41, 41, 41, 41, 41, 41, 41, 41, 41, 41, 41, 41, 41, 41, 41, 41, 41, 41, 41, 41, 41, 41, 62, 62, 62, 62, 62, 62, 62, 62, 62, 62, 62, 62, 62, 62, 62, 62, 62, 62, 62, 62, 62, 62, 62, 62, 62, 83, 83, 83, 83, 83, 83, 83, 83, 83, 83, 83, 83, 83, 83, 83, 83, 83, 83, 83, 83, 83, 83, 83, 83, 102, 102, 102, 102, 102, 102, 102, 102, 102, 102, 102, 102, 102, 102, 102, 102, 102, 102, 102, 102, 102, 102, 102, 102, 102, 123, 123, 123, 123, 123, 123, 123, 123, 123, 123, 123, 123, 123, 123, 123, 123, 123, 123, 123, 123, 123, 123, 123, 123, 141, 141, 141, 178, 178, 178, 178, 178, 178)

	for (i = 0; i < 158; i++)
	{
		if (i==pls)
		{
			cap.style.visibility ='visible';
			cap.style.left = arlft[pls];
			cap.style.top = artop[pls];
			break;
		}
	}

	cell = pls;
	clean();
	buttons();
}
function clean()
{
	//инфа
	document.getElementById('infotxt').innerHTML = '&nbsp;';
}
function clbets(click)
{
	cap = document.getElementById('cap1');
	cap.style.visibility ='hidden';
	cellhid = cell;
	cell = 0;
	buttons();
	if (click==1)
	{
		cellhid = 0;
	}
}

////////

function showrul()
{
	var dig=gframe.document.getElementById('dig').innerHTML;

	document.getElementById('rulet').style.background = "url('images/gmerz1.gif')";
	document.getElementById('rulet').innerHTML = dig;

	for (i = 0; i < 18; i++)
	{
		if (dig==rdig[i])
		{
			document.getElementById('rulet').style.background = "url('images/gmerz2.gif')";
			break;
		}
		else
		{
			document.getElementById('rulet').style.background = "url('images/gmerz1.gif')";
		}
	}
	if (dig==0)
	{
		document.getElementById('rulet').style.background = "url('images/gmerz3.gif')";
	}
	//buttons(2);
}

function stv(stid)
{
	for (i = 1; i < 6; i++)
	{
		if (stid==i)
		{
			document.getElementById('cstav'+i).innerHTML = '<img src="images/ar.gif" alt="" width="25" height="10">';
			document.getElementById('cap1').innerHTML = '<a href="#c"><img src="images/cap'+ i +'.gif" alt="Убрать ставку" width="23" height="22" border="0" onClick="clbets()"></a>';
			bet=i;
			buttons();
		}
		else
		{
			document.getElementById('cstav'+i).innerHTML='<img src="images/emp.gif" alt="" width="25" height="10">';
		}
	}
}

function buttons(i)
{
	but1='<a href="?set=ruletka&enter=true&s=1&stav=' + bet + '&cell=' + cell + '" target="gframe" class="abut" onMouseOver="window.status=\'Пуск\'; return true" onClick="blocktmp();">Пуск</a> ';
	if (i==1)
	{
	but1='<span class="abutoff">Пуск</span> ';
	}
	document.getElementById('butshow').innerHTML=but1;
}

function blocktmp()
{
	window.status='';
	setTimeout("buttons(1)", 300);
	setTimeout("buttons(2)", 2000);
}

function showtxt()
{
	var bet=gframe.document.getElementById('bet').innerHTML;
	var win=gframe.document.getElementById('win').innerHTML;
	win=win-0;

	var betsum=betarr[bet];

	if (win>0)
	{
		document.getElementById('infotxt').innerHTML='Выигрыш: <b>'+win+'</b> кредитов';
	}
}

function start()
{
	setTimeout("start2()", 100);
}

function start2()
{
	var credit=gframe.document.getElementById('credit').innerHTML;
	var bet=gframe.document.getElementById('bet').innerHTML;
	var mon=gframe.document.getElementById('mon').innerHTML;
	var dig=gframe.document.getElementById('dig').innerHTML;
	var win=gframe.document.getElementById('win').innerHTML;

	document.getElementById('infotxt').innerHTML='&nbsp;';

	stv(bet);

	if (mon==0) //начало
	{
		document.getElementById('creditsum').innerHTML=credit;

		showrul();
		buttons();
	}
	if (mon==1 || mon==2)
	{
		document.getElementById('rulet').style.background = "url('images/gmerza.gif')";
		document.getElementById('rulet').innerHTML='&nbsp;';

		buttons(1);
		setTimeout("finish()", 1500);
	}
	if (mon==3)
	{
		document.getElementById('infotxt').innerHTML='Пожалуйста, перепроверьте свою ставку.';
	}
}

function finish()
{
	var bet=gframe.document.getElementById('bet').innerHTML;
	var win=gframe.document.getElementById('win').innerHTML;
	var mon=gframe.document.getElementById('mon').innerHTML;
	var credit=gframe.document.getElementById('credit').innerHTML;

	document.getElementById('creditsum').innerHTML=credit;

	showrul();
	buttons(2);

	win=win-0;

	var betsum=betarr[bet];

	if (win>0)
	{
		document.getElementById('infotxt').innerHTML='Выигрыш: <b>'+win+'</b> кредитов';
	}
}