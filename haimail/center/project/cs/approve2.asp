<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<!--#INCLUDE FILE="approve_sub.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����ǁE�c�Ƌ��ʃy�[�W
' Content:�W���u���F�v����������ʏ���
'******************************************************

'=== ���F�v������ ===
Private Sub ApproveCancel(job_id)
	Dim sql

	sql = "UPDATE T_JOB SET JB_status='0' WHERE JB_job_id=" & job_id & " AND JB_status='1'"
	ExecuteSQL sql
End Sub

'=== ���C������ ===
Dim job_id

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

'�ύX�����`�F�b�N
'If system_id = "sales" Then
'	If Not LimitCheck(job_id) Then
'		Response.Redirect "approve2_err.asp?job_id=" & job_id
'	End If
'End If

ApproveCancel job_id

SetHeaderTitle "���F�v��������"
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
<p>�W���uID�F<%=job_id%> �̏��F�v�����������܂����B</p>
<input type="button" value="�@OK�@" onclick="location.href='job_contents.asp?job_id=<%=job_id%>'">
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
