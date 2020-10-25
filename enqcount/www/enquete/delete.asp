<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/header.asp"-->
<!--#INCLUDE FILE="../inc/login_check.asp"-->
<%
'******************************************************
' System :アンケート集計サービス
' Content:アンケート削除
'******************************************************

'=== メイン処理 ===
Dim enquete_id, sql

set_global "登録アンケート削除", BACK_TOP

enquete_id = Request.QueryString("enquete_id")

If enquete_id <> "" Then
	BeginTrans

	sql = "DELETE FROM t_enquete WHERE en_enquete_id=" & sql_number(enquete_id)
	ExecuteSQL sql

	sql = "DELETE FROM t_question WHERE qu_enquete_id=" & sql_number(enquete_id)
	ExecuteSQL sql

	sql = "DELETE FROM t_question_sel WHERE qs_enquete_id=" & sql_number(enquete_id)
	ExecuteSQL sql

	sql = "DELETE FROM t_answer WHERE an_enquete_id=" & sql_number(enquete_id)
	ExecuteSQL sql

	sql = "DELETE FROM t_answer_sel WHERE as_enquete_id=" & sql_number(enquete_id)
	ExecuteSQL sql

	CommitTrans
End If
%>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Pragma" content="no-cache">
<title><%=g_title%></title>
<link rel="stylesheet" type="text/css" href="../css/main.css">
</head>
<body>
<% page_header() %>

<div align="center">

<p>登録アンケートを削除しました。</p>
<form>
<input type="button" value="　戻る　" onclick="location.href='list.asp'">
</form>

</div>

<% page_footer() %>
</body>
</html>
