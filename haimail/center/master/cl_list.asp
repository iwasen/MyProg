<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�s�B���[���ꗗ�\������
'******************************************************

Dim sql, ds, line, from

SetHeaderTitle "���[���A�h���X�N���[�j���O"

sql = "SELECT UD_count,MM_member_id,MM_regist_date,MM_mail_adr" & vbCrLf & _
			"FROM T_UNDELIVERED,T_MEMBER" & vbCrLf & _
			"WHERE MM_status<>'9' AND MM_member_id=UD_member_id" & vbCrLf & _
			"ORDER BY UD_count DESC,MM_member_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function onclick_delete() {
	var f = document.form1;
	var ok = false;
	if (f.delete_id.length) {
		for (var i = 0; i < f.delete_id.length; i++) {
			if (f.delete_id[i].checked) {
				ok = true;
				break;
			}
		}
	} else {
		if (f.delete_id.checked)
			ok = true;
	}
	if (ok) {
		if (confirm("�I���������[���A�h���X���폜���܂��B��낵���ł����H"))
			f.submit();
	} else
		alert("�폜���郁�[���A�h���X��I�����Ă��������B");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<tr>
		<td>�����F<%=ds.RecordCount%></td>
		<td align="right">
<%
If ds.RecordCount > 0 Then
%>
			<input type="button" value=" �폜 " onclick="onclick_delete()">
<%
End If
%>
			<input type="button" value=" �߂� " onclick="location.href='index.asp'">
		</td>
	</tr>
</table>

<table border=1 cellpadding=0 cellspacing=1 width="100%">
	<form method="post" name="form1" action="cl_delete.asp">
	<tr bgcolor="#E0FFE0">
		<th>�s�B��</th>
		<th>�����o�[ID</th>
		<th>�o�^��</th>
		<th>���[���A�h���X</th>
		<th>�폜</th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td align='right'>" & NumFormat(ds("UD_count").Value) & "</td>"
	Writeln "<td align='center'>" & ds("MM_member_id").Value & "</td>"
	Writeln "<td align='center'>" & DateFormat(ds("MM_regist_date").Value) & "</td>"
	Writeln "<td><a href='cl_edit.asp?id=" & ds("MM_member_id").Value & "' title='���[���A�h���X���C�����܂�'>" & ReplaceTag(ds("MM_mail_adr").Value) & "</a></td>"
	Writeln "<td align='center'><input type='checkbox' name='delete_id' value='" & ds("MM_member_id").Value & "'></td>"
	Writeln "</tr>"
	ds.MoveNext
	line = line + 1
Loop
%>
	</form>
</table>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
