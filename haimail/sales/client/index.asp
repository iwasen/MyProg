<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ɨp�y�[�W
' Content:��Ɓ^�S���ғo�^
'******************************************************

'=== ���C������ ===
SetHeaderTitle "��Ɓ^�S���ғo�^"
Response.CacheControl = "no-cache"

Dim ds, sql, company_id, line, same_company, row_count

SQL = "SELECT *" & vbCrLf & _
			"FROM T_COMPANY, T_TANTOUSHA, T_OPERATOR" & vbCrLf & _
			"WHERE OP_operator_id='" & g_login_id & "' AND OP_dairiten_id=CM_dairiten_id AND CM_company_id=TS_company_id(+)" & vbCrLf & _
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
			<td align="left" class="m0" colspan="2" valign="top">
				<p>�����M��Ƃ̈ꗗ</p>
				<p class="np"> <font color="#000000">�����炩��̃��j���[���甭�M��Ɠo�^���s���܂��B�u�V�K�o�^�v�{�^�����甭�M��Ɨl�����o�^���������B<br>
					���o�^��A���M��Ɩ����N���b�N���āA�S���ғo�^���s���Ă��������B<br>
					���o�^�������������M��Ɓ@�S���҂h�c�A�p�X���[�h�ŁA�v���W�F�N�g�̉{�����\�ɂȂ�܂��B</font></p>
			</td>
			<td align="right" width="25%" valign="bottom">�����F<%=ds.RecordCount%>
				<input type="button" value="�V�K�o�^" onclick="location.href='company1.asp'">
				<input type="button" value=" �߂� " onclick="location.href='../top/index.asp'">
			</td>
		</tr>
	</table>
	<br>
	<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
	<tr bgcolor="#E0FFE0">
		<th><nobr>���M���ID</nobr></th>
		<th><nobr>���M��Ɩ�</nobr></th>
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
		Writeln "<td align='center' rowspan=" & row_count & " class='tc2'><a href='company2.asp?id=" & ds("CM_company_id").Value & "'>" & ds("CM_company_id").Value & "</a></td>"
		Writeln "<td rowspan=" & row_count & " class='tc2'><a href='tantou1.asp?company_id=" & ds("CM_company_id").Value & "'>" & ReplaceTag(ds("CM_name").Value) & "</a></td>"
	End If

	Writeln "<td align='center'><a href='tantou2.asp?id=" & ds("TS_tantousha_id").value & "'>" & BlankToBR(ds("TS_tantousha_id").Value) & "</a></td>"
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
