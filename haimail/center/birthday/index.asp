<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:誕生日メール配信画面処理
'******************************************************

Dim sql, ds, line, seibetsu, age, valid_flag

SetHeaderTitle "誕生日メール一覧"

sql = "SELECT BM_seq_no,BM_priority,BM_seibetsu,BM_age_from,BM_age_to,BM_subject,BM_valid_flag" & vbCrLf & _
			"FROM T_BIRTHDAY_MAIL" & vbCrLf & _
			"ORDER BY BM_priority"
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

<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<tr>
		<td>件数：<%=ds.RecordCount%></td>
		<td align="right">
			<input type="button" value="新規登録" onclick="location.href='birthday1.asp'">
			<input type="button" value=" 戻る " onclick="location.href='../project/index.asp'">
		</td>
	</tr>
</table>

<table border=1 cellpadding=0 cellspacing=1 width="100%">
	<tr bgcolor="#E0FFE0">
		<th width="10%">優先順位</th>
		<th>性別</th>
		<th>年齢範囲</th>
		<th>件名</th>
		<th>有効</th>
	</tr>
<%
line = 1
Do Until ds.EOF
	If IsNull(ds("BM_seibetsu").Value) Then
		seibetsu = "指定無し"
	Else
		seibetsu = SeibetsuText(ds("BM_seibetsu").Value)
	End If

	If IsNull(ds("BM_age_from").Value) And IsNull(ds("BM_age_to").Value) Then
		age = "指定無し"
	Else
		age = NVL(ds("BM_age_from").Value, "") & "～" & NVL(ds("BM_age_to").Value, "")
	End If

	If ds("BM_valid_flag").Value = "1" Then
		valid_flag = "○"
	Else
		valid_flag = "×"
	End If

	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td align='center'><a href='birthday1.asp?id=" & ds("BM_seq_no") & "' title='誕生日メール情報を表示・変更します'>" & ds("BM_priority").Value & "</a></td>"
	Writeln "<td align='center'>" & seibetsu & "</td>"
	Writeln "<td align='center'>" & age & "</td>"
	Writeln "<td>" & ds("BM_subject").Value & "</td>"
	Writeln "<td align='center'>" & valid_flag & "</td>"
	Writeln "</tr>"
	ds.MoveNext
	line = line + 1
Loop
%>
</table>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
