<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�L���^�C�v�}�X�^�ꗗ�\��
'******************************************************

SetHeaderTitle "�L���^�C�v�}�X�^"

Dim ds, sql, line

SQL = "SELECT * FROM T_KOUKOKU_TYPE ORDER BY KT_order"
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
<table align="center" width="80%">
	<tr>
		<td align="left" class="m0">���L���^�C�v�}�X�^�̈ꗗ <font size="-1">�i�L���^�C�vID ���N���b�N����ƏC�����邱�Ƃ��ł��܂��j</font></td>
		<td align="right">
			<input type="button" value="�V�K�o�^" onclick="location.href='koukoku2.asp'">
			<input type="button" value=" �߂� " onclick="location.href='index.asp'">
		</td>
	</tr>
</table>
<table border=1 cellpadding=1 cellspacing=1 width="80%" align="center">
	<tr bgcolor="#E0FFE0">
		<th><nobr>�L���^�C�vID</nobr></th>
		<th><nobr>�\������</nobr></th>
		<th><nobr>�L������</nobr></th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td align='center' width='10%'><a href='koukoku3.asp?id=" & ds("KT_koukoku_id").Value & "' title='�L���^�C�v����\���E�ύX���܂�'>"  & ds("KT_koukoku_id").Value & "</a></td>"
	Writeln "<td align='right' width='10%'>" & ds("KT_order").Value & "</td>"
	Writeln "<td align='left' width='80%'>" & ReplaceTag(ds("KT_name").Value) & "</td>"
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
