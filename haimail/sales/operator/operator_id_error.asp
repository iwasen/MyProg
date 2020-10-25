<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:オペレータ情報エラー処理
'******************************************************

'=== メイン処理 ===
SetHeaderTitle "オペレータIDエラー"
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
<p>入力されたオペレータIDはすでに登録されています。</p>
<input type="button" value=" 戻る " onclick="history.back()">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
