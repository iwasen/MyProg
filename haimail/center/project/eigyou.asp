<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:代理店／オペレータ登録
'******************************************************

SetHeaderTitle "代理店／オペレータ登録"
Response.CacheControl = "no-cache"

Dim ds, sql, same_dairiten, row_count, line, dairiten_id

SQL = "SELECT *" & vbCrLf & _
			"FROM T_DAIRITEN, T_OPERATOR" & vbCrLf & _
			"WHERE DA_dairiten_id=OP_dairiten_id(+)" & vbCrLf & _
			"ORDER BY DA_dairiten_id, OP_operator_id"
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
function super_agent(login_id) {
	if (confirm("オペレータID[" + login_id + "]で営業ページへログインします。よろしいですか？")) {
		document.form1.login_id.value = login_id;
		document.form1.submit();
	}
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<table align="center" width="100%">
	<tr>
		<td align="left" class="m0">■代理店の一覧 <font size="-1">（代理店名称 をクリックするとオペレータの新規登録ができます）</font></td>
		<td align="right">件数：<%=ds.RecordCount%>
			<input type="button" value="新規登録" onclick="location.href='eigyou_d1.asp'">
			<input type="button" value=" 戻る " onclick="location.href='index.asp'">
		</td>
	</tr>
</table>
<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
	<tr bgcolor="#E0FFE0">
		<th><nobr>代理店ID</nobr></th>
		<th><nobr>代理店名称</nobr></th>
		<th><nobr>オペレータID</nobr></th>
		<th><nobr>オペレータ名</nobr></th>
	</tr>
<%
Set same_dairiten = New CJoinSameItem
same_dairiten.Prepare ds, "DA_dairiten_id"

line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"

	dairiten_id = ds("DA_dairiten_id").Value
	row_count = same_dairiten.GetRowCount
	If row_count > 0 Then
		Writeln "<td align='center' rowspan=" & row_count & " class='tc2'><a href='eigyou_d2.asp?id=" & dairiten_id & "' title='代理店情報を表示・変更します'>" & dairiten_id & "</a></td>"
		Writeln "<td rowspan=" & row_count & " class='tc2'><a href='eigyou_o1.asp?dairiten_id=" & dairiten_id & "' title='オペレータを新規登録します'>" & ReplaceTag(ds("DA_name").Value) & "</a></td>"
	End If

	Writeln "<td align='center'><a href='eigyou_o2.asp?id=" & ds("OP_operator_id") & "' title='オペレータ情報を表示・変更します'>" & BlankToBR(ds("OP_operator_id").Value) & "</td>"
	Writeln "<td><a href='javaScript:super_agent(""" & ds("OP_operator_id").Value & """)' title='このオペレータで営業ページにログインします'>" & BlankToBR(ReplaceTag(ds("OP_name1").Value)) & " " & ReplaceTag(ds("OP_name2").Value) & "</a></td>"
	Writeln "</tr>"
	ds.MoveNext

	line = line + 1
Loop
%>

</table>
<form method="post" name="form1" action="/sales/login/agent_check.asp" target="_blank" style="margin:0">
<input type="hidden" name="login_id">
<input type="hidden" name="super" value="1">
</form>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</center>
</body>
</html>
