<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�L���r���}�X�^�X�V����
'******************************************************

SetHeaderTitle "�L���r���}�X�^�X�V", TC_MASTER

Dim DB, RS, SQL, keisen_id

keisen_id = Request.Form("keisen_id")
If Not IsEmpty(keisen_id) Then
  Set DB = OpenDB()
  Set RS = Server.CreateObject("ADODB.RecordSet")

  SQL = "SELECT * FROM T_KEISEN WHERE KE_keisen_id='" & keisen_id & "'"
  RS.Open SQL, DB, adOpenKeyset, adLockPessimistic
  If RS.EOF Then
    RS.AddNew
    RS("KE_keisen_id") = keisen_id
  End If
  RS("KE_start_line") = Request.Form("start_line")
  RS("KE_end_line") = Request.Form("end_line")
  RS.Update
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
<input type="button" value=" �n�j " onClick="location.href='keisen_master.asp'">
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
<%
Else
  Response.Redirect("keisen_master.asp")
End If
%>