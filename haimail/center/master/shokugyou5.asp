<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:職業マスタ削除処理
'******************************************************

SetHeaderTitle "職業マスタ削除"
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
<p>削除しました。</p>
<input type="button" value=" ＯＫ " onclick="location.href='shokugyou.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
