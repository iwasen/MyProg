<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:��ԕύX��ʏ���
'******************************************************

'=== JOB�𖢏��F��ԂɕύX ===
Private Sub SetStatus(job_id)
	Dim sql, ds

	sql = "SELECT JB_parent_job_id,JB_mail_id,JB_status FROM T_JOB WHERE JB_job_id=" & job_id
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If Not ds.EOF Then
		ds.Edit
		ds("JB_status").Value = "0"
		ds.Update

		sql = "UPDATE T_SEND_MAIL SET SM_permit='0',SM_notice_flag='0' WHERE SM_mail_id='" & ds("JB_mail_id").Value & "'"
		ExecuteSQL sql
	Else
		SystemError "not found job_id"
	End If
End Sub

'=== ���C������ ===
Dim job_id

SetHeaderTitle "�W���u���F��ԍX�V"

job_id = Request.Form("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

SetStatus job_id
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
<p>�W���uID�F<%=job_id%> �𖢏��F��ԂɕύX���܂����B</p>
<input type="button" value=" OK " onclick="location.href='pjt_go.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
