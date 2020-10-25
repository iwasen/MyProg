<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:誕生日メール削除処理
'******************************************************

'=== メイン処理 ===
Dim sql, ds
Dim seq_no

seq_no = Request.QueryString("id")
If IsEmpty(seq_no) Then
	Response.Redirect "index.asp"
End If

sql = "DELETE T_BIRTHDAY_MAIL WHERE BM_seq_no=" & seq_no
ExecuteSQL sql

Response.Redirect "index.asp"
%>
