<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�}�̃}�X�^�ꗗ�\��
'******************************************************

Private Function DecodeSearchFlag(flag)
	If flag = "1" Then
		DecodeSearchFlag = "��"
	Else
		DecodeSearchFlag = "<br>"
	End If
End Function

SetHeaderTitle "�}�̃}�X�^"
Response.CacheControl = "no-cache"

Dim ds, sql, line

SQL = "SELECT * FROM T_BAITAI ORDER BY BT_order"
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
		<td align="left" class="m0">���}�̃}�X�^�̈ꗗ <font size="-1">�i�}��ID ���N���b�N����ƏC�����邱�Ƃ��ł��܂��j</font></td>
		<td align="right">
			<input type="button" value="�V�K�o�^" onclick="location.href='baitai2.asp'">
			<input type="button" value=" �߂� " onclick="location.href='index.asp'">
		</td>
	</tr>
</table>
<table border=1 cellpadding=1 cellspacing=1 width="80%" align="center">
	<tr bgcolor="#E0FFE0">
		<th><nobr>�}��ID</nobr></th>
		<th><nobr>�\������</nobr></th>
		<th><nobr>�}�̖���</nobr></th>
		<th><nobr>�����w��</nobr></th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td align='center' width='10%'><a href='baitai3.asp?id=" & ds("BT_baitai_id").Value & "' title='�}�̏���\���E�ύX���܂�'>"  & ds("BT_baitai_id").Value & "</a></td>"
	Writeln "<td align='right' width='10%'>" & ds("BT_order").Value & "</td>"
	Writeln "<td align='left' width='70%'>" & ReplaceTag(ds("BT_name").Value) & "</td>"
	Writeln "<td align='center' width='10%'>" & DecodeSearchFlag(ds("BT_search_flag").Value) & "</td>"
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
