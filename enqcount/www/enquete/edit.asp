<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/header.asp"-->
<!--#INCLUDE FILE="../inc/list.asp"-->
<!--#INCLUDE FILE="../inc/login_check.asp"-->
<%
'******************************************************
' System :アンケート集計サービス
' Content:カラム情報設定
'******************************************************

'=== 設問種別選択 ===
Sub select_question_type(question_type)
	echo "<option value=''>- 未設定 -</option>"
	echo "<option " & value_selected("SA", question_type) & ">単一回答(SA)</option>"
	echo "<option " & value_selected("MA", question_type) & ">複数回答(MA)</option>"
	echo "<option " & value_selected("FA", question_type) & ">自由回答(FA)</option>"
	echo "<option " & value_selected("DT", question_type) & ">日付(DT)</option>"
	echo "<option " & value_selected("ID", question_type) & ">回答者ID(ID)</option>"
End Sub

'=== メイン処理 ===
Dim enquete_id, sql, rs, n, column_no

set_global "カラム情報設定", BACK_TOP

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
		<td class="lt">■カラム情報</td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="location.href='list.asp'">
		</td>
	</tr>
</table>

<form method="post" name="form1" action="update.asp">
<table <%=LIST_TABLE%> width="100%" class="small">
	<tr class="tch">
<%
		sort_header 0, "ｶﾗﾑNo."
		sort_header 0, "カラム名"
		sort_header 0, "種別"
		sort_header 0, "設問文"
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
<input type="submit" value="　保存　">
<input type="reset" value="リセット">
<input type="hidden" name="enquete_id" value="<%=enquete_id%>">
</form>
</div>

<% page_footer() %>
</body>
</html>
