<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�E�ƃ}�X�^�폜����
'******************************************************

SetHeaderTitle "�E�ƃ}�X�^�폜"
Response.CacheControl = "no-cache"

Dim sql, shokugyou_id

shokugyou_id = Request.QueryString("id")
If IsEmpty(shokugyou_id) Then
	SystemError "empty shokugyou_id"
End If

sql = "DELETE T_SHOKUGYOU WHERE SG_shokugyou_code='" & shokugyou_id & "'"
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
<input type="button" value=" �n�j " onclick="location.href='shokugyou.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
