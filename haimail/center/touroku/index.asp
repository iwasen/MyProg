<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/menu.asp"-->
<%
'******************************************************
' System :อข฿[้Enetฑวpy[W
' Content:o^วj[
'******************************************************

Const cols = 3
Const rows = 1

ReDim menu(cols, rows), href(cols, rows)

SetHeaderTitle "o^วj["

menu(1, 1) = "G[[`FbN"
href(1, 1) = "error_mail.asp"

menu(2, 1) = "o^า\ฆ"
href(2, 1) = "number.asp"

menu(3, 1) = "o^าฺ"
href(3, 1) = "output.asp"
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
