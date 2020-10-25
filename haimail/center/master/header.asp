<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/menu.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:ヘッダ，フッタ，感想文メニュー
'******************************************************

Const cols = 3
Const rows = 1

ReDim menu(cols, rows), href(cols, rows)

SetHeaderTitle "ヘッダ，フッタ，感想文メニュー"

menu(1, 1) = "テキスト用"
href(1, 1) = "header_text.asp"

menu(2, 1) = "ＨＴＭＬ用"
href(2, 1) = "header_html.asp"

menu(3, 1) = ""
href(3, 1) = ""
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<%
DispMenu cols, rows, menu, href
%>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
