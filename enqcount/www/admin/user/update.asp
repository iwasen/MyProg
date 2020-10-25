<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/header.asp"-->
<%
'******************************************************
' System :アンケート集計サービス
' Content:ユーザ情報更新処理
'******************************************************

'=== メイン処理 ===
Dim enquete_id, sql, msg

set_global "ユーザ情報更新", BACK_TOP

Select Case Request.Form("next_action")
Case "new"
	sql = "INSERT INTO t_user (us_mail_addr,us_password,us_name1,us_name2) VALUES (" & _
				sql_char(Request.Form("mail_addr")) & "," & _
				sql_char(Request.Form("password")) & "," & _
				sql_char(Request.Form("name1")) & "," & _
				sql_char(Request.Form("name2")) & ")"
	ExecuteSQL sql

	msg = "ユーザを登録しました。"
Case "update"
	sql = "UPDATE t_user SET " & _
				"us_mail_addr=" & sql_char(Request.Form("mail_addr")) & "," & _
				"us_password=" & sql_char(Request.Form("password")) & "," & _
				"us_name1=" & sql_char(Request.Form("name1")) & "," & _
				"us_name2=" & sql_char(Request.Form("name2")) & _
				" WHERE us_user_id=" & sql_number(Request.Form("user_id"))
	ExecuteSQL sql

	msg = "ユーザ情報を更新しました。"
Case "delete"
	sql = "DELETE FROM t_user WHERE us_user_id=" & sql_number(Request.Form("user_id"))
	ExecuteSQL sql

	msg = "ユーザを削除しました。"
Case Else
	Response.Redirect "list.php"
End Select
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
<p class="msg"><%=msg%></p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='list.asp'"></p>
</div>

<% page_footer() %>
</body>
</html>
