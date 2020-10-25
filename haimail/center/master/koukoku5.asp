<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:広告タイプマスタ削除処理
'******************************************************

SetHeaderTitle "広告タイプマスタ削除"
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
<p>削除しました。</p>
<input type="button" value=" ＯＫ " onclick="location.href='koukoku.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
