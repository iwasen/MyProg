<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:���M���[���e���v���[�g�c�a�X�V����
'******************************************************

SetHeaderTitle "���M���[���e���v���[�g�X�V", TC_MASTER

Dim DB, RS, SQL, id

id = Request.Form("id")
If Not IsEmpty(id) Then
  Set DB = OpenDB()
  Set RS = Server.CreateObject("ADODB.RecordSet")

  SQL = "SELECT * FROM T_SENDMAIL WHERE SM_id='" & id & "'"
  RS.Open SQL, DB, adOpenKeyset, adLockPessimistic
  If Not RS.EOF Then
    RS("SM_subject") = Request.Form("subject")
    RS("SM_from") = Request.Form("from")
    RS("SM_body") = Request.Form("body")
    RS.Update
  End If
  RS.Close
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<center>
<p>�X�V���܂����B</p>
<input type="button" value=" �n�j " onClick="location.href='mailtemp_master.asp'">
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
<%
Else
  Response.Redirect("index.asp")
End If
%>