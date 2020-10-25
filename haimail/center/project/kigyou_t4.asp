<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:担当者情報削除処理
'******************************************************

SetHeaderTitle "担当者情報削除"

Dim ds, sql, tantousha_id

tantousha_id = Request.QueryString("id")
If IsEmpty(tantousha_id) Then
	SystemError "empty tantousha_id"
End If

sql = "DELETE T_TANTOUSHA WHERE TS_tantousha_id='" & tantousha_id & "'"
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
<input type="button" value=" ＯＫ " onclick="location.href='kigyou.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
