<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�v���W�F�N�g�Ǘ�
'******************************************************

'=== �o�͔N�̑I�����o�� ===
Private Sub SelectYear(y)
	Dim i, cy

	cy = Year(Date)
	For i = 2000 To cy
		Writeln "<option" & Selected(Trim(y), CStr(i)) & ">" & i & "</option>"
	Next
End Sub

'=== ���C������ ===
Dim dairiten_id, company_id, tantousha_id

SetHeaderTitle "�v���W�F�N�g�W�v�o��"
%>

<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../../inc/header.asp"-->

<center>
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
					<tr>
						<form method="post" name="form1" action="month_total.asp">
						<td class="m3" width="30%">�P�D���ʃg�[�^��</td>
						<td class="n3" width="60%">
							<select name="s_year"><%SelectYear Year(Date)%>�N</select>
							<select name="s_month"><%SelectMonth Month(Date)%>��</select>
							<td class="n3" align="center" width="10%"><input type="submit" value="�o��"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form2" action="kind_total.asp">
						<td class="m3" width="30%">�Q�D��ʌ��ʃg�[�^��</td>
						<td class="n3" width="60%">
							<select name="s_year"><%SelectYear Year(Date)%>�N</select>
							<select name="s_month"><%SelectMonth Month(Date)%>��</select>
							<td class="n3" width="10%" align="center"><input type="submit" value="�o��"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form3" action="dairitenA_total.asp">
						<td class="m3" width="30%">�R�D�㗝�X�`�ʌ��ʃg�[�^��</td>
						<td class="n3" width="60%">
							<select name="s_year"><%SelectYear Year(Date)%>�N</select>
							<select name="s_month"><%SelectMonth Month(Date)%>��</select>
							<td class="n3" width="10%" align="center"><input type="submit" value="�o��"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form4" action="dairitenB_total.asp"">
						<td class="m3" width="30%">�S�D�㗝�X�a�ʌ��ʃg�[�^��</td>
						<td class="n3" width="60%">
							<select name="s_year"><%SelectYear Year(Date)%>�N</select>
							<select name="s_month"><%SelectMonth Month(Date)%>��</select>
							<td class="n3" width="10%" align="center"><input type="submit" value="�o��"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form5" action="kigyou_total.asp">
						<td class="m3" width="30%">�T�D��ƕʌ��ʃg�[�^��</td>
						<td class="n3" width="60%">
							<select name="s_year"><%SelectYear Year(Date)%>�N</select>
							<select name="s_month"><%SelectMonth Month(Date)%>��</select>�@
							<td class="n3" width="10%" align="center"><input type="submit" value="�o��"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form6" action="pjt_response.asp" target="_blank">
						<td class="m3" width="30%">�U�D��ʌ��ʃ��X�|���X����</td>
						<td class="n3" width="60%">
							<select name="s_year"><%SelectYear Year(Date)%>�N</select>
							<select name="s_month"><%SelectMonth Month(Date)%>��</select>
							<td class="n3" width="10%" align="center"><input type="submit" value="�o��"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form7" action="send_recv.asp" target="_blank">
						<td class="m3" width="30%">�V�D���ʑ��M�^��M����</td>
						<td class="n3" width="60%">
							<select name="s_year"><%SelectYear Year(Date)%>�N</select>
							<select name="s_month"><%SelectMonth Month(Date)%>��</select>
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
<input type="button" value=" �߂� " onclick="location.href='../index.asp'">
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
