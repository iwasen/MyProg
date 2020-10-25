<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�o�^�Ґ�����Excel�\��
'******************************************************

Dim koukoku_id, baitai_id

'=== �o�͔N�̑I�����o�� ===
Private Sub SelectYear(y)
	Dim i, cy

	cy = Year(Date)
	For i = 2000 To cy
		Writeln "<option" & Selected(Trim(y), CStr(i)) & ">" & i & "</option>"
	Next
End Sub

'=== �L���^�C�v�I���� ===
Private Sub SelectKoukokuType(koukoku_id)
	Dim sql, ds

	sql = "SELECT * FROM T_KOUKOKU_TYPE ORDER BY KT_order"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Writeln "<option value=''" & Selected(koukoku_id, "") & ">- �S�� -</option>"
	Do Until ds.EOF
		Writeln "<option value='" & ds("KT_koukoku_id").Value & "'" & Selected(koukoku_id, ds("KT_koukoku_id").Value) & ">" & ds("KT_name") & "</option>"
		ds.MoveNext
	Loop
End Sub

'=== �}�̑I���� ===
Private Sub SelectBaitai(baitai_id)
	Dim sql, ds

	sql = "SELECT * FROM T_BAITAI WHERE BT_baitai_id<>'03' ORDER BY BT_order"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Writeln "<option value=''" & Selected(baitai_id, "") & ">- �S�� -</option>"
	Do Until ds.EOF
		Writeln "<option value='" & ds("BT_baitai_id").Value & "'" & Selected(baitai_id, ds("BT_baitai_id").Value) & ">" & ds("BT_name") & "</option>"
		ds.MoveNext
	Loop
End Sub

'=== ���C������ ===
SetHeaderTitle "��ʕ�W �o�^�Ґ��W�v�o��"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
					<tr>
						<form method="post" name="form1" action="g_total1.asp" target="_blank">
						<td class="m3" width="25%">�P�D�o�q�o�^�ꗗ</td>
						<td class="n3" width="65%">
							<font class="hc">�L���^�C�v</font>
							<select name="koukoku_id"><%SelectKoukokuType(koukoku_id)%></select>�@
							<font class="hc">�}��</font>
							<select name="baitai_id"><%SelectBaitai(baitai_id)%></select>
							<td class="n3"width="10%" align="center"><input type="submit" value="�o��"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form2" action="g_total2.asp" target="_blank">
						<td class="m3" width="25%">�Q�D�T�ʃg�[�^��</td>
						<td class="n3" width="65%">
							<select name="s_year"><%SelectYear 2000%>�N</select>
							<select name="s_month"><%SelectMonth 12%>��</select>�`
							<select name="e_year"><%SelectYear Year(Date)%>�N</select>
							<select name="e_month"><%SelectMonth Month(Date)%>��</select>�@�@�@�@�@�@�@�@�@�@�@�@�@�@�@�@�@
							<td class="n3" width="10%" align="center"><input type="submit" value="�o��"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form3" action="g_total3.asp" target="_blank">
						<td class="m3" width="25%">�R�D���ʃg�[�^��</td>
						<td class="n3" width="65%">
							<select name="s_year"><%SelectYear 2000%>�N</select>
							<select name="s_month"><%SelectMonth 12%>��</select>�`
							<select name="e_year"><%SelectYear Year(Date)%>�N</select>
							<select name="e_month"><%SelectMonth Month(Date)%>��</select>�@�@�@�@�@�@�@�@�@�@�@�@�@�@�@�@�@
							<td class="n3" width="10%" align="center"><input type="submit" value="�o��"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form4" action="g_total4.asp" target="_blank">
						<td class="m3" width="25%">�S�D�T�ʍL���^�C�v��</td>
						<td class="n3" width="65%">
							<select name="s_year"><%SelectYear 2000%>�N</select>
							<select name="s_month"><%SelectMonth 12%>��</select>�`
							<select name="e_year"><%SelectYear Year(Date)%>�N</select>
							<select name="e_month"><%SelectMonth Month(Date)%>��</select>�@
							<font class="hc">�L���^�C�v</font>
							<select name="koukoku_id"><%SelectKoukokuType(koukoku_id)%></select>�@
							<td class="n3" width="10%" align="center"><input type="submit" value="�o��"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form5" action="g_total5.asp" target="_blank">
						<td class="m3" width="25%">�T�D���ʍL���^�C�v��</td>
						<td class="n3" width="65%">
							<select name="s_year"><%SelectYear 2000%>�N</select>
							<select name="s_month"><%SelectMonth 12%>��</select>�`
							<select name="e_year"><%SelectYear Year(Date)%>�N</select>
							<select name="e_month"><%SelectMonth Month(Date)%>��</select>�@
							<font class="hc">�L���^�C�v</font>
							<select name="koukoku_id"><%SelectKoukokuType(koukoku_id)%></select>�@
							<td class="n3" width="10%" align="center"><input type="submit" value="�o��"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form6" action="g_total6.asp" target="_blank">
						<td class="m3" width="25%">�U�D�T�ʔ}�̕�</td>
						<td class="n3" width="65%">
							<select name="s_year"><%SelectYear 2000%>�N</select>
							<select name="s_month"><%SelectMonth 12%>��</select>�`
							<select name="e_year"><%SelectYear Year(Date)%>�N</select>
							<select name="e_month"><%SelectMonth Month(Date)%>��</select>�@
							<font class="hc">�}��</font>
							<select name="baitai_id"><%SelectBaitai(baitai_id)%></select>
							<td class="n3" width="10%" align="center"><input type="submit" value="�o��"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form7" action="g_total7.asp" target="_blank">
						<td class="m3" width="25%">�V�D���ʔ}�̕�</td>
						<td class="n3" width="65%">
							<select name="s_year"><%SelectYear 2000%>�N</select>
							<select name="s_month"><%SelectMonth 12%>��</select>�`
							<select name="e_year"><%SelectYear Year(Date)%>�N</select>
							<select name="e_month"><%SelectMonth Month(Date)%>��</select>�@
							<font class="hc">�}��</font>
							<select name="baitai_id"><%SelectBaitai(baitai_id)%></select>
							<td class="n3" width="10%" align="center"><input type="submit" value="�o��"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form8" action="g_total8.asp" target="_blank">
						<td class="m3" width="25%">�W�D�L���^�C�v�ʗ݌v</td>
						<td class="n3" width="65%">
							<select name="s_year"><%SelectYear 2000%>�N</select>
							<select name="s_month"><%SelectMonth 12%>��</select>�`
							<select name="e_year"><%SelectYear Year(Date)%>�N</select>
							<select name="e_month"><%SelectMonth Month(Date)%>��</select>�@
							<td class="n3" width="10%" align="center"><input type="submit" value="�o��"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form9" action="g_total9.asp" target="_blank">
						<td class="m3" width="25%">�X�D�}�̕ʗ݌v</td>
						<td class="n3" width="65%">
							<select name="s_year"><%SelectYear 2000%>�N</select>
							<select name="s_month"><%SelectMonth 12%>��</select>�`
							<select name="e_year"><%SelectYear Year(Date)%>�N</select>
							<select name="e_month"><%SelectMonth Month(Date)%>��</select>�@
							<td class="n3" width="10%" align="center"><input type="submit" value="�o��"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form10" action="g_total10.asp" target="_blank">
						<td class="m3" width="25%">10�D�T�ʔ}�́~�o�q����</td>
						<td class="n3" width="65%">
							<select name="s_year"><%SelectYear 2000%>�N</select>
							<select name="s_month"><%SelectMonth 12%>��</select>�`
							<select name="e_year"><%SelectYear Year(Date)%>�N</select>
							<select name="e_month"><%SelectMonth Month(Date)%>��</select>�@
							<font class="hc">�}��</font>
							<select name="baitai_id"><%SelectBaitai(baitai_id)%></select>
							<td class="n3" width="10%" align="center"><input type="submit" value="�o��"></td>
						</td>
						</form>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<br>
<input type="button" value=" �߂� " onclick="history.back()">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
