<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:クレーム管理／キーワードマスタ一覧表示
'******************************************************

'=== メイン処理 ===
Dim ds, sql, line

SetHeaderTitle "クレームキーワード一覧"
Response.CacheControl = "no-cache"

SQL = "SELECT * FROM T_CLAIM_KEYWORD ORDER BY CK_keyword_id"
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
		<td align="left" class="m0">■クレームキーワードマスタの一覧 <font size="-1">（ID をクリックすると修正することができます）</font></td>
		<td align="right">
			<input type="button" value="新規登録" onclick="location.href='keyword2.asp'">
			<input type="button" value=" 戻る " onclick="location.href='index.asp'">
		</td>
	</tr>
</table>
<table border=1 cellpadding=1 cellspacing=1 width="80%" align="center">
	<tr bgcolor="#E0FFE0">
		<th><nobr>ID</nobr></th>
		<th><nobr>キーワード</nobr></th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td align='center'><a href='keyword3.asp?keyword_id=" & ds("CK_keyword_id").Value & "' title='クレームキーワードを修正します'>"  & ds("CK_keyword_id").Value & "</a></td>"
	Writeln "<td align='left'>" & ReplaceTag(ds("CK_keyword").Value) & "</td>"
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
