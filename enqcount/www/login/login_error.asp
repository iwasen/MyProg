<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/header.asp"-->
<%
'******************************************************
' System :アンケート集計サービス
' Content:ログインエラー画面処理
'******************************************************

set_global "メール配信サービス ログイン", BACK_NONE
%>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title><%=g_title%></title>
<link rel="stylesheet" type="text/css" href="../css/main.css">
</head>
<body onLoad="document.all.ok.focus()">

<% page_header() %>

<div align="center">
<p class="msg">ログインに失敗しました。<br><br>戻るボタンをクリックしてもう一度ＩＤとパスワードを入力してください。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="history.back()"></p>
</div>

<% page_footer() %>

</body>
</html>
