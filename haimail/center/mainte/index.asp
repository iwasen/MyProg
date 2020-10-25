<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/menu.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:サーバメンテナンスメニュー
'******************************************************

Const cols = 3
Const rows = 1

ReDim menu(cols, rows), href(cols, rows)

SetHeaderTitle "サーバメンテナンスメニュー"

menu(1, 1) = "クリックカウンタエクスポート"
href(1, 1) = "cc_exp.asp"
menu(2, 1) = "クリック履歴インポート"
href(2, 1) = "cc_imp.asp"
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
