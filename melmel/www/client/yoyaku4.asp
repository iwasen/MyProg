<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
'******************************************************
' System :めるめる・net広告主用ページ
' Content:予約キャンセル処理
'******************************************************

Dim DB, SQL, koukoku_id
Dim client_id

client_id = Session("client_id")
If IsEmpty(client_id) Then
  Session("client_next") = "yoyaku.asp"
  Response.Redirect "login.asp"
End If

koukoku_id = Request.QueryString("id")
If Not IsEmpty(koukoku_id) Then
  Set DB = OpenDB()
  SQL = "DELETE V_KOUKOKU WHERE KK_koukoku_id='" & koukoku_id & "' AND KK_client_id='" & client_id & "'"
  DB.Execute SQL
End If

Response.Redirect("yoyaku.asp")
%>
