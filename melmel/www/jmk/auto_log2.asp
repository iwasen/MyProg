<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:自動処理ログ削除処理
'******************************************************

Dim DB, SQL

If Request.QueryString("delete") = "1" Then
  Set DB = OpenDB()
  SQL = "DELETE T_AUTOLOG"
  DB.Execute SQL
End If

Response.Redirect("auto_log.asp")
%>
