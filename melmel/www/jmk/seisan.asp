<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:���Z�v���ꗗ�\��
'******************************************************

SetHeaderTitle "���Z�v�����X�g", TC_HAKKOUSHA
Response.CacheControl = "no-cache"
%>
<html>
<HEAD>
<META HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<META name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<LINK rel="stylesheet" type="text/css" href="admin.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_mel_id(id) {
  var win;
  win = window.open("mel_info.asp?id=" + id, "mel_info", "resizable=yes,scrollbars=yes,width=740");
  win.focus();
}
function OnSubmit_form1(f) {
	if (f.seq_no) {
		if (f.seq_no.length) {
			var n = f.seq_no.length;
			for (var i = 0; i < n; i++) {
				if (f.seq_no[i].checked)
				  return true;
			}
		} else {
			if (f.seq_no.checked)
				return true;
		}
	}
	alert("���Z����f�[�^������܂���B");
	return false;
}
//-->
</SCRIPT>
</HEAD>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim DB, RS, SQL, line

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT SP_seq_no,SP_mel_id,MM_h_name,SP_req_point,SP_req_date,MM_bk_nm,MM_bk_st,MM_bk_knd,MM_bk_acc,MM_bk_knm" & vbCrLf & _
      "FROM T_SPOINT INNER JOIN T_MELMEL ON SP_mel_id=MM_mel_id" & vbCrLf & _
      "WHERE SP_file=0" & vbCrLf & _
      "ORDER BY SP_seq_no"
RS.Open SQL, DB, adOpenKeyset, adLockReadOnly
%>

<table width="100%" border=0 cellpadding=0 cellspacing=0><tr><td>
<form method="post" name="form1" action="seisan2.asp" onSubmit="return OnSubmit_form1(this)">

<table width="100%">
  <tr>
    <td align="left">�������Z�̐��Z�v���ꗗ</td>
    <td align="right">�����F<%=RS.RecordCount%></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor='#FFE0E0'>
    <th>���s��ID</th>
    <th>���s�Җ�</th>
    <th>���Z�|�C���g</th>
    <th>�v����</th>
    <th>��s��</th>
    <th>��s�x�X��</th>
    <th>�������</th>
    <th>�����ԍ�</th>
    <th>�������`</th>
    <th>���Z</th>
  </tr>

<%
line = 1
Do Until RS.EOF
  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If
  Writeln "<td align='center'><a href='JavaScript:OnClick_mel_id(""" & RS("SP_mel_id") & """)'>" & RS("SP_mel_id") & "</a></td>"
  Writeln "<td>" & ReplaceTag(RS("MM_h_name")) & "</td>"
  Writeln "<td align='right'>" & NumFormat(RS("SP_req_point")) & "</td>"
  Writeln "<td align='center'>" & RS("SP_req_date") & "</td>"
  Writeln "<td>" & RS("MM_bk_nm") & "</td>"
  Writeln "<td>" & RS("MM_bk_st") & "</td>"
  Select Case RS("MM_bk_knd")
  Case 1
    Writeln "<td>����</td>"
  Case 2
    Writeln "<td>����</td>"
  Case 3
    Writeln "<td>���~</td>"
  Case Else
    Writeln "<td>�s��</td>"
  End Select
  Writeln "<td>" & RS("MM_bk_acc") & "</td>"
  Writeln "<td>" & RS("MM_bk_knm") & "</td>"
  Writeln "<td align='center'><input type='checkbox' name='seq_no' value='" & RS("SP_seq_no") & "' checked></td>"
  Writeln "</tr>"

  RS.MoveNext
  line = line + 1
Loop
RS.close
%>

</table>

<center>
<br>
<input type="submit" value="CSV�o��">
</center>

</form>
</td></tr></table>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
