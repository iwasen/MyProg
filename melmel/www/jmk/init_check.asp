<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�{�o�^�����`�F�b�N�ꗗ�\��
'******************************************************

SetHeaderTitle "�{�o�^�����`�F�b�N", TC_HAKKOUSHA
Response.CacheControl = "no-cache"
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

<%
Dim DB, RS, SQL, line

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT MG_mag_id,MG_m_name,MM_h_name,MM_mail_adr,MG_regist_date" & vbCrLf & _
      "FROM V_MELMAG" & vbCrLf & _
      "WHERE COALESCE(MG_kakunin_flag,0)=0 Or MG_shuu_keisuu IS NULL" & vbCrLf & _
      "ORDER BY MG_mag_id"
RS.Open SQL, DB, adOpenKeyset, adLockReadOnly
%>

<table width="100%">
  <tr>
    <td align="left">���{�o�^��A�����`�F�b�N����Ă��Ȃ������}�K�̈ꗗ <font size="-1">�i�����}�KID���N���b�N����ƃ����}�K���̏C����ʂ��\������܂��j</font></td>
    <td align="right">�����F<%=RS.RecordCount%></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor="#FFE0E0">
    <th>�����}�KID</th>
    <th>�����}�K��</th>
    <th>���s�Җ�</th>
    <th>e-mail�A�h���X</th>
    <th>�o�^��</th>
  </tr>

<%
line = 1
Do Until RS.EOF
  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If
  Writeln "<td align='center'><a href='init_check2.asp?id=" & RS("MG_mag_id") & "'>"  & RS("MG_mag_id") & "</a></td>"
  Writeln "<td>" & ReplaceTag(RS("MG_m_name")) & "</td>"
  Writeln "<td>" & ReplaceTag(RS("MM_h_name")) & "</td>"
  Writeln "<td>" & ReplaceTag(RS("MM_mail_adr")) & "</td>"
  Writeln "<td align='center'>" & DateFormat(RS("MG_regist_date")) & "</td>"
  Writeln "</tr>"

  RS.MoveNext
  line = line + 1
Loop
RS.close
%>

</table>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
