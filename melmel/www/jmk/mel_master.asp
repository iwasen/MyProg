<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:���s�҃}�X�^�ꗗ�\��
'******************************************************

SetHeaderTitle "���s�҃}�X�^", TC_MASTER
Response.CacheControl = "no-cache"

Dim DB, RS, SQL, line, status, sort

Sub TableHeader(text, sno)
  If sno = sort Then
    Writeln "<th><font color='brown'>" & text & "</font></th>"
  Else
    Writeln "<th class='sort' onClick='location.href=""mel_master.asp?sort=" & sno & """'>" & text & "</th>"
  End If
End Sub

sort = Request.QueryString("sort")
If IsEmpty(sort) Then
  sort = 1
Else
  sort = CInt(sort)
End If

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT MM_mel_id,MM_password,MM_h_name,MM_mail_adr,MM_regist_date,MM_status" & vbCrLf & _
      "FROM T_MELMEL" & vbCrLf & _
      "ORDER BY " & sort
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

<table align="center" width="100%">
  <tr>
    <td align="left">�����s�҂̈ꗗ <font size="-1">�i�w�b�_���N���b�N����Ƃ��̍��ڂŃ\�[�g�A���s��ID���N���b�N����ƏC�����邱�Ƃ��ł��܂��j</font></td>
    <td align="right">�����F<%=RS.RecordCount%></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor="#FFE0E0">
<%
TableHeader "���s��ID", 1
TableHeader "�p�X���[�h", 2
TableHeader "���s�Җ�", 3
TableHeader "e-mail�A�h���X", 4
TableHeader "�o�^��", 5
TableHeader "���", 6
%>
  </tr>
<%
line = 1
Do Until RS.EOF
  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If
  Writeln "<td align='center'><a href='mel_master2.asp?id=" & RS("MM_mel_id") & "'>"  & RS("MM_mel_id") & "</a></td>"
  Writeln "<td align='left'>" & RS("MM_password") & "</td>"
  Writeln "<td align='left'>" & ReplaceTag(RS("MM_h_name")) & "</td>"
  Writeln "<td align='left'>" & ReplaceTag(RS("MM_mail_adr")) & "</td>"
  Writeln "<td align='center'>" & DateFormat(RS("MM_regist_date")) & "</td>"
  Select Case RS("MM_status")
  Case 0
    status = "������"
  Case 1
    status = "<font color='red'>�ꎞ��~</font>"
  Case 8
    status = "<font color='magenta'>�x�</font>"
  Case 9
    status = "<font color='blue'>�މ�</font>"
  Case Else
    status = "�s��"
  End Select
  Writeln "<td align='center'>" & status & "</td>"
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
