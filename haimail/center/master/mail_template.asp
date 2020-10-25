<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:送信メールテンプレート一覧表示
'******************************************************

SetHeaderTitle "送信メールテンプレート"
Response.CacheControl = "no-cache"

Dim ds, sql, line

SQL = "SELECT MT_id,MT_guide,MT_subject,MT_from" & vbCrLf & _
			"FROM T_MAIL_TEMPLATE" & vbCrLf & _
			"ORDER BY MT_id"
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

<table align="center" width="100%">
	<tr>
		<td align="left" class="m0">■送信メールテンプレートの一覧 <font size="-1">（種類をクリックすると修正することができます）</font></td>
		<td align="right">
			<input type="button" value=" 戻る " onclick="location.href='index.asp'">
		</td>
	</tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
	<tr bgcolor="#E0FFE0">
		<th>種類</th>
		<th>Subject</th>
		<th>From</th>
	</tr>

<%
line = 1
Do Until ds.EOF
	If line Mod 2 = 0 Then
		Writeln "<tr bgcolor='#FFFFFF'>"
	Else
		Writeln "<tr bgcolor='#FFEEE0'>"
	End If
	Writeln "<td align='left'><a href='mail_template2.asp?id=" & ds("MT_id").Value & "' title='送信メールテンプレートを表示・変更します'>"  & ds("MT_guide").Value & "</a></td>"
	Writeln "<td align='left'>" & BlankToBR(ds("MT_subject").Value) & "</td>"
	Writeln "<td align='left'>" & BlankToBR(ReplaceTag(ds("MT_from").Value)) & "</td>"
	Writeln "</tr>"

	ds.MoveNext
	line = line + 1
Loop
%>

</table>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
