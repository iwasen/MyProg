<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�����W�������}�X�^�ꗗ�\��
'******************************************************

SetHeaderTitle "�����W�������}�X�^�ꗗ"
Response.CacheControl = "no-cache"

Dim sql, ds, line, k_category

%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnChange_k_category() {
	document.form1.submit();
}
function OnClick_form2(k_category) {
	location.href = "kyoumi2.asp?cg=" + k_category;
}
//-->
</SCRIPT>
</head>
<body>
<!--#INCLUDE FILE="../inc/header.asp"-->
<center>
<form method="post" name="form1" style="margin:0">
<table align="center" width="100%">
	<tr>
		<td align="left" class="m0">�������W�������}�X�^�̈ꗗ<font size="-1"> (�J�e�S����I�����Ă�������)&nbsp;</font></td>
		<td colspan="2" align="left" valign="middle">
<%
If Not IsEmpty(Request.form("k_category")) Then
	Session("k_category") = Request.form("k_category")
End If
k_category = Session("k_category")
%>
			<font class="hc">�J�e�S���I��</font>
			<select name="k_category" onchange="OnChange_k_category()">
				<option value=""<%=Selected(k_category, "")%>>�S��</option>
				<option <%=Selected(k_category, "�H")%>>�H</option>
				<option <%=Selected(k_category, "��")%>>��</option>
				<option <%=Selected(k_category, "�Z")%>>�Z</option>
				<option <%=Selected(k_category, "��")%>>��</option>
				<option <%=Selected(k_category, "��")%>>��</option>
				<option <%=Selected(k_category, "�x")%>>�x</option>
				<option <%=Selected(k_category, "�V")%>>�V</option>
				<option <%=Selected(k_category, "��")%>>��</option>
				<option <%=Selected(k_category, "�m")%>>�m</option>
			</select>
		</td>
		<td align="right">
<%
If k_category <> "" Then
	Writeln "<input type='button' value='�V�K�o�^' onclick='OnClick_form2(""" & k_category & """)'>"
End If
%>
			<input type="button" value=" �߂� " onclick="location.href='index.asp'">
		</td>
	</tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
	<tr bgcolor="#E0FFE0">
		<th width="10%"><nobr>���ޯ���ԍ�</nobr></th>
		<th width="10%"><nobr>�\������</nobr></th>
		<th width="10%"><nobr>�ú��</nobr></th>
		<th width="70%"><nobr>���ڃe�L�X�g</nobr></th>
		<th width="10%"><nobr>�L���t���O</nobr></th>
	</tr>
<%
If Session("k_category") <> "" Then
	SQL = "SELECT * FROM T_KYOUMI_GENRE WHERE KG_category ='" & k_category & "' ORDER BY KG_item_no"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If ds.EOF Then
			Session("k_category") = ""
			SQL = "SELECT * FROM T_KYOUMI_GENRE ORDER BY KG_index"
			Set ds = CreateDynaset(sql, ORADYN_READONLY)
		End If
Else
	SQL = "SELECT * FROM T_KYOUMI_GENRE ORDER BY KG_index"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
End If

line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	If ds("KG_valid_flag").Value = 1 Then
		Writeln "<td align='center' width='10%'><a href='kyoumi3.asp?id=" & ds("KG_index").Value & "' title='�����W����������\���E�ύX���܂�'>"  & ds("KG_index").Value & "</a></td>"
		Writeln "<td align='right' width='10%'>" & ds("KG_item_no").Value & "</td>"
		Writeln "<td align='center' width='10%'>" & ds("KG_category").Value & "</td>"
		Writeln "<td align='left' width='70%'>" & ReplaceTag(ds("KG_item_text").Value) & "</td>"
		Writeln "<td align='center' width='10%'>��</td>"
	ELse
		Writeln "<td align='center' width='10%'><a href='kyoumi3.asp?id=" & ds("KG_index").Value & "' title='�����W����������\���E�ύX���܂�'>"  & ds("KG_index").Value & "</a></td>"
		Writeln "<td align='right' width='10%'><Font color='#999999'>" & ds("KG_item_no").Value & "</font></td>"
		Writeln "<td align='center' width='10%'><Font color='#999999'>" & ds("KG_category").Value & "</font></td>"
		Writeln "<td align='left' width='70%'><Font color='#999999'>" & ReplaceTag(ds("KG_item_text").Value) & "</font></td>"
		Writeln "<td align='center' width='10%'>�~</td>"
	End If
	Writeln "</tr>"
	ds.MoveNext
	line = line + 1
Loop

%>
</table>
</form>
</center>
<!--#INCLUDE FILE="../inc/footer.asp"-->
</body>
</html>
