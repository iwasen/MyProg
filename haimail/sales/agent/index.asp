<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ɨp�y�[�W
' Content:�㗝�X�^�S���ғo�^
'******************************************************

'=== ���C������ ===
SetHeaderTitle "�㗝�X�^�S���ғo�^"
Response.CacheControl = "no-cache"

Dim ds, sql, dairiten_id, line, same_dairiten, row_count

sql = "SELECT OP_dairiten_id FROM T_OPERATOR WHERE OP_operator_id='" & g_login_id & "'"
Set ds = CreateDynaset(sql, ORADYN_READONLY)

sql = "SELECT *" & vbCrLf & _
			"FROM T_DAIRITEN,T_OPERATOR" & vbCrLf & _
			"WHERE DA_rep_id='" & ds(0).Value & "' AND DA_dairiten_id=OP_dairiten_id(+)" & vbCrLf & _
			"ORDER BY DA_dairiten_id, OP_dairiten_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
%>
<HTML>
<HEAD>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</HEAD>
<BODY>
<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<table align="center" width="100%">
	<tr>
		<td align="left" class="m0">���㗝�X�̈ꗗ <font size="-1">�i�㗝�X�����N���b�N����ƒS���҂̐V�K�o�^���ł��܂��j</font></td>
		<td align="right">�����F<%=ds.RecordCount%>
			<input type="button" value="�V�K�o�^" onclick="location.href='dairiten1.asp'">
			<input type="button" value=" �߂� " onclick="location.href='../top/index.asp'">
		</td>
	</tr>
</table>
<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
	<tr bgcolor="#E0FFE0">
		<th><nobr>�㗝�XID</nobr></th>
		<th><nobr>�㗝�X��</nobr></th>
		<th><nobr>�S����ID</nobr></th>
		<th><nobr>�S���Җ�</nobr></th>
	</tr>
<%
Set same_dairiten = New CJoinSameItem
same_dairiten.Prepare ds, "DA_dairiten_id"

line = 1
Do Until ds.EOF
  Writeln "<tr class='tc" & line Mod 2 & "'>"

	row_count = same_dairiten.GetRowCount
  If row_count > 0 Then
	  Writeln "<td align='center' rowspan=" & row_count & " class='tc2'><a href='dairiten2.asp?id=" & ds("DA_dairiten_id").Value & "'>" & ds("DA_dairiten_id").Value & "</a></td>"
	  Writeln "<td rowspan=" & row_count & " class='tc2'><a href='operator1.asp?dairiten_id=" & ds("DA_dairiten_id").Value & "'>" & ReplaceTag(ds("DA_name").Value) & "</a></td>"
  End If

	Writeln "<td align='center'><a href='operator2.asp?id=" & ds("OP_operator_id").value & "'>" & BlankToBR(ds("OP_operator_id").Value) & "</a></td>"
	Writeln "<td>" & BlankToBR(ReplaceTag(ds("OP_name1").Value)) & " " & ReplaceTag(ds("OP_name2").Value) & "</td>"
	Writeln "</tr>"
	ds.MoveNext

  line = line + 1
Loop
%>

</table>
<!--#INCLUDE FILE="../inc/footer.asp"-->
</center>
</BODY>
</HTML>
