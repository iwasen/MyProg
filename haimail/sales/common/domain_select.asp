<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:対象・除外ドメイン選択画面処理
'******************************************************

'=== メイン処理 ===
Dim title, name, id, sub1, sql, ds, line, check, idarry, item, domain_id

title = Request.QueryString("title")
name = Request.QueryString("name")
id = Request.QueryString("id")
idarry = Split(id, ",")

SetHeaderTitle title

sql = "SELECT MD_domain_id,MD_name" & vbCrLf & _
			"FROM T_MAIL_DOMAIN" & vbCrLf & _
			"ORDER BY MD_order"
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
	if (f.domain_id.checked)
		id = f.domain_id.value;
<%ElseIf ds.RecordCount > 1 Then%>
	n = f.domain_id.length;
	for (i = 0; i < n; i++) {
		if (f.domain_id[i].checked) {
			if (id != "")
				id = id + ",";
			id = id + f.domain_id[i].value;
		}
	}
<%End If%>
	opener.document.form1("<%=name%>").value = id;
	close();
}
function OnClick_all_select() {
	var f = document.form1;
<%If ds.RecordCount = 1 Then%>
	f.domain_id.checked = true;
<%ElseIf ds.RecordCount > 1 Then%>
	n = f.domain_id.length;
	for (i = 0; i < n; i++)
		f.domain_id[i].checked = true;
<%End If%>
}
function OnClick_all_cancel() {
	var f = document.form1;
<%If ds.RecordCount = 1 Then%>
	f.domain_id.checked = false;
<%ElseIf ds.RecordCount > 1 Then%>
	n = f.domain_id.length;
	for (i = 0; i < n; i++)
		f.domain_id[i].checked = false;
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
		<th><nobr>ドメインID</nobr></th>
		<th><nobr>ドメイン名</nobr></th>
	</tr>
<%
line = 1
Do Until ds.EOF
	domain_id = ds("MD_domain_id").Value
	check = ""
	For Each item In idarry
		If MakeID(item, 2) = domain_id Then
			check = " checked"
			Exit For
		End If
	Next

	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td width='10%' align='center'><input type='checkbox' name='domain_id' value='" & domain_id & "'" & check & "></td>"
	Writeln "<td width='10%' align='center'>" & domain_id & "</td>"
	Writeln "<td width='80%'>" & ReplaceTag(ds("MD_name").Value) & "</td>"
	Writeln "</tr>"
	ds.MoveNext

	line = line + 1
Loop
%>
</table>
</form>

</body>
</html>
