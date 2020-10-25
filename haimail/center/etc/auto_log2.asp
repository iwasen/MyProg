<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:自動処理ログ削除処理
'******************************************************

Dim sql

If Request.QueryString("delete") = "1" Then
	sql = "DELETE T_AUTOLOG"
	ExecuteSQL sql
End If

Response.Redirect("auto_log.asp")
%>
