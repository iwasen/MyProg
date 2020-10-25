<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:ログインエラー画面処理
'******************************************************

Dim g_login_type

g_login_type = Request.QueryString("t")

SetHeaderTitle "マーケティングインターフェース"
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
<input type="button" value="　戻る　" onclick="history.back()">
<br>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
