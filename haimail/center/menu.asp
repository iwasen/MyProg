<!--#INCLUDE FILE="inc/common.asp"-->
<!--#INCLUDE FILE="inc/sub.asp"-->
<!--#INCLUDE FILE="inc/login.asp"-->
<!--#INCLUDE FILE="inc/menu.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:処理メニュー画面
'******************************************************

Const cols = 3
Const rows = 5

ReDim menu(cols, rows), href(cols, rows)

SetHeaderTitle "事務局メニュー"
top_link = "<small><font color='blue'>■</font><A href='login/logoff.asp'>ログオフ</A></small>"

menu(1, 1) = "募集管理"
href(1, 1) = "boshuu/index.asp"
menu(1, 2) = "登録管理"
href(1, 2) = "touroku/index.asp"
menu(1, 3) = "メンバー検索"
href(1, 3) = "search/index.asp"
menu(1, 4) = "個人検索"
href(1, 4) = "personal/6-2.asp"
menu(1, 5) = "メンバープロフィール"
href(1, 5) = "profile/profile.asp"

menu(2, 1) = "プロジェクト管理"
href(2, 1) = "project/index.asp"
menu(2, 2) = "全配信処理"
href(2, 2) = "zenhaishin/index.asp"
menu(2, 3) = "ビジネス定期便配信"
href(2, 3) = "business/index.asp"
menu(2, 4) = "退会者管理"
href(2, 4) = "taikai/index.asp"
menu(2, 5) = "スケジュール"
href(2, 5) = "schedule/index.asp"

menu(3, 1) = "サーバメンテナンス"
href(3, 1) = "mainte/index.asp"
menu(3, 2) = "送信ログ管理"
href(3, 2) = "sendlog/index.asp"
menu(3, 3) = "エラーログ管理"
href(3, 3) = "etc/auto_log.asp"
menu(3, 4) = "クレーム管理"
href(3, 4) = "claim/index.asp"
menu(3, 5) = "マスタメンテナンス"
href(3, 5) = "master/index.asp"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="haimail.css">
</head>
<body>

<!--#INCLUDE FILE="inc/header.asp"-->

<%
DispMenu cols, rows, menu, href
%>

<!--#INCLUDE FILE="inc/footer.asp"-->

</body>
</html>
