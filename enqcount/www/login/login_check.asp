<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<%
'******************************************************
' System :アンケート集計サービス
' Content:ログインチェック
'******************************************************

Dim login_id, password, sql, rs

login_id = Request.Form("login_id")
password = Request.Form("password")

sql = "SELECT us_user_id,us_mail_addr FROM t_user WHERE us_mail_addr=" & sql_char(login_id) & " AND us_password=" & sql_char(password)
Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
If Not rs.EOF Then
	Response.Cookies("user_login_id") = rs("us_user_id")
	Response.Cookies("user_mail_addr") = rs("us_mail_addr")
	Response.Cookies("user_mail_addr").Expires = DateAdd("m", 1, Now())
	Response.Redirect "../index.asp"
Else
	Response.Cookies("user_login_id") = ""
	Response.Redirect "login_error.asp"
End If
%>
