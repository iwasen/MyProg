<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�f�ڎ��ѕ񍐏�����ꗗ
'******************************************************

SetHeaderTitle "�f�ڎ��ѕ񍐏�", TC_KOUKOKU
Response.CacheControl = "no-cache"

Dim DB, RS, SQL, SUB1, SUB2, line, koukoku_id

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")

SUB1 = "SELECT KK_koukoku_id AS S1_koukoku_id FROM V_KOUKOKU WHERE (KK_status=3 OR KK_status=4) AND KK_tsuika_no IS NULL"

SQL = "SELECT KK_koukoku_id,KK_koukoku_name,KK_client_name,KK_company,KK_koukoku_shuu,KK_busuu,KK_status,KK_tsuika_id,keisai_busuu" & vbCrLf & _
      "FROM ((" & SUB1 & ") AS SUB1 INNER JOIN V_KOUKOKU ON S1_koukoku_id=ISNULL(KK_tsuika_id,KK_koukoku_id)) " & vbCrLf & _
      " LEFT OUTER JOIN V_KEISAI_BUSUU ON KK_koukoku_id=koukoku_id" & vbCrLf & _
      "ORDER BY ISNULL(KK_tsuika_id,KK_koukoku_id),KK_koukoku_id"
RS.Open SQL, DB, adOpenStatic, adLockReadOnly
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="admin.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_shuuryou(koukoku_id) {
  if (confirm("���̍L�����I����Ԃɐݒ肵�܂��B��낵���ł����H"))
    location.href = "report3.asp?id=" + koukoku_id
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<table width="100%" border=0 cellpadding=0 cellspacing=0><tr><td>
<form>

<table width="100%">
  <tr>
    <td align="left">���f�ڈ˗��ς݂̍L���ꗗ <font size="-1">�i�L��ID���N���b�N����ƌf�ڎ��ѕ񍐏����쐬���܂��j</font></td>
    <td align="right">�����F<%=RS.RecordCount%></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor="#FFE0E0">
    <th>�L��ID</th>
    <th>�L����</th>
    <th>�L���˗��喼</th>
    <th>�g�D��</th>
    <th>�f�ڏT</th>
    <th>�f�ڕ���</th>
    <th>�ۏؕ���</th>
    <th>�I��</th>
  </tr>

<%
line = 1
Do Until RS.EOF
  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If
  koukoku_id = RS("KK_koukoku_id")

  If IsNull(RS("KK_tsuika_id")) Then
    Writeln "<td align='center'><a href='report2.asp?id=" & koukoku_id & "'>"  & koukoku_id & "</a></td>"
    Writeln "<td>" & ReplaceTag(RS("KK_koukoku_name")) & "</td>"
    Writeln "<td>" & ReplaceTag(RS("KK_client_name")) & "</td>"
    Writeln "<td>" & BlankToBR(ReplaceTag(RS("KK_company"))) & "</td>"
  Else
    Writeln "<td align='center'>" & RS("KK_koukoku_id") & "</td>"
    Writeln "<td>" & RS("KK_koukoku_name") & "</td>"
    Writeln "<td colspan=2>�y�L��ID:" & RS("KK_tsuika_id") & "�̒ǉ��˗����z</td>"
  End If

  Writeln "<td align='center'>" & RS("KK_koukoku_shuu") & "</td>"
  Writeln "<td align='right'>" & NumFormat(RS("keisai_busuu")) & "</td>"
  Writeln "<td align='right'>" & NumFormat(RS("KK_busuu")) & "</td>"

  If IsNull(RS("KK_tsuika_id")) Then
    If RS("KK_status") = 3 Then
      Writeln "<td align='center'><nobr>���o��</nobr></td>"
    Else
      Writeln "<td align='center'><input type='button' value='�I��' onClick='OnClick_shuuryou(""" & koukoku_id & """)'></td>"
    End If
  Else
    Writeln "<td><br></td>"
  End If
  Writeln "</tr>"

  RS.MoveNext
  line = line + 1
Loop
RS.close
%>

</table>

</form>
</td></tr></table>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
