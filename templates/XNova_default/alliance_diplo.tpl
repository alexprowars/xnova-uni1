<br>
	<table width=519>
	<tr>
	  <td class=c colspan=3>���� �������<td>
	</tr>
	{DMyQuery}
	<table>
<br>
	<table width=519>
	<tr>
	  <td class=c colspan=3>������� ������ �������</td>
	</tr>
	{DQuery}
	</table>
<br>
	<table width=519>
	<tr>
	  <td class=c colspan=4>��������� ����� ���������</td>
	</tr>
	{DText}
	</table>

<form action="?set=alliance&mode=diplo&edit=add" method=post>
  <table width=519>
	<tr>
	  <td class=c colspan=2>�������� ������ � ������</td>
	</tr>
	<tr>
	  <th>
		<select name=ally>
		  {a_list}
		</select>
	</th>
	  <th>
		<select name=status>
		  <option value="1">���������
		  <option value="2">���
		  <option value="3">�����
		</select>
	  </th>
	</tr>

	<tr>
	  <td class="c"><a href="?set=alliance">�����</a></td>
	  <td class="c">
		<input type="submit" value="��������">
	  </td>
	</tr>
  </table>
</form>