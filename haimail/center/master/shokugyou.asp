<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:職業マスタ一覧表示
'******************************************************

SetHeaderTitle "職業マスタ"
Response.CacheControl = "no-cache"

Dim ds, sql, line

SQL = "SELECT * FROM T_SHOKUGYOU ORDER BY SG_seq_no"
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
		<td align="left" class="m0">■職業マスタの一覧 <font size="-1">（職業コード をクリックすると修正することができます）</font></td>
		<td align="right">
			<input type="button" value="新規登録" onclick="location.href='shokugyou2.asp'">
			<input type="button" value=" 戻る " onclick="location.href='index.asp'">
		</td>
	</tr>
</table>
<table border=1 cellpadding=1 cellspacing=1 width="80%" align="center">
	<tr bgcolor="#E0FFE0">
		<th><nobr>職業コード</nobr></th>
		<th><nobr>表示順序</nobr></th>
		<th><nobr>職業名称</nobr></th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td align='center' width='10%'><a href='shokugyou3.asp?id=" & ds("SG_shokugyou_code").Value & "' title='職業情報を表示・変更します'>"  & ds("SG_shokugyou_code").Value & "</a></td>"
	Writeln "<td align='right' width='10%'>" & ds("SG_seq_no").Value & "</td>"
	Writeln "<td align='left' width='80%'>" & ReplaceTag(ds("SG_shokugyou_name").Value) & "</td>"
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
