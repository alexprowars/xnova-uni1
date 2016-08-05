<center>
<form action="?set=options&mode=change" method="post">
<table width="519">
<tr>
	<td class="c" colspan="2">{userdata}</td>
</tr><tr>
	<th>{username}</th>
	<th><input name="db_character" size="20" value="{opt_usern_data}" type="text"></th>
</tr><tr>
	<th>{lastpassword}</th>
	<th><input name="db_password" size="20" value="" type="password"></th>
</tr><tr>
	<th>{newpassword}</th>
	<th><input name="newpass1" size="20" maxlength="40" type="password"></th>
</tr><tr>
	<th>{newpasswordagain}</th>
	<th><input name="newpass2" size="20" maxlength="40" type="password"></th>
</tr><tr>
	<th><a title="{emaildir_tip}">{emaildir}</a></th>
	<th><input name="db_email" maxlength="100" size="20" value="{opt_mail1_data}" type="text"></th>
</tr><tr>
	<th><a title="Номер ICQ">ICQ</a></th>
	<th><input name="icq" maxlength="10" size="20" value="{opt_icq_data}" type="text"></th>
</tr><tr>
	<th><a title="Пример: http://vkontakte.ru/id{??????}">vkontakte.ru ID</a></th>
	<th><input name="vkontakte" maxlength="15" size="20" value="{opt_vkontakte_data}" type="text"></th>
</tr><tr>
	<td class="c" colspan="2">{general_settings}</td>
</tr><tr>
	<th>{opt_lst_ord}</th>
	<th>
		<select name="settings_sort">
		{opt_lst_ord_data}
		</select>
	</th>
</tr><tr>
	<th>{opt_lst_cla}</th>
	<th>
		<select name="settings_order">
		{opt_lst_cla_data}
		</select>
	</th>
</tr><tr>
	<th>Стандартное оформление</th>
	<th><input name="design"{opt_sskin_data} type="checkbox"></th>
</tr><tr>
	<th>Привязка сессии к IP</th>
	<th><input name="design"{opt_sec_data} type="checkbox"></th>
</tr><tr>
	<th>Цвет чата</th>
	<th><SELECT NAME='color' style='WIDTH: 121'>{opt_lst_color_data}</SELECT></th>
</tr><tr>
	<th>Аватар</th>
	<th>{avatar} <a href="?set=avatar">Выбрать аватар</a></th>
</tr><tr>
	<td class="c" colspan="2">{delete_vacations}</td>
</tr><tr>
	<th><a title="{vacations_tip}">{mode_vacations}</a></th>
	<th><input name="urlaubs_modus"{opt_modev_data} type="checkbox" /></th>
</tr><tr>
	<th><a title="{deleteaccount_tip}">{deleteaccount}</a></th>
	<th><input name="db_deaktjava"{opt_delac_data} type="checkbox" /></th>
</tr><tr>
	<th colspan="2"><input value="{save_settings}" type="submit"></th>
</tr>
</table>
</form>
</center>