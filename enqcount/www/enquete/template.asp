<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/header.asp"-->
<!--#INCLUDE FILE="../inc/list.asp"-->
<!--#INCLUDE FILE="../inc/format.asp"-->
<!--#INCLUDE FILE="../inc/select.asp"-->
<!--#INCLUDE FILE="../inc/login_check.asp"-->
<%
'******************************************************
' System :アンケート集計サービス
' Content:テンプレート選択
'******************************************************

'=== メイン処理 ===
Dim enquete_id, sql, rs, order_by, n, template_id, memo

set_global "出力テンプレート選択", BACK_TOP

enquete_id = Request.QueryString("enquete_id")

'ソート条件
order_by = sort_order(1, 0, "te_template_id/te_title")

sql = "SELECT te_template_id,te_title,te_memo" & _
			" FROM t_template" & _
			" " & order_by
Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
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
		<td class="lt">■出力テンプレート一覧</td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="location.href='list.asp'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <%=value(sort_col)%>>
<input type="hidden" name="sort_dir" <%=value(sort_dir)%>>
<input type="hidden" name="enquete_id" <%=value(enquete_id)%>>
</form>

<table <%=LIST_TABLE%> width="100%">
	<tr class="tch">
<%
		sort_header 1, "ID"
		sort_header 2, "テンプレート名"
		sort_header 0, "備考"
%>
	</tr>
<%
Do Until rs.EOF
	memo = rs("te_memo")
	template_id = rs("te_template_id")
%>
	<tr class="tc<%=n Mod 2%>">
		<td align="center"><a href="../template/edit.asp?enquete_id=<%=enquete_id%>&template_id=<%=template_id%>" title="このテンプレートを修正します"><%=template_id%></a></td>
		<td><a href="<%=template_id%>/set.asp?enquete_id=<%=enquete_id%>&template_id=<%=template_id%>" title="このテンプレートを使用して集計結果を出力します"><%=HTMLEncode(rs("te_title"))%></a></td>
		<td align="center" class="small"><%=HTMLEncode(memo)%></td>
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
