<HTML><HEAD><meta content="text/html; charset=windows-1251" http-equiv=Content-type>
<TITLE>Смайлики</TITLE>
<SCRIPT>
function S(name)
{
	if(!window.opener||window.opener==self) return;
    	var frame = window.opener.top;
    	if(frame) 
    	{
        	var msgg = frame.document.forms[<?=intval($_GET['form'])?>].text;        
        	msgg.value  += ':'+name+':';	
    	}
}
</SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="/scripts/smiles.js"></SCRIPT>
</HEAD>
<BODY leftmargin=2 topmargin=2 marginheight=2 bgcolor=#d3d3d3><CENTER>
<SCRIPT>
var i=0;
while(i<sm.length) {
        var s = sm[i++];
        document.write('<IMG SRC=images/smile/'+s+'.gif WIDTH='+sm[i++]+' HEIGHT='+sm[i++]+' BORDER=0 ALT="'+s+'" onclick="S(\''+s+'\')" style="cursor:hand"> ');
}
</SCRIPT>

<BR><INPUT TYPE="button" value=" Закрыть " onclick="window.close()" style="border: solid 1pt #B0B0B0; font-family: MS Sans Serif; font-size: 10px; color: #191970; MARGIN-BOTTOM: 2px; MARGIN-TOP: 1px;">
</CENTER>
</BODY>
</HTML>