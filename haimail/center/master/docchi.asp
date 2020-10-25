<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:どっち派マスタ一覧表示
'******************************************************

SetHeaderTitle "どっち派マスタ一覧"
Response.CacheControl = "no-cache"

Dim sql, ds, line

SQL = "SELECT * FROM T_DOCCHI_HA ORDER BY DH_item_no"
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
		<td align="left" class="m0">■どっち派マスタの一覧<font size="-1">（インデックス番号 をクリックすると修正することができます）</font></td>
		<td align="right">
			<input type="button" value="新規登録" onclick="location.href='docchi2.asp'">
			<input type="button" value=" 戻る " onclick="location.href='index.asp'">
		</td>
	</tr>
</table>
<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
	<tr bgcolor="#E0FFE0">
		<th><nobr>ｲﾝﾃﾞｯｸｽ番号</nobr></th>
		<th><nobr>表示順序</nobr></th>
		<th><nobr>項目テキスト１</nobr></th>
		<th><nobr>項目テキスト２</nobr></th>
		<th><nobr>有効フラグ</nobr></th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	If ds("DH_valid_flag").Value = 1 Then
		Writeln "<td align='center' width='10%'><a href='docchi3.asp?id=" & ds("DH_index").Value & "' title='どっち派情報を表示・変更します'>"  & ds("DH_index").Value & "</a></td>"
		Writeln "<td align='right' width='10%'>" & ds("DH_item_no").Value & "</td>"
		Writeln "<td align='left' width='35%'>" & ReplaceTag(ds("DH_item1_text").Value) & "</td>"
		Writeln "<td align='left' width='35%'>" & ReplaceTag(ds("DH_item2_text").Value) & "</td>"
		Writeln "<td align='center' width='10%'>○</td>"
		Writeln "</tr>"
	ELse
		Writeln "<td align='center' width='10%'><a href='docchi3.asp?id=" & ds("DH_index").Value & "' title='どっち派情報を表示・変更します'>"  & ds("DH_index").Value & "</a></td>"
		Writeln "<td align='right' width='10%'><Font color='#999999'>" & ds("DH_item_no").Value & "</font></td>"
		Writeln "<td align='left' width='35%'><Font color='#999999'>" & ReplaceTag(ds("DH_item1_text").Value) & "</font></td>"
		Writeln "<td align='left' width='35%'><Font color='#999999'>" & ReplaceTag(ds("DH_item2_text").Value) & "</font></td>"
		Writeln "<td align='center' width='10%'>×</td>"
		Writeln "</tr>"
	End If
	ds.MoveNext
	line = line + 1
Loop
%>
</table>
</center>
<!--#INCLUDE FILE="../inc/footer.asp"-->
</body>
</html>
