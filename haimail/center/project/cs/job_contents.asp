<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����ǁE�c�Ƌ��ʃy�[�W
' Content:�W���u���e��ʏ���
'******************************************************

'=== �W���u���擾 ===
Private Sub ReadJob(job_id)
	Dim sql, ds

	sql = "SELECT PJ_project_id,PJ_project_name,JB_job_name,JB_status,JB_mail_type,JB_job_type,JB_parent_job_id,SM_subject,SM_start_date,SM_mail_format" & vbCrLf & _
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
		mail_type = ds("JB_mail_type").Value
		mail_format = NVL(ds("SM_mail_format").Value, "0")
		job_type = NVL(ds("JB_job_type").Value, "0")
		parent_job_id = ds("JB_parent_job_id").Value
	End If
End Sub

'=== �W���u��ʖ��擾 ===
Private Function JobTypeText(job_type)
	Select Case job_type
	Case "0"
		JobTypeText = "���MJOB"
	Case "1"
		JobTypeText = "�A���P�[�g���X�|���XJOB"
	Case "2"
		JobTypeText = "�N���b�N���X�|���XJOB"
	End Select
End Function

'=== �qJOB�\�� ===
Private Sub ChildJob()
	Dim sql, ds

	sql = "SELECT JB_job_id,JB_job_name from T_JOB where JB_parent_job_id=" & job_id & " ORDER BY JB_job_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If ds.EOF Then
		Response.Write "����"
	Else
		Do Until ds.EOF
			Response.Write ds("JB_job_id").Value & " " & ReplaceTag(ds("JB_job_name").Value) & "<br>"
			ds.MoveNext
		Loop
	End If		
End Sub

'=== �eJOB�\�� ===
Private Sub ParentJob()
	Dim sql, ds

	If IsNull(parent_job_id) Then
		Response.Write "����܂���"
	Else
		sql = "SELECT JB_job_id,JB_job_name from T_JOB where JB_job_id='" & parent_job_id & "'"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds.EOF Then
			Response.Write ds("JB_job_id").Value & " " & ReplaceTag(ds("JB_job_name").Value) & "<br>"
		End If
	End If		
End Sub

'=== ���C������ ===
Dim project_id, project_name, job_id, job_name, status, subject, start_date, back, a_type
Dim mail_type, mail_format, job_type, parent_job_id

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

back = Request.QueryString("back")
If IsEmpty(back) Then
	back = Session("pjt_back")
	If IsEmpty(back) Then
		back = top_page
	End If
Else
	back = "../" & back
	Session("pjt_back") = back
End If

If CheckPermission(PM_REGIST_JOB_A) Then
	a_type = True
ElseIf CheckPermission(PM_REGIST_JOB_B) Then
	a_type = False
Else
	NoPermission
End If

ReadJob job_id

SetHeaderTitle "�W���u���e�o�^"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_approve() {
<%If IsNull(subject) Then%>
	alert("���[���R���e���c��o�^���Ă��������B");
<%ElseIf IsNull(start_date) Then%>
	alert("���M������o�^���Ă��������B");
<%Else%>
	if (confirm("����JOB�̏��F��v�����܂����H"))
		location.href = "approve.asp?job_id=<%=job_id%>";
<%End If%>
}
function OnClick_kaijo() {
	if (confirm("����JOB�̏��F�v�����������܂����H"))
		location.href = "approve2.asp?job_id=<%=job_id%>";
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../../inc/header.asp"-->

<center>
<form method="post" name="form1">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
<%If CheckPermission(PM_REGIST_PROJECT) Then%>
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
<%End If%>
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
						<td class="m3">���[���^�C�v</td>
						<td class="n3"><%=MailTypeText(mail_type)%></td>
					</tr>
					<tr>
						<td class="m3">���[���`��</td>
						<td class="n3"><%=MailFormatText(mail_format)%></td>
					</tr>
					<tr>
						<td class="m3">�W���u���</td>
						<td class="n3"><%=JobTypeText(job_type)%></td>
					</tr>
<%If job_type = "0" Then%>
					<tr>
						<td class="m3">�q���X�|���XJOB</td>
						<td class="n3"><%ChildJob%></td>
					</tr>
<%Else%>
					<tr>
						<td class="m3">�e���MJOB</td>
						<td class="n3"><%ParentJob%></td>
					</tr>
<%End If%>
					<tr>
						<td class="m3">���F</td>
						<td class="n3"><%=ApproveStatus(status)%></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
<%If a_type Then%>
	<input type="button" name="click" value="�N���b�N�J�E���^" onclick="location.href='click_count.asp?job_id=<%=job_id%>'">
	<input type="button" name="mail" value="���[���R���e���c" onclick="location.href='mail_contents.asp?job_id=<%=job_id%>'">
	<input type="button" name="send" value=" ���M���� " onclick="location.href='send_condition.asp?job_id=<%=job_id%>'">
<%	If status = "0" Then%>
	<input type="button" name="approve" value=" ���F�v�� " onclick="OnClick_approve()">
<%	ElseIf status = "1" Then%>
	<input type="button" name="approve" value=" ���F�v����� " onclick="OnClick_kaijo()">
<%	End If%>
<%Else%>
<%	If CheckPermission(PM_JOB_CLICK_COUNTER) Then%>
	<input type="button" name="click" value="�N���b�N�J�E���^" onclick="location.href='click_count.asp?job_id=<%=job_id%>'">
<%	End If%>
	<input type="button" name="mail" value="���[���R���e���c" onclick="location.href='mail_contents.asp?job_id=<%=job_id%>'">
	<input type="button" name="send" value=" ���M���� " onclick="location.href='send_condition.asp?job_id=<%=job_id%>'">
	<input type="button" name="estimate" value=" ���ς� ">
<%End If%>
	<input type="button" value=" �߂� " onclick="location.href='<%=back%>'">
</form>
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
