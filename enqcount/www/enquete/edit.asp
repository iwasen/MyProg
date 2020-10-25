<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/header.asp"-->
<!--#INCLUDE FILE="../inc/list.asp"-->
<!--#INCLUDE FILE="../inc/login_check.asp"-->
<%
'******************************************************
' System :�A���P�[�g�W�v�T�[�r�X
' Content:�J�������ݒ�
'******************************************************

'=== �ݖ��ʑI�� ===
Sub select_question_type(question_type)
	echo "<option value=''>- ���ݒ� -</option>"
	echo "<option " & value_selected("SA", question_type) & ">�P���(SA)</option>"
	echo "<option " & value_selected("MA", question_type) & ">������(MA)</option>"
	echo "<option " & value_selected("FA", question_type) & ">���R��(FA)</option>"
	echo "<option " & value_selected("DT", question_type) & ">���t(DT)</option>"
	echo "<option " & value_selected("ID", question_type) & ">�񓚎�ID(ID)</option>"
End Sub

'=== ���C������ ===
Dim enquete_id, sql, rs, n, column_no

set_global "�J�������ݒ�", BACK_TOP

enquete_id = Request.QueryString("enquete_id")

sql = "SELECT qu_enquete_id,qu_column_no,qu_question_name,qu_question_type,qu_question_text" & _
			" FROM t_question" & _
			" WHERE qu_enquete_id=" & enquete_id & _
			" ORDER BY qu_column_no"
Set rs = CreateRecordset(sql, adOpenKeyset, adLockReadOnly)
%>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Pragma" content="no-cache">
<title><%=g_title%></title>
<link rel="stylesheet" type="text/css" href="../css/main.css">
<script type="text/javascript">
<!--
//-->
</script>
</head>
<body>
<% page_header() %>

<div align="center">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">���J�������</td>
		<td class="lb">
			<input type="button" value="�@�߂�@" onclick="location.href='list.asp'">
		</td>
	</tr>
</table>

<form method="post" name="form1" action="update.asp">
<table <%=LIST_TABLE%> width="100%" class="small">
	<tr class="tch">
<%
		sort_header 0, "���No."
		sort_header 0, "�J������"
		sort_header 0, "���"
		sort_header 0, "�ݖ╶"
%>
	</tr>
<%
n = 0
Do Until rs.EOF
	column_no = rs("qu_column_no")
%>
	<tr class="tc<%=n Mod 2%>">
		<td align="center"><%=column_no%></td>
		<td><input type="text" name="name<%=column_no%>" size=30 <%=value(rs("qu_question_name"))%>></td>
		<td><select name="type<%=column_no%>"><%select_question_type rs("qu_question_type")%></select></td>
		<td><textarea name="text<%=column_no%>" cols=60 rows=2><%=HTMLEncode(rs("qu_question_text"))%></textarea></td>
	</tr>
<%
	n = n + 1
	rs.MoveNext
Loop
%>
</table>
<br>
<input type="submit" value="�@�ۑ��@">
<input type="reset" value="���Z�b�g">
<input type="hidden" name="enquete_id" value="<%=enquete_id%>">
</form>
</div>

<% page_footer() %>
</body>
</html>
