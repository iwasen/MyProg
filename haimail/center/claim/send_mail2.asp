<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/menu.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�N���[���Ǘ��^���[�����M����
'******************************************************

'=== ���C������ ===
Dim sql, ds, seq_no, subject, from, body
		
SetHeaderTitle "�N���[���Ή����[�����M����"
Response.CacheControl = "no-cache"

seq_no = Request.Form("seq_no")
If IsEmpty(seq_no) Then
	SystemError "empty seq_no"
End If

SendMail Request.Form("to"), Request.Form("from"), Request.Form("subject"), Request.Form("body")
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<p>���[���𑗐M���܂����B</p>
<input type="button" value=" �n�j " onclick="location.href='set_status.asp?seq=<%=seq_no%>&status=1'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
