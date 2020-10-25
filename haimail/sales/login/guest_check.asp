<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login_check.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:ゲストログインチェック処理
'******************************************************

Dim login_id, password

login_id = Request.Form("login_id")
password = Request.Form("password")

If IsEmpty(login_id) Then
	Response.Redirect "guest.asp"
End If

If LoginCheck(LOGIN_GUEST, Trim(login_id), Trim(password), "0") Then
	Response.Redirect "../top/index.asp"
Else
	Response.Redirect "login_error.asp?t=G"
End If
%>