<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/menu.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�T�[�o�����e�i���X���j���[
'******************************************************

Const cols = 3
Const rows = 1

ReDim menu(cols, rows), href(cols, rows)

SetHeaderTitle "�T�[�o�����e�i���X���j���["

menu(1, 1) = "�N���b�N�J�E���^�G�N�X�|�[�g"
href(1, 1) = "cc_exp.asp"
menu(2, 1) = "�N���b�N�����C���|�[�g"
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
