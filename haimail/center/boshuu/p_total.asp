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

'=== ���C������ ===
SetHeaderTitle "�l�A�t�B���G�C�g��W �o�^�Ґ��W�v�o��"
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
						<form method="post" name="form2" action="p_total1.asp" target="_blank">
						<td class="m3" width="30%">�P�D�T�ʃg�[�^��</td>
						<td class="n3">
							<select name="s_year"><%SelectYear 2000%>�N</select>
							<select name="s_month"><%SelectMonth 12%>��</select>�`
							<select name="e_year"><%SelectYear Year(Date)%>�N</select>
							<select name="e_month"><%SelectMonth Month(Date)%>��</select>�@�@�@�@�@�@�@�@�@�@�@�@�@�@�@�@�@
							<td class="n3" width="10%" align="center"><input type="submit" value="�o��"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form3" action="p_total2.asp" target="_blank">
						<td class="m3" width="30%">�Q�D���ʃg�[�^��</td>
						<td class="n3">
							<select name="s_year"><%SelectYear 2000%>�N</select>
							<select name="s_month"><%SelectMonth 12%>��</select>�`
							<select name="e_year"><%SelectYear Year(Date)%>�N</select>
							<select name="e_month"><%SelectMonth Month(Date)%>��</select>�@�@�@�@�@�@�@�@�@�@�@�@�@�@�@�@�@
							<td class="n3" width="10%" align="center"><input type="submit" value="�o��"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form4" action="p_total3.asp" target="_blank">
						<td class="m3" width="30%">�R�D�����o�[�ʏT�ʃg�[�^��</td>
						<td class="n3">
							<select name="s_year"><%SelectYear 2000%>�N</select>
							<select name="s_month"><%SelectMonth 12%>��</select>�`
							<select name="e_year"><%SelectYear Year(Date)%>�N</select>
							<select name="e_month"><%SelectMonth Month(Date)%>��</select>�@
							<td class="n3" width="10%" align="center"><input type="submit" value="�o��"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form5" action="p_total4.asp" target="_blank">
						<td class="m3" width="30%">�S�D�����o�[�ʌ��ʃg�[�^��</td>
						<td class="n3">
							<select name="s_year"><%SelectYear 2000%>�N</select>
							<select name="s_month"><%SelectMonth 12%>��</select>�`
							<select name="e_year"><%SelectYear Year(Date)%>�N</select>
							<select name="e_month"><%SelectMonth Month(Date)%>��</select>�@
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
