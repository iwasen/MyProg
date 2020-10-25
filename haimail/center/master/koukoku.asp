<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:広告タイプマスタ一覧表示
'******************************************************

SetHeaderTitle "広告タイプマスタ"

Dim ds, sql, line

SQL = "SELECT * FROM T_KOUKOKU_TYPE ORDER BY KT_order"
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
		<td align="left" class="m0">■広告タイプマスタの一覧 <font size="-1">（広告タイプID をクリックすると修正することができます）</font></td>
		<td align="right">
			<input type="button" value="新規登録" onclick="location.href='koukoku2.asp'">
			<input type="button" value=" 戻る " onclick="location.href='index.asp'">
		</td>
	</tr>
</table>
<table border=1 cellpadding=1 cellspacing=1 width="80%" align="center">
	<tr bgcolor="#E0FFE0">
		<th><nobr>広告タイプID</nobr></th>
		<th><nobr>表示順序</nobr></th>
		<th><nobr>広告名称</nobr></th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td align='center' width='10%'><a href='koukoku3.asp?id=" & ds("KT_koukoku_id").Value & "' title='広告タイプ情報を表示・変更します'>"  & ds("KT_koukoku_id").Value & "</a></td>"
	Writeln "<td align='right' width='10%'>" & ds("KT_order").Value & "</td>"
	Writeln "<td align='left' width='80%'>" & ReplaceTag(ds("KT_name").Value) & "</td>"
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
