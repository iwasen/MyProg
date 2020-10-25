<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�S�z�M�A���P�[�g���X�|���X���X�g�\�����
'******************************************************

'=== ���C������ ===
Dim job_id

SetHeaderTitle "�S�z�M �A���P�[�g���X�|���X���X�g�\��"

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_text() {
	document.form1.action = "enq_text.asp";
	document.form1.submit();
}
function OnClick_list() {
	document.form1.action = "enq_list.asp";
	document.form1.submit();
}
function OnClick_csv() {
	document.form1.action = "enq_csv.asp";
	document.form1.submit();
}
function OnClick_report() {
	document.form1.action = "enq_report.asp";
	document.form1.submit();
}
function OnClick_clear() {
	if (confirm("���ǂ̃��[����S�Ė��ǂɐݒ肵�܂��B��낵���ł����H"))
		location.href = "enquete2.asp?job_id=<%=job_id%>"
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" name="form1">
	<input type="hidden" name="job_id" value="<%=job_id%>">
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td class="m0">���A���P�[�g���X�|���X���X�g�\������</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="20%">SEQ</td>
						<td class="n3">
							<input type="text" name="seq_from" size=5>����
							<input type="text" name="seq_to" size=5>�܂�
						</td>
					</tr>
					<tr>
						<td class="m3">��M���ԁi�J�n�j</td>
						<td class="n3">
							<select name="start_date_y"><%SelectYear3 ""%></select>�N
							<select name="start_date_m"><%SelectMonth 1%></select>��
							<select name="start_date_d"><%SelectDay 1%></select>������
						</td>
					</tr>
					<tr>
						<td class="m3">��M����(�I���j</td>
						<td class="n3">
							<select name="end_date_y"><%SelectYear3 ""%></select>�N
							<select name="end_date_m"><%SelectMonth 1%></select>��
							<select name="end_date_d"><%SelectDay 1%></select>������
						</td>
					</tr>
					<tr>
						<td class="m3">����</td>
						<td class="n3">
							<input type="checkbox" name="midoku" value="1">���ǂ̃��X�|���X���X�g�̂ݕ\���@
							<input type="button" name="clear" value="�N���A" onclick="OnClick_clear()">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<br>
	<input type="button" name="text" value="�e�L�X�g�\��" Onclick="OnClick_text()">
	<input type="button" name="list" value="���X�g�ꗗ" Onclick="OnClick_list()">
	<input type="button" name="csv" value="CSV�擾" onclick="OnClick_csv()">
	<input type="button" name="report" value="���|�[�g����" onclick="window.open('report.asp?job_id=<%=job_id%>&file=enq.dat')">
	<input type="reset" name="reset" value="���Z�b�g">
	<input type="button" name="back" value=" �߂� " onclick="location.href='index.asp'">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
