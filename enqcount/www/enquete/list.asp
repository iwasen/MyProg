<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/header.asp"-->
<!--#INCLUDE FILE="../inc/list.asp"-->
<!--#INCLUDE FILE="../inc/format.asp"-->
<!--#INCLUDE FILE="../inc/select.asp"-->
<!--#INCLUDE FILE="../inc/login_check.asp"-->
<%
'******************************************************
' System :�A���P�[�g�W�v�T�[�r�X
' Content:�o�^�A���P�[�g�ꗗ�\��
'******************************************************

'=== ���C������ ===
Dim displine, page, order_by, sql, rs, nrow, n

set_global "�o�^�A���P�[�g�ꗗ", BACK_LOGOFF

displine = disp_limit()
page = Request.QueryString("page")

'�\�[�g����
order_by = sort_order(1, 1, "en_enquete_id/en_title/en_redist_date")

sql = "SELECT en_enquete_id,en_user_id,en_title,en_memo,en_redist_date" & _
			" FROM t_enquete" & _
			" WHERE en_user_id=" & Request.Cookies("user_login_id") & _
			" " & order_by
Set rs = CreateRecordset(sql, adOpenKeyset, adLockReadOnly)
nrow = rs.RecordCount
%>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Pragma" content="no-cache">
<title><%=g_title%></title>
<link rel="stylesheet" type="text/css" href="../css/main.css">
<script type="text/javascript">
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
//-->
</script>
</head>
<body>
<% page_header() %>

<div align="center">
<form method="get" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">���o�^�ς݃A���P�[�g�ꗗ</td>
		<td class="lb">
			<input type="button" value="�V�K�o�^" onclick="location.href='new.asp'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>�\���s��<select name="displine" onchange="submit()"><%select_displine(displine)%></select><input type="button" value="�O��" onclick="page.value=<%=page - 1%>;submit()"<%=disabled(page = 0)%>><input type="button" value="����"onclick="page.value=<%=page + 1%>;submit()"<%=disabled(displine = 0 Or nrow <= displine * (page + 1))%>></nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <%=value(sort_col)%>>
<input type="hidden" name="sort_dir" <%=value(sort_dir)%>>
<input type="hidden" name="page" value=0>
</form>

<table <%=LIST_TABLE%> width="100%">
	<tr class="tch">
<%
		sort_header 1, "ID"
		sort_header 2, "�^�C�g��"
		sort_header 3, "�o�^����"
%>
	</tr>
<%
If Not rs.EOF Then
	rs.AbsolutePosition = page * displine + 1
End If
n = 0
Do Until rs.EOF Or n >= displine
%>
	<tr class="tc<%=n Mod 2%>">
		<td align="center"><a href="new.asp?enquete_id=<%=rs("en_enquete_id")%>" title="�񓚃f�[�^���ăA�b�v���[�h���܂�"><%=rs("en_enquete_id")%></a></td>
		<td><a href="template.asp?enquete_id=<%=rs("en_enquete_id")%>" title="�W�v���ʂ��o�͂��܂�"><%=HTMLEncode(rs("en_title"))%></a></td>
		<td align="center"><%=format_datetime(rs("en_redist_date"))%></td>
	</tr>
<%
	n = n + 1
	rs.MoveNext
Loop
%>
</table>
</div>

<% page_footer() %>
</body>
</html>
