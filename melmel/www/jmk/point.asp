<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�|�C���g�Ǘ��ꗗ�\��
'******************************************************

SetHeaderTitle "�|�C���g�Ǘ�", TC_HAKKOUSHA
Response.CacheControl = "no-cache"
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
function OnClick_mel_id(id) {
  var win;
  win = window.open("mel_info.asp?id=" + id, "mel_info", "resizable=yes,scrollbars=yes,width=740");
  win.focus();
}
function OnClick_sakujo(seq_no) {
  if (confirm("�w�肵���f�[�^���폜���܂��B��낵���ł����H"))
    location.href = "point4.asp?no=" + seq_no;
}
function OnSubmit_form1() {
  if (document.form1.mel_id.value == "") {
    alert("���s��ID����͂��Ă��������B");
    document.form1.mel_id.focus();
    return false;
  }
  return true;
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim DB, RS, SQL, line

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT MP_seq_no,MP_mel_id,MM_h_name,MP_point,MP_comment,MP_date" & vbCrLf & _
      "FROM T_MPOINT INNER JOIN T_MELMEL ON MP_mel_id=MM_mel_id" & vbCrLf & _
      "ORDER BY MP_seq_no DESC"
RS.Open SQL, DB, adOpenKeyset, adLockReadOnly
%>

<form method="post" action="point2.asp" name="form1" style="margin:0" onSubmit="return OnSubmit_form1()">
<table width="100%">
  <tr>
    <td align="left">
      �����s��ID&nbsp;<input type="text" name="mel_id">
      <input type="submit" value="�V�K����">
    </td>
    <td align="right">�����F<%=RS.RecordCount%></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor="#FFE0E0">
    <th>���s��ID</th>
    <th>���s�Җ�</th>
    <th>�|�C���g</th>
    <th>�R�����g</th>
    <th>�o�^��</th>
    <th>�폜</th>
  </tr>

<%
line = 1
Do Until RS.EOF
  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If
  Writeln "<td align='center'><a href='JavaScript:OnClick_mel_id(""" & RS("MP_mel_id") & """)'>" & RS("MP_mel_id") & "</a></td>"
  Writeln "<td>" & ReplaceTag(RS("MM_h_name")) & "</td>"
  Writeln "<td align='right'>" & FormatNumber(RS("MP_point"), 2, -1, 0, 0) & "</td>"
  Writeln "<td>" & ReplaceTag(RS("MP_comment")) & "</td>"
  Writeln "<td align='center'>" & DateFormat(RS("MP_date")) & "</td>"
  Writeln "<td align='center'><input type='button' value='�폜' onClick='OnClick_sakujo(""" & RS("MP_seq_no") & """)'></td>"
  Writeln "</tr>"

  RS.MoveNext
  line = line + 1
Loop
RS.close
%>

</table>
</form>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
