<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�L���^�C�v�}�X�^�폜����
'******************************************************

SetHeaderTitle "�L���^�C�v�}�X�^�폜"
Response.CacheControl = "no-cache"

Dim ds, sql, koukoku_id

koukoku_id = Request.QueryString("id")
If IsEmpty(koukoku_id) Then
	SystemError "empty koukoku_id"
End If

sql = "DELETE T_KOUKOKU_TYPE WHERE KT_koukoku_id='" & koukoku_id & "'"
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
<input type="button" value=" �n�j " onclick="location.href='koukoku.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
