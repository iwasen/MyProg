<!--#INCLUDE FILE="inc/common.asp"-->
<!--#INCLUDE FILE="inc/sub.asp"-->
<!--#INCLUDE FILE="inc/login.asp"-->
<!--#INCLUDE FILE="inc/menu.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�������j���[���
'******************************************************

Const cols = 3
Const rows = 5

ReDim menu(cols, rows), href(cols, rows)

SetHeaderTitle "�����ǃ��j���["
top_link = "<small><font color='blue'>��</font><A href='login/logoff.asp'>���O�I�t</A></small>"

menu(1, 1) = "��W�Ǘ�"
href(1, 1) = "boshuu/index.asp"
menu(1, 2) = "�o�^�Ǘ�"
href(1, 2) = "touroku/index.asp"
menu(1, 3) = "�����o�[����"
href(1, 3) = "search/index.asp"
menu(1, 4) = "�l����"
href(1, 4) = "personal/6-2.asp"
menu(1, 5) = "�����o�[�v���t�B�[��"
href(1, 5) = "profile/profile.asp"

menu(2, 1) = "�v���W�F�N�g�Ǘ�"
href(2, 1) = "project/index.asp"
menu(2, 2) = "�S�z�M����"
href(2, 2) = "zenhaishin/index.asp"
menu(2, 3) = "�r�W�l�X����֔z�M"
href(2, 3) = "business/index.asp"
menu(2, 4) = "�މ�ҊǗ�"
href(2, 4) = "taikai/index.asp"
menu(2, 5) = "�X�P�W���[��"
href(2, 5) = "schedule/index.asp"

menu(3, 1) = "�T�[�o�����e�i���X"
href(3, 1) = "mainte/index.asp"
menu(3, 2) = "���M���O�Ǘ�"
href(3, 2) = "sendlog/index.asp"
menu(3, 3) = "�G���[���O�Ǘ�"
href(3, 3) = "etc/auto_log.asp"
menu(3, 4) = "�N���[���Ǘ�"
href(3, 4) = "claim/index.asp"
menu(3, 5) = "�}�X�^�����e�i���X"
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
