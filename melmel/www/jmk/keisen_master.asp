<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�L���r���}�X�^�ꗗ�\��
'******************************************************

SetHeaderTitle "�L���r���}�X�^", TC_MASTER
Response.CacheControl = "no-cache"

Dim DB, RS, SQL, line

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT * FROM T_KEISEN ORDER BY KE_keisen_id"
RS.Open SQL, DB, adOpenKeyset, adLockReadOnly
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
<table width="75%">
  <tr>
    <td align="left">���L���r���̈ꗗ <font size="-1">�i�r��ID���N���b�N����ƏC�����邱�Ƃ��ł��܂��j</font></td>
    <td align="right">�����F<%=RS.RecordCount%></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="75%">
  <tr bgcolor="#FFE0E0">
    <th>�r��ID</th>
    <th>�J�n�^�I���r��</th>
  </tr>
<%
line = 1
Do Until RS.EOF
  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If
  Writeln "<td align='center'><a href='keisen_master3.asp?id=" & RS("KE_keisen_id") & "'>"  & RS("KE_keisen_id") & "</a></td>"
  Writeln "<td class='np'>" & ReplaceTag(RS("KE_start_line")) & "<br>" & ReplaceTag(RS("KE_end_line")) & "</td>"

  RS.MoveNext
  line = line + 1
Loop
RS.close
%>

</table>

<form>
<input type="button" value="�V�K�o�^" onClick="location.href='keisen_master2.asp'">
</form>

</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
