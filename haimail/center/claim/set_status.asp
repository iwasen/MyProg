<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:クレーム管理／状態設定処理
'******************************************************

'=== メイン処理 ===
Dim sql, ds, seq_no, status

Response.CacheControl = "no-cache"

seq_no = Request.QueryString("seq")
If IsEmpty(seq_no) Then
	SystemError "empty seq_no"
End If
status = Request.QueryString("status")

sql = "UPDATE T_CLAIM_MAIL SET CA_status='" & status & "' WHERE CA_seq_no=" & seq_no
ExecuteSQL sql

Response.Redirect "list.asp?st=0"
%>