<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�W���u���F�҂���������
'******************************************************

'=== JOB��DB�ɓo�^ ===
Private Sub ShouninJob(job_id)
	Dim sql, ds

	sql = "SELECT JB_mail_id,JB_status FROM T_JOB WHERE JB_job_id=" & job_id
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If Not ds.EOF Then
		ds.Edit
		ds("JB_status").Value = "0"
		ds.Update
	Else
		SystemError "not found job_id"
	End If
End Sub

'=== ���C������ ===
Dim job_id

SetHeaderTitle "�W���u���F�҂�����"

job_id = Request.Form("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

ShouninJob job_id
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
<p>�W���uID�F<%=job_id%> �̏��F�҂����������܂����B</p>
<input type="button" value=" OK " onclick="location.href='shounin.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
