<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/menu.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�v���W�F�N�g�Ǘ����j���[
'******************************************************

Const cols = 3
Const rows = 2

ReDim menu(cols, rows), href(cols, rows)

SetHeaderTitle "�v���W�F�N�g�Ǘ����j���["

menu(1, 1) = "�v���W�F�N�g�i�s�Ǘ�"
href(1, 1) = "pjt_go.asp"
menu(1, 2) = "�W���u���F����"
href(1, 2) = "shounin.asp"

menu(2, 1) = "�㗝�X�^�I�y���[�^�o�^"
href(2, 1) = "eigyou.asp"
menu(2, 2) = "��Ɓ^�S���ғo�^"
href(2, 2) = "kigyou.asp"

menu(3, 1) = "�a�������[���z�M"
href(3, 1) = "../birthday/index.asp"
menu(3, 2) = "�A���P�[�g�G���[���[��"
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
