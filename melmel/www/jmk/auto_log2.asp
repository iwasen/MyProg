<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�����������O�폜����
'******************************************************

Dim DB, SQL

If Request.QueryString("delete") = "1" Then
  Set DB = OpenDB()
  SQL = "DELETE T_AUTOLOG"
  DB.Execute SQL
End If

Response.Redirect("auto_log.asp")
%>
