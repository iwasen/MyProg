<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ɨp�y�[�W
' Content:�㗝�X�^�I�y���[�^�o�^
'******************************************************

'=== ���C������ ===
SetHeaderTitle "�I�y���[�^�o�^"
Response.CacheControl = "no-cache"

Dim ds, sql, dairiten_id, line

SQL = "SELECT *" & vbCrLf & _
			"FROM T_OPERATOR, T_DAIRITEN" & vbCrLf & _
			"WHERE OP_operator_id='" & g_login_id & "' AND OP_dairiten_id = DA_dairiten_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)

SQL = "SELECT *" & vbCrLf & _
			"FROM T_OPERATOR" & vbCrLf & _
			"WHERE OP_dairiten_id = '" & ds("OP_dairiten_id").value & "'" & vbCrLf & _
			"ORDER BY OP_operator_id"
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
function OnClick_form(dairiten_id) {
	location.href = "operator1.asp?dairiten_id=" + dairiten_id;
}
//-->
</SCRIPT>
</head>
<body>
<!--#INCLUDE FILE="../inc/header.asp"-->
<center>
<table align="center" width="100%">
	<input type="hidden" name="dairiten_id" value="<%=ds("OP_dairiten_id").Value%>">
	<tr>
		<td align="left" class="m0">���I�y���[�^�̈ꗗ <font size="-1">�i�I�y���[�^ID ���N���b�N����ƃI�y���[�^���̏C�����ł��܂��j</font></td>
		<td align="right">�����F<%=ds.RecordCount%>
			<input type="button" value="�V�K�o�^" onclick="OnClick_form(<%=ds("OP_dairiten_id").Value%>)">
			<input type="button" value=" �߂� " onclick="location.href='../top/index.asp'">
		</td>
	</tr>
</table>
<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
	<tr bgcolor="#E0FFE0">
		<th><nobr>�I�y���[�^ID</nobr></th>
		<th><nobr>�I�y���[�^��</nobr></th>
		<th><nobr>���[���A�h���X</nobr></th>
		<th><nobr>�d�b</nobr></th>
		<th><nobr>�e�`�w</nobr></th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"

	Writeln "<td align='center'><a href='operator2.asp?id=" & ds("OP_operator_id") & "'>" & ds("OP_operator_id").Value & "</a></td>"
	Writeln "<td>" & ReplaceTag(ds("OP_name1").Value) & " " & ReplaceTag(ds("OP_name2").Value) & "</td>"
	Writeln "<td>" & BlankToBR(ds("OP_mail_adr").Value) & "</td>"
	Writeln "<td>" & BlankToBR(ds("OP_tel_no").Value) & "</td>"
	Writeln "<td>" & BlankToBR(ds("OP_fax_no").Value) & "</td>"

	Writeln "</tr>"
	ds.MoveNext

	line = line + 1
Loop
%>

</table>
<!--#INCLUDE FILE="../inc/footer.asp"-->
</center>
</body>
</html>
