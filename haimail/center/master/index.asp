<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/menu.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�}�X�^�[�����e�i���X���j���[
'******************************************************

Const cols = 3
Const rows = 4

ReDim menu(cols, rows), href(cols, rows)

SetHeaderTitle "�}�X�^�[�����e�i���X���j���["

menu(1, 1) = "�����W�������}�X�^"
href(1, 1) = "kyoumi.asp"
menu(1, 2) = "�悭�s���X�}�X�^"
href(1, 2) = "yokuiku.asp"
menu(1, 3) = "�ǂ����h�}�X�^"
href(1, 3) = "docchi.asp"
menu(1, 4) = "�w�b�_�^���z���^�t�b�^"
href(1, 4) = "header.asp"

menu(2, 1) = "�E�ƃ}�X�^"
href(2, 1) = "shokugyou.asp"
menu(2, 2) = "�L���^�C�v�}�X�^"
href(2, 2) = "koukoku.asp"
menu(2, 3) = "�}�̃}�X�^"
href(2, 3) = "baitai.asp"
menu(2, 4) = "Web���[���h���C���}�X�^"
href(2, 4) = "mail_domain.asp"

menu(3, 1) = "���M���[���e���v���[�g"
href(3, 1) = "mail_template.asp"
menu(3, 2) = "�R���g���[���}�X�^"
href(3, 2) = "control.asp"
menu(3, 3) = "�����ǃ}�X�^"
href(3, 3) = "center.asp"
menu(3, 4) = "���[���A�h���X�N���[�j���O"
href(3, 4) = "cl_list.asp"
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
