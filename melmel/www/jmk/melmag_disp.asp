<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:��M�����}�K�\������
'******************************************************

SetHeaderTitle "��M�����}�K�\��", TC_HAKKOUSHA
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

<%
Dim DB, RS, SQL, mag_id, line, first

'�p�����[�^�i�����}�KID�j�擾
mag_id = Request.QueryString("id")
If Not IsEmpty(mag_id) Then

  '�{�o�^�����}�K���O�I�[�v��
  Set DB = OpenDB()
  Set RS = Server.CreateObject("ADODB.RecordSet")
  SQL = "SELECT TOP 10 * FROM T_HMAILLOG WHERE HM_mag_id='" & mag_id & "' ORDER BY HM_seq_no DESC"
  RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

  first = True
  Do Until RS.EOF
    If first Then
      Response.Write "<div>" & vbCrLf
      first = False
    Else
      Response.Write "<div class='page'>" & vbCrLf
    End If
    Writeln "<table class='print' border=0 cellspacing=0 cellpadding=0>"
    Writeln "<tr><td>�����}�KID:</td><td>" & RS("HM_mag_id") & "</td></tr>"
    Writeln "<tr><td>Subject:</td><td>" & ReplaceTag(RS("HM_subject")) & "</td></tr>"
    Writeln "<tr><td>From:</td><td>" & ReplaceTag(RS("HM_from")) & "</td></tr>"
    Writeln "<tr><td>Date:</td><td>" & RS("HM_date") & "</td></tr>"
    Writeln "</table>"
    Writeln "<br><xmp class='mail'>" & ReplaceXmp(RS("HM_body")) & "</xmp>"
    Writeln "<hr class='print'>"
    Writeln "</div>"

    RS.MoveNext
  Loop
  RS.Close
End If
%>

</body>
</html>
