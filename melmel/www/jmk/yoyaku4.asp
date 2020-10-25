<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:予約キャンセル処理
'******************************************************

Dim DB, SQL, koukoku_id

koukoku_id = Request.QueryString("id")
If Not IsEmpty(koukoku_id) Then
  Set DB = OpenDB()
  SQL = "DELETE V_KOUKOKU WHERE KK_koukoku_id='" & koukoku_id & "'"
  DB.Execute SQL
End If

Response.Redirect("yoyaku.asp")
%>
