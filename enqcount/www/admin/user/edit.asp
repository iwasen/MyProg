<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/header.asp"-->
<%
'******************************************************
' System :�A���P�[�g�W�v�T�[�r�X
' Content:���[�U�o�^���ύX
'******************************************************

'=== ���C������ ===
Dim user_id, sql, rs

set_global "���[�U�o�^���ύX", BACK_TOP

user_id = Request.QueryString("user_id")

sql = "SELECT * FROM t_user WHERE us_user_id=" & sql_number(user_id)
Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
%>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Pragma" content="no-cache">
<title><%=g_title%></title>
<link rel="stylesheet" type="text/css" href="../../css/main.css">
<script type="text/javascript">
<!--
function onLoad_body() {
	var f = document.form1;
	f.mail_addr.focus();
}
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "update":
		return input_check(f);
	case "delete":
		return confirm("���̃��[�U���폜���܂��B��낵���ł����H");
	}
}
function input_check(f) {
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
	return true;
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
			<input class="alpha" type="text" name="mail_addr" size=80 <%=value(rs("us_mail_addr"))%>>
		</td>
	</tr>
	<tr>
		<td class="m1">�p�X���[�h<%=MUST_ITEM%></td>
		<td class="n1">
			<input type="password" name="password" size=20 maxlength=20 <%=value(rs("us_password"))%>>
			<span class="note">�i���p�Q�O�����܂Łj</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���O<%=MUST_ITEM%></td>
		<td class="n1">
			<input class="kanji" type="text" name="name1" size="40" <%=value(rs("us_name1"))%>> ��<br>
			<input class="kanji" type="text" name="name2" size="40" <%=value(rs("us_name2"))%>> ��
		</td>
	</tr>
</table>
<br>
<input type="submit" value="�@�o�^�@" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�@�폜�@" onclick="document.form1.next_action.value='delete'">
<input type="button" value="�L�����Z��" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="user_id" <%=value(user_id)%>>
</form>
</div>

<% page_footer() %>
</body>
</html>
