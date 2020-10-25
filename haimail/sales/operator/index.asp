<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:代理店／オペレータ登録
'******************************************************

'=== メイン処理 ===
SetHeaderTitle "オペレータ登録"
Response.CacheControl = "no-cache"

Dim ds, sql, dairiten_id, line

SQL = "SELECT *" & vbCrLf & _
			"FROM T_OPERATOR, T_DAIRITEN" & vbCrLf & _
			"WHERE OP_operator_id='" & g_login_id & "' AND OP_dairiten_id = DA_dairiten_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)

SQL = "SELECT *" & vbCrLf & _
			"FROM T_OPERATOR" & vbCrLf & _
			"WHERE OP_dairiten_id = '" & ds("OP_dairiten_id").value & "'" & vbCrLf & _
			"ORDER BY OP_operator_id"
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
function OnClick_form(dairiten_id) {
	location.href = "operator1.asp?dairiten_id=" + dairiten_id;
}
//-->
</SCRIPT>
</head>
<body>
<!--#INCLUDE FILE="../inc/header.asp"-->
<center>
<table align="center" width="100%">
	<input type="hidden" name="dairiten_id" value="<%=ds("OP_dairiten_id").Value%>">
	<tr>
		<td align="left" class="m0">■オペレータの一覧 <font size="-1">（オペレータID をクリックするとオペレータ情報の修正ができます）</font></td>
		<td align="right">件数：<%=ds.RecordCount%>
			<input type="button" value="新規登録" onclick="OnClick_form(<%=ds("OP_dairiten_id").Value%>)">
			<input type="button" value=" 戻る " onclick="location.href='../top/index.asp'">
		</td>
	</tr>
</table>
<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
	<tr bgcolor="#E0FFE0">
		<th><nobr>オペレータID</nobr></th>
		<th><nobr>オペレータ名</nobr></th>
		<th><nobr>メールアドレス</nobr></th>
		<th><nobr>電話</nobr></th>
		<th><nobr>ＦＡＸ</nobr></th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"

	Writeln "<td align='center'><a href='operator2.asp?id=" & ds("OP_operator_id") & "'>" & ds("OP_operator_id").Value & "</a></td>"
	Writeln "<td>" & ReplaceTag(ds("OP_name1").Value) & " " & ReplaceTag(ds("OP_name2").Value) & "</td>"
	Writeln "<td>" & BlankToBR(ds("OP_mail_adr").Value) & "</td>"
	Writeln "<td>" & BlankToBR(ds("OP_tel_no").Value) & "</td>"
	Writeln "<td>" & BlankToBR(ds("OP_fax_no").Value) & "</td>"

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
