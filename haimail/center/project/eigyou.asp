<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�㗝�X�^�I�y���[�^�o�^
'******************************************************

SetHeaderTitle "�㗝�X�^�I�y���[�^�o�^"
Response.CacheControl = "no-cache"

Dim ds, sql, same_dairiten, row_count, line, dairiten_id

SQL = "SELECT *" & vbCrLf & _
			"FROM T_DAIRITEN, T_OPERATOR" & vbCrLf & _
			"WHERE DA_dairiten_id=OP_dairiten_id(+)" & vbCrLf & _
			"ORDER BY DA_dairiten_id, OP_operator_id"
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
function super_agent(login_id) {
	if (confirm("�I�y���[�^ID[" + login_id + "]�ŉc�ƃy�[�W�փ��O�C�����܂��B��낵���ł����H")) {
		document.form1.login_id.value = login_id;
		document.form1.submit();
	}
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<table align="center" width="100%">
	<tr>
		<td align="left" class="m0">���㗝�X�̈ꗗ <font size="-1">�i�㗝�X���� ���N���b�N����ƃI�y���[�^�̐V�K�o�^���ł��܂��j</font></td>
		<td align="right">�����F<%=ds.RecordCount%>
			<input type="button" value="�V�K�o�^" onclick="location.href='eigyou_d1.asp'">
			<input type="button" value=" �߂� " onclick="location.href='index.asp'">
		</td>
	</tr>
</table>
<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
	<tr bgcolor="#E0FFE0">
		<th><nobr>�㗝�XID</nobr></th>
		<th><nobr>�㗝�X����</nobr></th>
		<th><nobr>�I�y���[�^ID</nobr></th>
		<th><nobr>�I�y���[�^��</nobr></th>
	</tr>
<%
Set same_dairiten = New CJoinSameItem
same_dairiten.Prepare ds, "DA_dairiten_id"

line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"

	dairiten_id = ds("DA_dairiten_id").Value
	row_count = same_dairiten.GetRowCount
	If row_count > 0 Then
		Writeln "<td align='center' rowspan=" & row_count & " class='tc2'><a href='eigyou_d2.asp?id=" & dairiten_id & "' title='�㗝�X����\���E�ύX���܂�'>" & dairiten_id & "</a></td>"
		Writeln "<td rowspan=" & row_count & " class='tc2'><a href='eigyou_o1.asp?dairiten_id=" & dairiten_id & "' title='�I�y���[�^��V�K�o�^���܂�'>" & ReplaceTag(ds("DA_name").Value) & "</a></td>"
	End If

	Writeln "<td align='center'><a href='eigyou_o2.asp?id=" & ds("OP_operator_id") & "' title='�I�y���[�^����\���E�ύX���܂�'>" & BlankToBR(ds("OP_operator_id").Value) & "</td>"
	Writeln "<td><a href='javaScript:super_agent(""" & ds("OP_operator_id").Value & """)' title='���̃I�y���[�^�ŉc�ƃy�[�W�Ƀ��O�C�����܂�'>" & BlankToBR(ReplaceTag(ds("OP_name1").Value)) & " " & ReplaceTag(ds("OP_name2").Value) & "</a></td>"
	Writeln "</tr>"
	ds.MoveNext

	line = line + 1
Loop
%>

</table>
<form method="post" name="form1" action="/sales/login/agent_check.asp" target="_blank" style="margin:0">
<input type="hidden" name="login_id">
<input type="hidden" name="super" value="1">
</form>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</center>
</body>
</html>
