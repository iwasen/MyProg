<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:��Ɓ^�S���ғo�^
'******************************************************

SetHeaderTitle "��Ɓ^�S���ғo�^"
Response.CacheControl = "no-cache"

Dim ds, sql, same_company, row_count, line

SQL = "SELECT *" & vbCrLf & _
			"FROM T_COMPANY, T_TANTOUSHA" & vbCrLf & _
			"WHERE CM_company_id=TS_company_id(+)" & vbCrLf & _
			"ORDER BY CM_company_id, TS_tantousha_id"
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
		<td align="left" class="m0">����Ƃ̈ꗗ <font size="-1">�i��Ɩ� ���N���b�N����ƒS���҂̐V�K�o�^���ł��܂��j</font></td>
		<td align="right">�����F<%=ds.RecordCount%>
			<input type="button" value="�V�K�o�^" onclick="location.href='kigyou_c1.asp'">
			<input type="button" value=" �߂� " onclick="location.href='index.asp'">
		</td>
	</tr>
</table>
<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
	<tr bgcolor="#E0FFE0">
		<th><nobr>���ID</nobr></th>
		<th><nobr>��Ɩ�</nobr></th>
		<th><nobr>�S����ID</nobr></th>
		<th><nobr>�S���Җ�</nobr></th>
	</tr>
<%
Set same_company = New CJoinSameItem
same_company.Prepare ds, "CM_company_id"

line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"

	row_count = same_company.GetRowCount
	If row_count > 0 Then
		Writeln "<td align='center' rowspan=" & row_count & " class='tc2'><a href='kigyou_c2.asp?id=" & ds("CM_company_id").Value & "' title='��Ə���\���E�ύX���܂�'>" & ds("CM_company_id").Value & "</a></td>"
		Writeln "<td rowspan=" & row_count & " class='tc2'><a href='kigyou_t1.asp?company_id=" & ds("CM_company_id").Value & "' title='��ƒS���҂�V�K�o�^���܂�'>" & ReplaceTag(ds("CM_name").Value) & "</a></td>"
	End If

	Writeln "<td align='center'><a href='kigyou_t2.asp?id=" & ds("TS_tantousha_id") & "' title='��ƒS���ҏ���\���E�ύX���܂�'>" & BlankToBR(ds("TS_tantousha_id").Value) & "</td>"
	Writeln "<td>" & BlankToBR(ReplaceTag(ds("TS_name1").Value)) & " " & ReplaceTag(ds("TS_name2").Value) & "</td>"
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
