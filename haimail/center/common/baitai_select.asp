<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:対象・除外媒体選択画面処理
'******************************************************

'=== メイン処理 ===
Dim title, name, id, sub1, sql, ds, line, check, idarry, item, baitai_id, where, temp, arry

title = Request.QueryString("title")
name = Request.QueryString("name")
id = Request.QueryString("id")
idarry = Split(id, ",")

SetHeaderTitle title

where = "BT_search_flag='1'"

If system_id = "sales" Then
	sql = "SELECT DA_" & name & " FROM T_OPERATOR,T_DAIRITEN WHERE OP_operator_id='" & g_login_id & "' AND OP_dairiten_id=DA_dairiten_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		temp = ""
		arry = Split(Replace(NVL(ds(0).Value, ""), vbCrLf, ","), ",")
		For Each item In arry
			If Len(item) > 0 Then
				If temp <> "" Then
					temp = temp & ","
				End If
				temp = temp & MakeID(item, 2)
			End If
		Next
		If temp = "" Then
			temp = "''"
		End If
		where = where & " AND BT_baitai_id IN (" & temp & ")"
	End If
End If

sql = "SELECT BT_baitai_id,BT_name" & vbCrLf & _
			"FROM T_BAITAI" & vbCrLf & _
			"WHERE " & where & vbCrLf & _
			"ORDER BY BT_order"
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
function OnClick_ok() {
	var f = document.form1;
	var id, n, i;
	id = "";
<%If ds.RecordCount = 1 Then%>
	if (f.baitai_id.checked)
		id = f.baitai_id.value;
<%ElseIf ds.RecordCount > 1 Then%>
	n = f.baitai_id.length;
	for (i = 0; i < n; i++) {
		if (f.baitai_id[i].checked) {
			if (id != "")
				id = id + ",";
			id = id + f.baitai_id[i].value;
		}
	}
<%End If%>
	opener.document.form1("<%=name%>").value = id;
	close();
}
function OnClick_all_select() {
	var f = document.form1;
<%If ds.RecordCount = 1 Then%>
	f.baitai_id.checked = true;
<%ElseIf ds.RecordCount > 1 Then%>
	n = f.baitai_id.length;
	for (i = 0; i < n; i++)
		f.baitai_id[i].checked = true;
<%End If%>
}
function OnClick_all_cancel() {
	var f = document.form1;
<%If ds.RecordCount = 1 Then%>
	f.baitai_id.checked = false;
<%ElseIf ds.RecordCount > 1 Then%>
	n = f.baitai_id.length;
	for (i = 0; i < n; i++)
		f.baitai_id[i].checked = false;
<%End If%>
}
//-->
</SCRIPT>
</head>
<body>
<form method="post" name="form1">
<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<tr>
		<td class="m0">■<%=title%></td>
		<td align="right">
			<input type="button" value="全選択" onclick="OnClick_all_select()">
			<input type="button" value="全解除" onclick="OnClick_all_cancel()">
			<input type="button" value="　OK　" onclick="OnClick_ok()">
			<input type="button" value="ｷｬﾝｾﾙ" onclick="window.close()">
		</td>
	</tr>
</table>

<table border=1 cellpadding=0 cellspacing=1 width="100%">
	<tr bgcolor="#E0FFE0">
		<th><nobr>選択</nobr></th>
		<th><nobr>媒体ID</nobr></th>
		<th><nobr>媒体名</nobr></th>
	</tr>
<%
line = 1
Do Until ds.EOF
	baitai_id = ds("BT_baitai_id").Value
	check = ""
	For Each item In idarry
		If MakeID(item, 2) = baitai_id Then
			check = " checked"
			Exit For
		End If
	Next

	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td width='10%' align='center'><input type='checkbox' name='baitai_id' value='" & baitai_id & "'" & check & "></td>"
	Writeln "<td width='10%' align='center'>" & baitai_id & "</td>"
	Writeln "<td width='80%'>" & ReplaceTag(ds("BT_name").Value) & "</td>"
	Writeln "</tr>"
	ds.MoveNext

	line = line + 1
Loop
%>
</table>
</form>

</body>
</html>
