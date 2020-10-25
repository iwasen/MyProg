<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="master_inc.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:よく行く店マスタ一覧表示
'******************************************************

SetHeaderTitle "よく行く店マスタ一覧"
Response.CacheControl = "no-cache"

Dim sql, ds, line, y_category

%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnChange_y_category() {
	document.form1.submit();
}
function OnClick_form2(y_category) {
	location.href = "yokuiku2.asp?mg=" + y_category;
}
//-->
</SCRIPT>
</head>
<body>
<!--#INCLUDE FILE="../inc/header.asp"-->
<center>
<form method="post" name="form1">
<table align="center" width="100%">
	<tr>
		<td align="left" class="m0">■よく行く店マスタの一覧<font size="-1"> (カテゴリを選択してください)&nbsp;</font></td>
		<td colspan="2" align="left" valign="middle">
<%
If Not IsEmpty(Request.form("y_category")) Then
	Session("y_category") = Request.form("y_category")
End If
y_category = Session("y_category")
%>
			<font class="hc">カテゴリ選択</font>
			<select name="y_category" onchange="OnChange_y_category()">
				<option value=""<%=Selected(y_category, "00")%>>全て</option>
				<option value="01"<%=Selected(y_category, "01")%>>コンビニ</option>
				<option value="02"<%=Selected(y_category, "02")%>>スーパー</option>
				<option value="03"<%=Selected(y_category, "03")%>>その他(業態)</option>
				<option value="04"<%=Selected(y_category, "04")%>>その他(店)</option></td>
			</select>
		</td>
		<td align="right">
<%
If y_category <> "" Then
	Writeln "<input type='button' value='新規登録' onclick='OnClick_form2(""" & y_category & """)'>"
End IF
%>
			<input type="button" value=" 戻る " onclick="location.href='index.asp'">
		</td>
	</tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
	<tr bgcolor="#E0FFE0">
		<th width="10%"><nobr>ｲﾝﾃﾞｯｸｽ番号</nobr></th>
		<th width="10%"><nobr>表示順序</nobr></th>
		<th width="10%"><nobr>カテゴリ</nobr></th>
		<th width="70%"><nobr>項目テキスト</nobr></th>
		<th width="10%"><nobr>有効フラグ</nobr></th>
	</tr>
<%
If Session("y_category") <> "" Then
	SQL = "SELECT * FROM T_YOKUIKU_MISE WHERE YM_category ='" & y_category & "' ORDER BY YM_item_no"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If ds.EOF Then
			Session("y_category") = ""
			SQL = "SELECT * FROM T_YOKUIKU_MISE ORDER BY YM_index"
			Set ds = CreateDynaset(sql, ORADYN_READONLY)
		End If
Else
	SQL = "SELECT * FROM T_YOKUIKU_MISE ORDER BY YM_index"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
End If

line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	If ds("YM_valid_flag").Value = 1 Then
		Writeln "<td align='center' width='10%'><a href='yokuiku3.asp?id=" & ds("YM_index").Value & "' title='よく行く店情報を表示・変更します'>"  & ds("YM_index").Value & "</a></td>"
		Writeln "<td align='right' width='10%'>" & ds("YM_item_no").Value & "</td>"
		Writeln "<td align='center' width='15%'><nobr>" & category_name(ds("YM_category").Value) & "</nobr></td>"
		Writeln "<td align='left' width='70%'>" & ReplaceTag(ds("YM_item_text").Value) & "</td>"
		Writeln "<td align='center' width='10%'>○</td>"
	ELse
		Writeln "<td align='center' width='10%'><a href='yokuiku3.asp?id=" & ds("YM_index").Value & "' title='よく行く店情報を表示・変更します'>"  & ds("YM_index").Value & "</a></td>"
		Writeln "<td align='right' width='10%'><font color='#999999'>" & ds("YM_item_no").Value & "</font></td>"
		Writeln "<td align='center' width='15%'><nobr><font color='#999999'>" & category_name(ds("YM_category").Value) & "</font></nobr></td>"
		Writeln "<td align='left' width='70%'><font color='#999999'>" & ReplaceTag(ds("YM_item_text").Value) & "</font></td>"
		Writeln "<td align='center' width='10%'>×</td>"
	End If
	Writeln "</tr>"
	ds.MoveNext
	line = line + 1
Loop
%>
</table>
</form>
</center>
<!--#INCLUDE FILE="../inc/footer.asp"-->
</body>
</html>
