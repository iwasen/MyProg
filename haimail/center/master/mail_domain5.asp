<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:Web���[���h���C���}�X�^�폜����
'******************************************************

SetHeaderTitle "Web���[���h���C���}�X�^�폜"
Response.CacheControl = "no-cache"

Dim ds, sql, domain_id

domain_id = Request.QueryString("id")
If IsEmpty(domain_id) Then
	SystemError "empty domain_id"
End If

sql = "DELETE T_MAIL_DOMAIN WHERE MD_domain_id='" & domain_id & "'"
ExecuteSQL(sql)
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
<p>�폜���܂����B</p>
<input type="button" value="�@OK�@" onclick="location.href='mail_domain.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
