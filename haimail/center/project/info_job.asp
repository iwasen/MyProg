<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�W���u���e��ʏ���
'******************************************************

'=== �W���u���擾 ===
Private Sub ReadJob(job_id)
	Dim sql, ds

	sql = "SELECT PJ_project_id,PJ_project_name,JB_job_name,JB_status,SM_subject,SM_start_date" & vbCrLf & _
				"FROM T_PROJECT,T_JOB,T_SEND_MAIL" & vbCrLf & _
				"WHERE JB_job_id=" & job_id & " AND PJ_project_id=JB_project_id AND JB_mail_id=SM_mail_id(+)"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		project_id = ds("PJ_project_id").Value
		project_name = ds("PJ_project_name").Value
		job_name = ds("JB_job_name").Value
		status = ds("JB_status").Value
		subject = ds("SM_subject").value
		start_date = ds("SM_start_date").Value
	End If
End Sub

'=== ���F��ԕ\�� ===
Private Function ApproveStatus(status)
	Select Case status
	Case "0"
		ApproveStatus = "�����F"
	Case "1"
		ApproveStatus = "���F�҂�"
	Case "2"
		ApproveStatus = "���F�ς�"
	End Select
End Function

'=== ���C������ ===
Dim project_id, project_name, job_id, job_name, status, subject, start_date

job_id = Request.QueryString("id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

ReadJob job_id

SetHeaderTitle "�W���u���\��"
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
<form method="post" name="form1">
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
						<td class="m3">�W���u��</td>
						<td class="n3"><%=ReplaceTag(job_name)%></td>
					</tr>
					<tr>
						<td class="m3">���F</td>
						<td class="n3"><%=ApproveStatus(status)%></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="button" name="click" value="�N���b�N�J�E���^" onclick="location.href='click_count.asp'">
	<input type="button" name="mail" value="���[���R���e���c" onclick="location.href='mail_contents.asp'">
	<input type="button" name="send" value=" ���M���� " onclick="location.href='send_condition.asp'">
	<input type="button" value=" ���� " onclick="window.close()">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
