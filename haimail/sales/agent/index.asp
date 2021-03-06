<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:代理店／担当者登録
'******************************************************

'=== メイン処理 ===
SetHeaderTitle "代理店／担当者登録"
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
		<td align="left" class="m0">■代理店の一覧 <font size="-1">（代理店名をクリックすると担当者の新規登録ができます）</font></td>
		<td align="right">件数：<%=ds.RecordCount%>
			<input type="button" value="新規登録" onclick="location.href='dairiten1.asp'">
			<input type="button" value=" 戻る " onclick="location.href='../top/index.asp'">
		</td>
	</tr>
</table>
<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
	<tr bgcolor="#E0FFE0">
		<th><nobr>代理店ID</nobr></th>
		<th><nobr>代理店名</nobr></th>
		<th><nobr>担当者ID</nobr></th>
		<th><nobr>担当者名</nobr></th>
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
