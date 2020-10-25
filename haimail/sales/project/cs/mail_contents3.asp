<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<!--#INCLUDE FILE="../../inc/html.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:クリックカウンタ自動設定処理
'******************************************************

'=== クリックカウント用URL名生成 ===
Private Function ClickCountURL(ct_id)
	ClickCountURL = "http://www.haimail.net/ct/c.asp?" & ct_id & "-%CLICK%"
End Function

'=== URLの開始位置検索 ===
Private Function SearchUrlStart(pos, body, mail_format)
	Dim p1, p2
	If mail_format = "0" Then
		p1 = InStr(pos, body, "http://")
		p2 = InStr(pos, body, "https://")
		If p1 > 0 And p2 > 0 Then
			If p1 < p2 Then
				SearchUrlStart = p1
			Else
				SearchUrlStart = p2
			End If
		ElseIf p1 > 0 And p2 = 0 Then
			SearchUrlStart = p1
		ElseIf p1 = 0 And p2 > 0 Then
			SearchUrlStart = p2
		Else
			SearchUrlStart = 0
		End If
	Else
		SearchUrlStart = CheckATag(pos, body)
	End If
End Function

'=== <A>タグ検索 ===
Private Function CheckATag(pos, s)
	Dim n1, n2, element_name, element_val, tag_name, href
	Dim sql, ds

	CheckATag = 0
	n1 = pos
	Do While True
		n2 = InStr(n1, s, "<")
		If n2 > 0 Then
			tag_name = ""
			href = ""
			n2 = n2 + 1
			Do While True
				element_name = HtmlElementName(s, n2)
				If element_name = "" Then
					Exit Do
				End If

				If tag_name = "" Then
					tag_name = element_name
				Else
					element_val = HtmlElementValue(s, n2)
					If element_name = "href" Then
						href = element_val
					End If
				End If
			Loop

			If tag_name = "a" And Left(href, 7) = "http://" Then
				CheckATag = InStr(n1, s, "http://")
				Exit Do
			End If

			If tag_name = "a" And Left(href, 8) = "https://" Then
				CheckATag = InStr(n1, s, "https://")
				Exit Do
			End If

			n1 = n2 + 1
		Else
			Exit Do
		End If
	Loop
End Function

'=== URLの終了位置検索 ===
Private Function SearchUrlEnd(pos, body)
	SearchUrlEnd = FindStr(pos, body, " 　""'>" & vbCrLf)
	If SearchUrlEnd = 0 Then
		SearchUrlEnd = Len(body) + 1
	End If
End Function

'=== 文字検索 ===
Private Function FindStr(pos, str, find)
	Dim i, sz

	FindStr = 0
	sz = Len(str)
	For i = pos To sz
		If InStr(find, Mid(str, i, 1)) > 0 Then
			FindStr = i
			Exit For
		End If
	Next
End Function

'=== メイン処理 ===
Dim job_id, body, ct_id, p1, p2, jump_url, click_url, line, mail_format, start_date, end_date

job_id = Request.Form("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

body = Request.Form("body")
If IsEmpty(job_id) Then
	SystemError "body is empty"
End If

mail_format = Request.Form("mail_format")

start_date = Now
end_date = DateAdd("m", 1, start_date)

SetHeaderTitle "クリックカウンタ自動設定"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function SetDate() {
	var f = document.form1;
	var start_date, end_date

	if (f.start_date_y.value != "")
		start_date = f.start_date_y.value + "/" + f.start_date_m.value + "/" + f.start_date_d.value
	else
		start_date = "";

	if (f.end_date_y.value != "")
		end_date = f.end_date_y.value + "/" + f.end_date_m.value + "/" + f.end_date_d.value
	else
		end_date = "";

	f = opener.form1;
	f.start_date.value = start_date;
	f.end_date.value = end_date;
}
function SetURL() {
	var f = document.form1;
	var f2 = opener.form1;
	var line = Number(f.line.value);
	var body = f.body.value;
	var m, ct_id, url_name, jump_url;

	ct_id = f2.ct_id.value;
	url_name = f2.url_name.value;
	jump_url = f2.jump_url.value;
	for (var i = 1; i <= line; i++) {
		m = "%CTID=" + f("ct_id" + i).value + "%"
		if (f("ct_id" + i).checked) {
			if (ct_id != "") {
				ct_id += "||";
				url_name += "||";
				jump_url += "||";
			}
			ct_id += f("ct_id" + i).value;
			url_name += f("url_name" + i).value;
			jump_url += f("jump_url" + i).value;
			body = body.replace(m, f("click_url" + i).value);
		} else
			body = body.replace(m, f("jump_url" + i).value);
	}
	f2.body.value = body;
	f2.ct_id.value = ct_id;
	f2.url_name.value = url_name;
	f2.jump_url.value = jump_url;
}
function OnClick_OK() {
	SetDate();
	SetURL();
	window.close();
}
//-->
</SCRIPT>
</head>
<body>

<form name="form1">
<center>
<table border=0 cellpadding=0 cellspacing=0 width="100%">
	<tr>
		<td class="m0">■クリックカウンタ自動設定</td>
		</td>
	</tr>
	<tr>
		<td>
			<table border=0 cellpadding=0 cellspacing=1 width="100%">
				<tr>
					<td class="m3">カウンタ期間(開始)</td>
					<td class="n3">
						<select name="start_date_y"><%SelectYear2 Year(start_date)%></select>年&nbsp;
						<select name="start_date_m"><%SelectMonth Month(start_date)%></select>月&nbsp;
						<select name="start_date_d"><%SelectDay Day(start_date)%></select>日&nbsp;<font class="note">（開始を指定しない場合は年をブランクにしてください）</font>
					</td>
				</tr>
				<tr>
					<td class="m3">カウンタ期間(終了)</td>
					<td class="n3">
						<select name="end_date_y"><%SelectYear2 Year(end_date)%></select>年&nbsp;
						<select name="end_date_m"><%SelectMonth Month(end_date)%></select>月&nbsp;
						<select name="end_date_d"><%SelectDay Day(end_date)%></select>日&nbsp;<font class="note">（終了を指定しない場合は年をブランクにしてください）</font>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><br></td>
	</tr>
	<tr>
		<td>
			<table border=1 cellpadding=0 cellspacing=1 width="100%">
				<tr bgcolor="#E0FFE0">
					<th>CTID</th>
					<th>URL名称</th>
					<th>飛び先URL</th>
					<th>生成URL</th>
					<th>生成</th>
				</tr>
<%
line = 1
p1 = 1
Do While True
	p1 = SearchUrlStart(p1, body, mail_format)
	If p1 = 0 Then
		Exit Do
	End If

	p2 = SearchUrlEnd(p1, body)
	jump_url = Mid(body, p1, p2 - p1)
	If InStr(jump_url, "%CLICK%") = 0 Then
		ct_id = GetNextVal("CC_ct_id")
		click_url = ClickCountURL(ct_id)

		Writeln "<tr class='tc" & line Mod 2 & "'>"
		Writeln "<td align='center'>" & ct_id & "</td>"
		Writeln "<td><input type='text' name='url_name" & line & "' size=50 maxlength=100 value='URL-" & ct_id & "'></td>"
		Writeln "<td><input type='hidden' name='jump_url" & line & "' value='" & jump_url & "'><a href='" & jump_url & "' target='_blank'>" & ReplaceTag(jump_url) & "</a></td>"
		Writeln "<td><input type='hidden' name='click_url" & line & "' value='" & click_url & "'>" & click_url & "</td>"
		Writeln "<td align='center'><input type='checkbox' name='ct_id" & line & "' value='" & ct_id & "' checked></td>"
		Writeln "</tr>"

		click_url = "%CTID=" & ct_id & "%"
		body = Left(body, p1 - 1) & Replace(body, jump_url, click_url, p1, 1)
		line = line + 1
		p1 = p1 + Len(click_url)
	Else
		p1 = p1 + Len(jump_url)
	End If
Loop
%>
			</table>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="line" value="<%=line-1%>">
<textarea name="body" style="display:none"><%=HTMLEncode(body)%></textarea>
<input type="button" value="　OK　" onclick="OnClick_OK()">
<input type="button" value="キャンセル" onclick="window.close()">
</center>
</form>

</body>
</html>
