<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�ǂ����h�}�X�^�ꗗ�\��
'******************************************************

SetHeaderTitle "�ǂ����h�}�X�^�ꗗ"
Response.CacheControl = "no-cache"

Dim sql, ds, line

SQL = "SELECT * FROM T_DOCCHI_HA ORDER BY DH_item_no"
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
		<td align="left" class="m0">���ǂ����h�}�X�^�̈ꗗ<font size="-1">�i�C���f�b�N�X�ԍ� ���N���b�N����ƏC�����邱�Ƃ��ł��܂��j</font></td>
		<td align="right">
			<input type="button" value="�V�K�o�^" onclick="location.href='docchi2.asp'">
			<input type="button" value=" �߂� " onclick="location.href='index.asp'">
		</td>
	</tr>
</table>
<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
	<tr bgcolor="#E0FFE0">
		<th><nobr>���ޯ���ԍ�</nobr></th>
		<th><nobr>�\������</nobr></th>
		<th><nobr>���ڃe�L�X�g�P</nobr></th>
		<th><nobr>���ڃe�L�X�g�Q</nobr></th>
		<th><nobr>�L���t���O</nobr></th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	If ds("DH_valid_flag").Value = 1 Then
		Writeln "<td align='center' width='10%'><a href='docchi3.asp?id=" & ds("DH_index").Value & "' title='�ǂ����h����\���E�ύX���܂�'>"  & ds("DH_index").Value & "</a></td>"
		Writeln "<td align='right' width='10%'>" & ds("DH_item_no").Value & "</td>"
		Writeln "<td align='left' width='35%'>" & ReplaceTag(ds("DH_item1_text").Value) & "</td>"
		Writeln "<td align='left' width='35%'>" & ReplaceTag(ds("DH_item2_text").Value) & "</td>"
		Writeln "<td align='center' width='10%'>��</td>"
		Writeln "</tr>"
	ELse
		Writeln "<td align='center' width='10%'><a href='docchi3.asp?id=" & ds("DH_index").Value & "' title='�ǂ����h����\���E�ύX���܂�'>"  & ds("DH_index").Value & "</a></td>"
		Writeln "<td align='right' width='10%'><Font color='#999999'>" & ds("DH_item_no").Value & "</font></td>"
		Writeln "<td align='left' width='35%'><Font color='#999999'>" & ReplaceTag(ds("DH_item1_text").Value) & "</font></td>"
		Writeln "<td align='left' width='35%'><Font color='#999999'>" & ReplaceTag(ds("DH_item2_text").Value) & "</font></td>"
		Writeln "<td align='center' width='10%'>�~</td>"
		Writeln "</tr>"
	End If
	ds.MoveNext
	line = line + 1
Loop
%>
</table>
</center>
<!--#INCLUDE FILE="../inc/footer.asp"-->
</body>
</html>
