<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�����ǃ}�X�^�ꗗ�\��
'******************************************************

SetHeaderTitle "�����ǃ}�X�^"
Response.CacheControl = "no-cache"

Dim ds, sql, line

SQL = "SELECT * FROM T_CENTER ORDER BY CN_user_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</head>
<body>
<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<table align="center" width="100%">
	<tr>
		<td align="left" class="m0">�������ǃ}�X�^�̈ꗗ <font size="-1">�i���[�UID ���N���b�N����ƏC�����邱�Ƃ��ł��܂��j</font></td>
		<td align="right">
			<input type="button" value="�V�K�o�^" onclick="location.href='center2.asp'">
			<input type="button" value=" �߂� " onclick="location.href='index.asp'">
		</td>
	</tr>
</table>
<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
	<tr bgcolor="#E0FFE0">
		<th><nobr>���[�UID</nobr></th>
		<th><nobr>�p�X���[�h</nobr></th>
		<th><nobr>����</nobr></th>
		<th><nobr>���[���A�h���X</nobr></th>
		<th><nobr>����</nobr></th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td align='center'><a href='center3.asp?id=" & ds("CN_user_id").Value & "' title='�����ǃ��[�U��\���E�ύX���܂�'>"  & ds("CN_user_id").Value & "</a></td>"
	Writeln "<td align='left'>" & ReplaceTag(ds("CN_password").Value) & "</td>"
	Writeln "<td align='left'>" & ReplaceTag(ds("CN_name").Value) & "</td>"
	Writeln "<td align='left'>" & BlankToBR(ReplaceTag(ds("CN_mail_adr").Value)) & "</td>"
	Writeln "<td align='center'>" & ds("CN_privilege").Value & "</td>"
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
