<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/search.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:メンバー検索結果画面処理
'******************************************************

SetHeaderTitle "メンバー検索結果"

Dim search, result, sql, ds, find, all, condition, count

result = Request.Form("result")

sql = "SELECT COUNT(*) FROM T_MEMBER WHERE MM_status='0'"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	all = 0
Else
	all = ds(0).Value
End If

Set search = New CSearch
search.GetData
condition = search.GetConditionText

If result = "1" Then
	sql = "SELECT MM_member_id FROM T_MEMBER WHERE " & search.MakeSQL & " ORDER BY MM_member_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	find = ds.RecordCount
Else
	sql = "SELECT COUNT(*) FROM T_MEMBER WHERE " & search.MakeSQL
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If ds.EOF Then
		find = 0
	Else
		find = ds(0).Value
	End If
End If

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
<table border=0 cellspacing=0 cellpadding=0 width='700'>
	<tr>
		<td class="m0">■検索結果</td>
	</tr>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width='100%'>
				<tr>
					<td class="m4" width="25%">検索された会員数</td>
					<td class="n4"><%=NumFormat(find)%></td>
				</tr>
				<tr>
					<td class="m4">全有効会員数</td>
					<td class="n4"><%=NumFormat(all)%></td>
				</tr>
				<tr>
					<td class="m4">検索された割合</td>
					<td class="n4"><%=FormatNumber(CDbl(find) / all * 100, 1, -1, 0, 0)%>%</td>
				</tr>
				<tr>
					<td class="m4">検索条件</td>
					<td class="n4"><font size="-1"><%=Replace(condition, vbCrLF, "<br>")%></font></td>
				</tr>
<%
If result = "1" Then
	Writeln "<tr>"
	Writeln "<td class='m4'>会員ID</td>"
	Writeln "<td class='n4'><textarea cols=66 rows=10 readonly>"
	count = 0
	Do Until ds.EOF
		If count <> 0 Then
			Response.Write ","
		End If
		Response.Write ds(0).Value
		count = count + 1
		ds.MoveNext
	Loop
	Writeln "</textarea></td>"
	Writeln "</tr>"
End If
%>
			</table>
		</td>
	</tr>
</table>

<p>
<input type="button" value="　閉じる　" onclick="window.close()">
</p>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
