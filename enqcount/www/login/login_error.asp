<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/header.asp"-->
<%
'******************************************************
' System :�A���P�[�g�W�v�T�[�r�X
' Content:���O�C���G���[��ʏ���
'******************************************************

set_global "���[���z�M�T�[�r�X ���O�C��", BACK_NONE
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
<p class="msg">���O�C���Ɏ��s���܂����B<br><br>�߂�{�^�����N���b�N���Ă�����x�h�c�ƃp�X���[�h����͂��Ă��������B</p>
<p><input type="button" id="ok" value="�@�߂�@" onclick="history.back()"></p>
</div>

<% page_footer() %>

</body>
</html>
