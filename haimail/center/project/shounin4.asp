<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�W���u���F�҂������m�F��ʏ���
'******************************************************

'=== �W���u���擾 ===
Private Sub ReadJob(job_id)
	Dim sql, ds

	sql = "SELECT PJ_project_id,PJ_project_name,JB_job_name" & vbCrLf & _
				"FROM T_JOB,T_PROJECT WHERE JB_job_id=" & job_id & " AND JB_project_id=PJ_project_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		project_id = ds("PJ_project_id").Value
		project_name = ds("PJ_project_name").Value
		job_name = ds("JB_job_name").Value
	End If
End Sub

'=== ���C������ ===
Dim project_id, project_name, job_id, job_name

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

ReadJob job_id

SetHeaderTitle "�W���u���F�҂������m�F"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1() {
	return confirm("���̃W���u�̏��F�҂����������܂��B��낵���ł����H");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" name="form1" action="shounin5.asp" onsubmit="return OnSubmit_form1()">
	<input type="hidden" name="job_id" value="<%=job_id%>">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">���v���W�F�N�g</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
					<tr>
						<td class="m3" width="25%">�v���W�F�N�gID</td>
						<td class="n3"><%=project_id%></td>
					</tr>
					<tr>
						<td class="m3" width="25%">�v���W�F�N�g��</td>
						<td class="n3"><%=ReplaceTag(project_name)%></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td><br></td>
		</tr>
		<tr>
			<td class="m0">���W���u</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
					<tr>
						<td class="m3" width="25%">�W���uID</td>
						<td class="n3"><%=job_id%></td>
					</tr>
					<tr>
						<td class="m3" width="25%">�W���u��</td>
						<td class="n3"><%=ReplaceTag(job_name)%></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value="�@���F�҂������@">
	<input type="button" value=" �߂� " onclick="history.back()">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
