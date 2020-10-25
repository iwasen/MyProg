<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:事務局マスタ一覧表示
'******************************************************

SetHeaderTitle "事務局マスタ"
Response.CacheControl = "no-cache"

Dim ds, sql, line

SQL = "SELECT * FROM T_CENTER ORDER BY CN_user_id"
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
<table align="center" width="100%">
	<tr>
		<td align="left" class="m0">■事務局マスタの一覧 <font size="-1">（ユーザID をクリックすると修正することができます）</font></td>
		<td align="right">
			<input type="button" value="新規登録" onclick="location.href='center2.asp'">
			<input type="button" value=" 戻る " onclick="location.href='index.asp'">
		</td>
	</tr>
</table>
<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
	<tr bgcolor="#E0FFE0">
		<th><nobr>ユーザID</nobr></th>
		<th><nobr>パスワード</nobr></th>
		<th><nobr>氏名</nobr></th>
		<th><nobr>メールアドレス</nobr></th>
		<th><nobr>権限</nobr></th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td align='center'><a href='center3.asp?id=" & ds("CN_user_id").Value & "' title='事務局ユーザを表示・変更します'>"  & ds("CN_user_id").Value & "</a></td>"
	Writeln "<td align='left'>" & ReplaceTag(ds("CN_password").Value) & "</td>"
	Writeln "<td align='left'>" & ReplaceTag(ds("CN_name").Value) & "</td>"
	Writeln "<td align='left'>" & BlankToBR(ReplaceTag(ds("CN_mail_adr").Value)) & "</td>"
	Writeln "<td align='center'>" & ds("CN_privilege").Value & "</td>"
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
