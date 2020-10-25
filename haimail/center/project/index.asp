<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/menu.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:プロジェクト管理メニュー
'******************************************************

Const cols = 3
Const rows = 2

ReDim menu(cols, rows), href(cols, rows)

SetHeaderTitle "プロジェクト管理メニュー"

menu(1, 1) = "プロジェクト進行管理"
href(1, 1) = "pjt_go.asp"
menu(1, 2) = "ジョブ承認処理"
href(1, 2) = "shounin.asp"

menu(2, 1) = "代理店／オペレータ登録"
href(2, 1) = "eigyou.asp"
menu(2, 2) = "企業／担当者登録"
href(2, 2) = "kigyou.asp"

menu(3, 1) = "誕生日メール配信"
href(3, 1) = "../birthday/index.asp"
menu(3, 2) = "アンケートエラーメール"
href(3, 2) = "enq_error.asp"
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
