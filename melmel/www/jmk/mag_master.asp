<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�����}�K�}�X�^�ꗗ�\��
'******************************************************

SetHeaderTitle "�����}�K�}�X�^", TC_MASTER
Response.CacheControl = "no-cache"
%>
<%
Dim DB, RS, SQL, line, status, sort, flag1, flag2

Sub TableHeader(text, sno)
  If sno = sort Then
    Writeln "<th><font color='brown'>" & text & "</font></th>"
  Else
    Writeln "<th class='sort' onClick='location.href=""mag_master.asp?sort=" & sno & """'>" & text & "</th>"
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
SQL = "SELECT MG_mag_id,MG_m_name,MG_new_busuu,CASE WHEN ISNULL(MG_shoukai_flag1,0)=1 AND ISNULL(MG_shoukai_flag2,0)<>1 THEN 0 ELSE 1 END,MG_regist_date,MG_last_update,MG_status,MG_shoukai_flag1,MG_shoukai_flag2" & vbCrLf & _
      "FROM T_MELMAG" & vbCrLf & _
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
    <td align="left">�������}�K�̈ꗗ <font size="-1">�i�w�b�_���N���b�N����Ƃ��̍��ڂŃ\�[�g�A�����}�KID���N���b�N����ƏC�����邱�Ƃ��ł��܂��j</font></td>
    <td align="right">�����F<%=RS.RecordCount%></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center" frame="box">
  <tr bgcolor="#FFE0E0">
<%
TableHeader "�����}�KID", 1
TableHeader "�����}�K��", 2
TableHeader "���s����", 3
TableHeader "�Љ�", 4
TableHeader "�o�^��", 5
TableHeader "�ŏI�X�V��", 6
TableHeader "���", 7
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
  Writeln "<td align='center'><a href='mag_master2.asp?id=" & RS("MG_mag_id") & "'>"  & RS("MG_mag_id") & "</a></td>"
  Writeln "<td align='left'>" & ReplaceTag(RS("MG_m_name")) & "</td>"
  Writeln "<td align='right'>" & NumFormat(RS("MG_new_busuu")) & "</td>"
  flag1 = NVL(RS("MG_shoukai_flag1"), False)
  flag2 = NVL(RS("MG_shoukai_flag2"), False)
  If flag1 = True And flag2 = False Then
    Writeln "<td align='center'><nobr><font color='red'>" & TorF(flag1) & "/" & TorF(flag2) & "</font></nobr></td>"
  Else
    Writeln "<td align='center'><nobr>" & TorF(flag1) & "/" & TorF(flag2) & "</nobr></td>"
  End If
  Writeln "<td align='center'>" & DateFormat(RS("MG_regist_date")) & "</td>"
  Writeln "<td align='center'>" & DateFormat(RS("MG_last_update")) & "</td>"
  Select Case RS("MG_status")
  Case 0
    status = "������"
  Case 1
    status = "<font color='orange'>�x�~��</font>"
  Case 2
    status = "<font color='green'>�p��</font>"
  Case 3
    status = "<font color='red'>��~�i�����j</font>"
  Case 4
    status = "<font color='red'>��~�i�L���j</font>"
  Case 5
    status = "<font color='red'>��~�i���e�j</font>"
  Case 8
    status = "<font color='magenta'>���s�ҋx�</font>"
  Case 9
    status = "<font color='blue'>���s�ґމ�</font>"
  Case Else
    status = "�s��"
  End Select
  Writeln "<td align='center'>" & status & "</td>"
  Writeln "</tr>"

  RS.MoveNext
  line = line + 1
Loop
RS.close

Function TorF(flag)
  If flag = True Then
    TorF = "T"
  Else
    TorF = "F"
  End If
End Function
%>

</table>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
