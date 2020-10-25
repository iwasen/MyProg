<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/header.asp"-->
<!--#INCLUDE FILE="../../inc/list.asp"-->
<!--#INCLUDE FILE="../../inc/format.asp"-->
<%
'******************************************************
' System :アンケート集計サービス
' Content:ユーザ一覧
'******************************************************

'=== メイン処理 ===
Dim order_by, sql, rs, n

set_global "登録ユーザ一覧", BACK_NONE

'ソート条件
order_by = sort_order(1, 0, "us_mail_addr/us_name1,us_name2/us_regist_date")

sql = "SELECT us_user_id,us_mail_addr,us_password,us_name1,us_name2,us_regist_date" & _
			" FROM t_user" & _
			" " & order_by
Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
%>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Pragma" content="no-cache">
<title><%=g_title%></title>
<link rel="stylesheet" type="text/css" href="../../css/main.css">
</head>
<body>
<% page_header() %>

<div align="center">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■登録ユーザ一覧</td>
		<td class="lb">
			<input type="button" value="新規登録" onclick="location.href='new.asp'">
		</td>
	</tr>
</table>

<table <%=LIST_TABLE%> width="100%">
	<tr class="tch">
<%
		sort_header 1, "メールアドレス"
		sort_header 2, "名前"
		sort_header 3, "登録日時"
%>
	</tr>
<%
n = 0
Do Until rs.EOF
%>
	<tr class="tc<%=n Mod 2%>">
		<td><a href="edit.asp?user_id=<%=rs("us_user_id")%>" title="ユーザ情報を表示・変更します"><%=HTMLEncode(rs("us_mail_addr"))%></a></td>
		<td align="center"><%=HTMLEncode(rs("us_name1") & " " & rs("us_name2"))%></td>
		<td align="center"><%=format_date(rs("us_regist_date"))%></td>
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
