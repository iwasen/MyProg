<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�\��L�����Z������
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
