<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/header.asp"-->
<%
'******************************************************
' System :�A���P�[�g�W�v�T�[�r�X
' Content:���[�U�V�K�o�^
'******************************************************

'=== ���C������ ===
set_global "���[�U�V�K�o�^", BACK_TOP
%>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Pragma" content="no-cache">
<title><%=g_title%></title>
<link rel="stylesheet" type="text/css" href="../../css/main.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.mail_addr.value == "") {
		alert("���[���A�h���X����͂��Ă��������B");
		f.mail_addr.focus();
		return false;
	}
	if (f.password.value == "") {
		alert("�p�X���[�h����͂��Ă��������B");
		f.password.focus();
		return false;
	}
	if (f.name1.value == "") {
		alert("���O����͂��Ă��������B");
		f.name1.focus();
		return false;
	}
	if (f.name2.value == "") {
		alert("���O����͂��Ă��������B");
		f.name2.focus();
		return false;
	}
}
//-->
</script>
</head>
<body onload="document.form1.mail_addr.focus()">
<% page_header() %>

<div align="center">
<form method="post" name="form1" action="update.asp" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>�����[�U������͂��Ă�������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">���[���A�h���X<%=MUST_ITEM%></td>
		<td class="n1" width="80%">
			<input class="alpha" type="text" name="mail_addr" size=80>
		</td>
	</tr>
	<tr>
		<td class="m1">�p�X���[�h<%=MUST_ITEM%></td>
		<td class="n1">
			<input type="password" name="password" size=20 maxlength=20>
			<span class="note">�i���p�Q�O�����܂Łj</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���O<%=MUST_ITEM%></td>
		<td class="n1">
			<input class="kanji" type="text" name="name1" size="40"> ��<br>
			<input class="kanji" type="text" name="name2" size="40"> ��
		</td>
	</tr>
</table>
<br>
<input type="submit" value="�@�o�^�@" onclick="document.form1.next_action.value='new'">
<input type="button" value="�L�����Z��" onclick="history.back()">
<input type="hidden" name="next_action">
</form>
</div>

<% page_footer() %>
</body>
</html>
