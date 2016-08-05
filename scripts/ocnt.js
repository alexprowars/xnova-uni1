function t() {
	v = new Date();
	n = new Date();
	o = new Date();
	for (cn = 1; cn <= anz; cn++) {
		bxx = document.getElementById('bxx' + cn);
		ss  = bxx.title;
		s   = ss - Math.round((n.getTime() - v.getTime()) / 1000.);
		m   = 0;
		h   = 0;
		if (s < 0) {
			bxx.innerHTML = "-";
		} else {
			if (s > 59) {
				m = Math.floor(s/60);
				s = s - m * 60;
			}
			if (m > 59) {
				h = Math.floor(m / 60);
				m = m - h * 60;
			}
			if (s < 10) {
				s = "0" + s;
			}
			if (m < 10) {
				m = "0" + m;
			}
		bxx.innerHTML = h + ":" + m + ":" + s + "";
		}
		bxx.title = bxx.title - 1;
	}
	window.setTimeout("t();", 999);
}

function addZeros(value, count)
{
	var ret = "";
	var ost;
	for(i = 0; i < count; i++)
	{
		ost = value % 10;
		value = Math.floor(value / 10);
		ret = ost + ret;
	};
	return(ret);
};

function validate_number(value)
{
	if(value == 0)
	{
		ret = "0";
	}
	else
	{
		var inv;
		if(value < 0)
		{
			value = -value;
			inv = 1;
		}
		else
		{
			inv = 0;
		};

		var ret = "";
		var ost;
		while(value > 0)
		{
			ost = value % 1000;
			value = Math.floor(value / 1000);
			if(value <= 0)
			{
				s_ost = ost;
			}
			else
			{
				s_ost = addZeros(ost, 3);
			};
			if(ret == "")
			{
				ret = s_ost;
			}
			else
			{
				ret = s_ost + "." + ret;
			};
		};
		if(inv == 1)
		{
			ret = "-" + ret;
		};
	};
	return(ret);
};
