<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:システムエラー表示
'******************************************************

SetHeaderTitle "システムエラー"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<p>システムエラーが発生したため、処理を続行できません。</p>
<input type="button" value="　戻る　" onclick="history.back()">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
