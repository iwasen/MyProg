<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:���o�^�����}�K������X�g�ꗗ
'******************************************************

SetHeaderTitle "���o�^�����}�K������X�g", TC_HAKKOUSHA
Response.CacheControl = "no-cache"
%>
<html>
<HEAD>
<META HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<META name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<LINK rel="stylesheet" type="text/css" href="admin.css">
</HEAD>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim DB, RS, SQL, line, max, check

If IsEmpty(Request.QueryString("max")) Then
  max = 0
Else
  max = CLng(Request.QueryString("max"))
End If

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT KM_seq_no,KM_subject,KM_from,KM_date" & vbCrLf & _
      "FROM T_KMAILLOG" & vbCrLf & _
      "WHERE KM_print_flag=0" & vbCrLf & _
      "ORDER BY KM_subject,KM_seq_no"
RS.Open SQL, DB, adOpenKeyset, adLockReadOnly
%>

<table width="100%" border=0 cellpadding=0 cellspacing=0><tr><td>
<form method="post" name="form1" action="kari_print2.asp">

<table width="100%">
  <tr>
    <td align="left">����M�������o�^�����}�K�̈ꗗ <font size="-1">�i������̂��́j</font></td>
    <td align="left">
      �ő�<input type="text" name="max" value="<%=max%>" size=5>��
      <input type="button" value="�ύX" onClick="JavaScript:location.href='kari_print.asp?max=' + document.form1.max.value">
    </td>
    <td align="right">�����F<%=RS.RecordCount%></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor='#FFE0E0'>
    <th>Subject</th>
    <th>From</th>
    <th>Date</th>
    <th>���</th>
  </tr>

<%
line = 1
Do Until RS.EOF
  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If
  Writeln "<td>" & ReplaceTag(RS("KM_subject")) & "</td>"
  Writeln "<td>" & ReplaceTag(RS("KM_from")) & "</td>"
  Writeln "<td align='center'>" & RS("KM_date") & "</td>"
  If line <= max Then
    check = " checked"
  Else
    check = ""
  End If
  Writeln "<td align='center'><input type='checkbox' name='seq_no' value='" & RS("KM_seq_no") & "'" & check & "></td>"
  Writeln "</tr>"

  RS.MoveNext
  line = line + 1
Loop
RS.close
%>

</table>

<center>
<br>
<input type="submit" value="�@����@">
</center>

</form>
</td></tr></table>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
