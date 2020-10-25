<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�r�W�l�X�z�MJOB�o�^���
'******************************************************

'=== �c�a���f�[�^�ǂݍ��� ===
Private Sub ReadJob(id)
	Dim sql, ds

	sql = "SELECT BH_job_name,BH_notify_adr FROM T_BSHAISHIN WHERE BH_job_id=" & id
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If ds.EOF Then
		Response.Redirect "index.asp"
	End If

	job_name = ds("BH_job_name").Value
	notify_adr = ds("BH_notify_adr").Value
End Sub

'=== �ߋ���JOB�̑I�� ===
Private Sub SelectJob()
	Dim sql, ds

	sql = "SELECT BH_job_id,BH_job_name FROM T_BSHAISHIN ORDER BY BH_job_id DESC"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Writeln "<option value=''>- �ߋ���JOB������I�� -</option>"
	Do Until ds.EOF
		Writeln "<option value='" & ds("BH_job_id").Value & "'>" & ds("BH_job_name").Value & "</option>"
		ds.MoveNext
	Loop
End Sub

'=== ���C������ ===
SetHeaderTitle "�r�W�l�X�z�MJOB�o�^"

Dim job_id, job_name, notify_adr, cp

notify_adr = "otayori@haimail.net"

job_id = Request.QueryString("id")
If Not IsEmpty(job_id) Then
	ReadJob job_id
End If

cp = Request.QueryString("cp")
If Not IsEmpty(cp) Then
	ReadJob cp
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
function InputCheck() {
	with (document.form1) {
		if (job_name.value == "") {
			alert("�i�n�a������͂��Ă��������B");
			job_name.focus();
			return false;
		}
		if (notify_adr.value == "") {
			alert("���M�ʒm��A�h���X����͂��Ă��������B");
			notify_adr.focus();
			return false;
		}
	}
	return true;
}
function OnClick_regist() {
	if (InputCheck()) {
		if (confirm("�i�n�a��o�^���܂��B��낵���ł����H")) {
			document.form1.action.value = "regist";
			document.form1.submit();
		}
	}
}
function OnClick_update() {
	if (InputCheck()) {
		if (confirm("�i�n�a���X�V���܂��B��낵���ł����H")) {
			document.form1.action.value = "update";
			document.form1.submit();
		}
	}
}
function OnClick_delete() {
	if (InputCheck()) {
		if (confirm("�i�n�a���폜���܂��B��낵���ł����H")) {
			document.form1.action.value = "delete";
			document.form1.submit();
		}
	}
}
function OnChange_copy() {
	if (document.form1.copy.value != "")
		location.href = "bh_job.asp?cp=" + document.form1.copy.value
}
//-->
</SCRIPT>
</head>
<body onload="document.form1.job_name.focus()">

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="bh_job2.asp" name="form1">
	<input type="hidden" name="job_id" value="<%=job_id%>">
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td class="m0">���i�n�a���</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
<%
If Not IsEmpty(job_id) Then
%>
					<tr>
						<td class="m3" width="20%">JOBID</td>
						<td class="n3"><%=job_id%></td>
					</tr>
<%
End If
%>
					<tr>
						<td class="m3">�i�n�a��</td>
						<td class="n3">
							<input type="text" name="job_name" size=50 maxlength=50 value="<%=HTMLEncode(job_name)%>">
							<font class="note">�i�S�p�T�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">���M���A�h���X</td>
						<td class="n3">
							<input type="text" name="notify_adr" size=50 maxlength=50 value="<%=HTMLEncode(notify_adr)%>">
							<font class="note">�i���p�T�O�����܂Łj</font>
						</td>
					</tr>
<%
If IsEmpty(job_id) Then
%>
					<tr>
						<td class="m3">�R�s�[</td>
						<td class="n3"><select name="copy" onchange="OnChange_copy()"><%SelectJob%></select></td>
					</tr>
<%
End If
%>
				</table>
			</td>
		</tr>
	</table>

	<br>
<%
If IsEmpty(job_id) Then
%>
	<input type="button" name="regist" value=" �o�^ " Onclick="OnClick_regist()">
<%
Else
%>
	<input type="button" name="update" value=" �X�V " Onclick="OnClick_update()">
	<input type="button" name="delete" value=" �폜 " Onclick="OnClick_delete()">
<%
End If
%>
	<input type="reset" value="���Z�b�g">
	<input type="button" value=" �߂� " onclick="history.back()">
	<input type="hidden" name="action">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
