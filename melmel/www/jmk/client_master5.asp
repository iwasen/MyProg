<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�N���C�A���g�폜����
'******************************************************

SetHeaderTitle "�N���C�A���g�폜", TC_MASTER

Dim DB, SQL, client_id

client_id = Request.QueryString("id")
If Not IsEmpty(client_id) Then
  Set DB = OpenDB()
  SQL = "DELETE T_CLIENT WHERE CM_client_id='" & client_id & "'"
  DB.Execute SQL
%>
<html>
<HEAD>
<META HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<META name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<TITLE><%=title_text%></TITLE>
<LINK rel="stylesheet" type="text/css" href="admin.css">
</HEAD>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<center>
<p>
�폜���܂����B
</p>
<input type="button" value=" �n�j " onClick="location.href='client_master.asp'">
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
<%
Else
  Response.Redirect("client_master.asp")
End If
%>