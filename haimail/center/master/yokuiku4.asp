<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="master_inc.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:よく行く店マスタ更新処理
'******************************************************

SetHeaderTitle "よく行く店マスタ更新"
Response.CacheControl = "no-cache"

Dim ds, sql, line, id, yokuiku_id, y_category

yokuiku_id = Request.Form("yokuiku_id")

If Not IsEmpty(yokuiku_id) Then
	sql = "SELECT max(YM_index) FROM T_YOKUIKU_MISE"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	id = ds(0) + 1

	sql = "SELECT * FROM T_YOKUIKU_MISE WHERE YM_index='" & yokuiku_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)

	If ds.EOF Then
		ds.Addnew
		ds("YM_index").Value = id
	Else
		ds.Edit
	End If
	ds("YM_category").Value = Request.Form("y_category")
	ds("YM_item_no").Value = Request.Form("item_no")
	ds("YM_item_text").Value = Request.Form("item_text")
	ds("YM_valid_flag").Value = Request.Form("yokuiku")
	ds.Update
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
<p>更新しました。</p>
<table align="center" width="60%">
	<td align="right">カテゴリ ＝ ＜<%=category_name(ds("YM_category").Value)%>＞</td>
</table>
<table border=1 cellpadding=1 cellspacing=1 width="60%" align="center">
	<tr bgcolor="#E0FFE0">
		<th width="50"><nobr>表示順序</nobr></th>
		<th width="400"><nobr>項目テキスト</nobr></th>
		<th width="50"><nobr>有効フラグ</nobr></th>
	</tr>
<%
SQL = "SELECT * FROM T_YOKUIKU_MISE WHERE YM_category ='" & Request.Form("y_category") & "' ORDER BY YM_item_no"
Set ds = CreateDynaset(sql, ORADYN_READONLY)

line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	If ds("YM_valid_flag").Value = 1 Then
		Writeln "<td align='right' width='10%'>" & ds("YM_item_no").Value & "</td>"
		Writeln "<td align='left' width='80%'>" & ReplaceTag(ds("YM_item_text").Value) & "</td>"
		Writeln "<td align='center' width='10%'>○</td>"
	ELse
		Writeln "<td align='right' width='10%'><Font color='#999999'>" & ds("YM_item_no").Value & "</font></td>"
		Writeln "<td align='left' width='80%'><Font color='#999999'>" & ReplaceTag(ds("YM_item_text").Value) & "</font></td>"
		Writeln "<td align='center' width='10%'>×</td>"
	End If
	Writeln "</tr>"
	ds.MoveNext
	line = line + 1
Loop
%>
</table>
<form id=form1 name=form1>
<input type="button" value=" ＯＫ " onclick="location.href='yokuiku.asp'">
</form>
</center>
<!--#INCLUDE FILE="../inc/footer.asp"-->
</body>
</html>
<%
Else
	Response.Redirect("yokuiku.asp")
End If
%>