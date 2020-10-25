<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/menu.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:マスターメンテナンスメニュー
'******************************************************

Const cols = 3
Const rows = 4

ReDim menu(cols, rows), href(cols, rows)

SetHeaderTitle "マスターメンテナンスメニュー"

menu(1, 1) = "興味ジャンルマスタ"
href(1, 1) = "kyoumi.asp"
menu(1, 2) = "よく行く店マスタ"
href(1, 2) = "yokuiku.asp"
menu(1, 3) = "どっち派マスタ"
href(1, 3) = "docchi.asp"
menu(1, 4) = "ヘッダ／感想文／フッタ"
href(1, 4) = "header.asp"

menu(2, 1) = "職業マスタ"
href(2, 1) = "shokugyou.asp"
menu(2, 2) = "広告タイプマスタ"
href(2, 2) = "koukoku.asp"
menu(2, 3) = "媒体マスタ"
href(2, 3) = "baitai.asp"
menu(2, 4) = "Webメールドメインマスタ"
href(2, 4) = "mail_domain.asp"

menu(3, 1) = "送信メールテンプレート"
href(3, 1) = "mail_template.asp"
menu(3, 2) = "コントロールマスタ"
href(3, 2) = "control.asp"
menu(3, 3) = "事務局マスタ"
href(3, 3) = "center.asp"
menu(3, 4) = "メールアドレスクリーニング"
href(3, 4) = "cl_list.asp"
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
