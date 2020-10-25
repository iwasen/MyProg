<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�މ�ҊǗ�Excel�\��
'******************************************************

Dim sql, ds

'=== �o�͔N�̑I�����o�� ===
Private Sub SelectYear(y)
	Dim i, cy

	cy = Year(Date)

	For i = 2000 To cy
		Writeln "<option" & Selected(Trim(y), CStr(i)) & ">" & i & "</option>"
	Next
End Sub

'=== ���C������ ===
SetHeaderTitle "�މ�ҊǗ�"

sql = "SELECT * FROM T_TAIKAI"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
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
	<table>
		<tr>
			<table border=0 cellspacing=0 cellpadding=0 width='800'>
				<tr>
			<td align="left" class="m0">���o�͊��Ԃ��w�肵�Ă�������
			<td align="right"><%=TimeFormat(Now)%>���݁@�މ�Ґ��@<%=ds.RecordCount%>��</td>
				</tr>
			</table>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='800'>
					<tr>
						<form method="post" name="form1" action="taikai1_csv.asp" target="_blank">
							<td class="m3" width="30%">�P�D�މ�҂b�r�u�o��</td>
							<td class="n3" width="60%">
								<select name="s_year"><%SelectYear 2000%></select>�N
								<select name="s_month"><%SelectMonth 12%></select>��
								<select name="s_day"><%Selectday 1%></select>���`
								<select name="e_year"><%SelectYear Year(Date)%></select>�N
								<select name="e_month"><%SelectMonth Month(Date)%></select>��
								<select name="e_day"><%Selectday Day(Date)%></select>��
							<td class="n3"width="10%" align="center"><input type="submit" value="�o��"></td>
							</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form2" action="taikai2_excel.asp" target="_blank">
							<td class="m3" width="30%">�Q�D�N�ʓ���E�މ�Ґ�����</td>
							<td class="n3" width="60%">
							<td class="n3" width="10%" align="center"><input type="submit" value="�o��"></td>
							</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form3" action="taikai3_excel.asp" target="_blank">
							<td class="m3" width="30%">�R�D���ʓ���E�މ�Ґ�����</td>
							<td class="n3" width="60%">
								<select name="s_year"><%SelectYear Year(Date)%></select>�N
							<td class="n3" width="10%" align="center"><input type="submit" value="�o��"></td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form4" action="taikai4_excel.asp" target="_blank">
							<td class="m3" width="30%">�S�D���ʓ���E�މ�Ґ�����</td>
							<td class="n3" width="60%">
								<select name="s_year"><%SelectYear Year(Date)%></select>�N
								<select name="s_month"><%SelectMonth Month(Date)%></select>��
							<td class="n3" width="10%" align="center"><input type="submit" value="�o��"></td>
							</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form5" action="taikai5_excel.asp" target="_blank">
						<td class="m3" width="30%">�T�D�މ�҂̓������</td>
						<td class="n3" width="60%">
							<select name="s_year"><%SelectYear 2000%></select>�N
							<select name="s_month"><%SelectMonth 12%></select>���`
							<select name="e_year"><%SelectYear Year(Date)%></select>�N
							<select name="e_month"><%SelectMonth Month(Date)%></select>��
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
<input type="button" value=" �߂� " onclick="location.href='../menu.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
