<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/header.asp"-->
<!--#INCLUDE FILE="../inc/login_check.asp"-->
<%
'******************************************************
' System :�A���P�[�g�W�v�T�[�r�X
' Content:�e���v���[�g�ύX
'******************************************************

'=== ���C������ ===
Dim template_id, sql, rs, title, memo, filename

set_global "�e���v���[�g�ύX", BACK_TOP

template_id = Request.QueryString("template_id")
sql = "SELECT te_template_id,te_title,te_memo,te_filename FROM t_template WHERE te_template_id=" & sql_number(template_id)
Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
If Not rs.EOF Then
	title = rs("te_title")
	memo = rs("te_memo")
	filename = rs("te_filename")
End If
%>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Pragma" content="no-cache">
<title><%=g_title%></title>
<link rel="stylesheet" type="text/css" href="../css/main.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.title.value == "") {
		alert("�e���v���[�g��<����͂��Ă��������B");
		f.title.focus();
		return false;
	}
	return true;
}
//-->
</script>
</head>
<body>
<% page_header() %>

<div align="center">
<form method="post" name="form1" action="update.asp" ENCtype="multipart/form-data" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>���e���v���[�g������͂��Ă�������</td>
	</tr>
	<tr>
		<td class="m1">�e���v���[�g��<%=MUST_ITEM%></td>
		<td class="n1">
			<input type="text" name="title" size=80 <%=value(title)%>>
		</td>
	</tr>
	<tr>
		<td class="m1">���l��</td>
		<td class="n1">
			<textarea name="memo" cols=60 rows=5><%=HTMLEncode(memo)%></textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">�e���v���[�g�t�@�C��</td>
		<td class="n1">
			<input type="file" name="template_file" size=80><br>
			<span class="note">�i�e���v���[�g�t�@�C�����X�V���Ȃ��ꍇ�͓��͂���K�v�͂���܂���j</span>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="�@�o�^�@" onclick="document.form1.next_action.value='new'">
<input type="button" value="�_�E�����[�h" onclick="window.open('<%=filename%>', '_blank')">
<input type="button" value="�L�����Z��" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="template_id" value="<%=template_id%>">
<input type="hidden" name="enquete_id" value="<%=Request.QueryString("enquete_id")%>">
</form>
</div>

<% page_footer() %>
</body>
</html>
