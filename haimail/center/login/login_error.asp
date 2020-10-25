<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:ログインエラー画面処理
'******************************************************

Const g_login_type = "H"

SetHeaderTitle "はいめーる・net 事務局インターフェース"
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
<p>ログインに失敗しました。<br><br>戻るボタンをクリックしてもう一度ＩＤとパスワードを入力してください。</p>
<input type="button" value=" 戻る " onclick="history.back()">
<br>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
