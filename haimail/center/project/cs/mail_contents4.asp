<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:発信コンテンツ表示処理
'******************************************************

'=== クリッカブルURL処理 ===
Private Sub Clickable(body)
	Dim arry_ct_id, arry_jump_url
	Dim p1, p2, p3, p4, disp_url, jump_url, click_url, ct_id, i

	arry_ct_id = Split(Request.Form("ct_id"), "||")
	arry_jump_url = Split(Request.Form("jump_url"), "||")

	p1 = 1
	Do While True
		p1 = InStr(p1, body, "http://")
		If p1 = 0 Then
			Exit Do
		End If

		p2 = SearchUrlEnd(p1, body)
		disp_url = Mid(body, p1, p2 - p1)

		p3 = InStr(disp_url, "/ct/c.asp?")
		If p3 = 0 Then
			jump_url = disp_url
		Else
			jump_url = ""
			p4 = InStr(disp_url, "-%CLICK%")
			If p4 > 0 Then
				ct_id = Mid(disp_url, p3 + 10, p4 - p3 - 10)
				For i = 0 To Ubound(arry_ct_id)
					If arry_ct_id(i) = ct_id Then
						jump_url = arry_jump_url(i)
						Exit For
					End If
				Next
				If jump_url = "" Then
					jump_url = disp_url
				End If
			End If
		End If

		If jump_url <> "" Then
			click_url = "<a href=""" & jump_url & """ target=""_blank"">" & disp_url & "</a>"
			body = Left(body, p1 - 1) & click_url & Mid(body, p2)
			p1 = p1 + Len(click_url)
		Else
			p1 = p1 + Len(disp_url)
		End If
	Loop
		
End Sub

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

'=== HTML画像表示処理 ===
Private Sub HtmlImage(body)
	Dim sql, ds, job_id, site_url

	job_id = Request.Form("job_id")
	site_url = GetControlInfo("CT_site_url")

	sql = "SELECT AT_file_name,AT_content_type FROM T_ATTACHMENT,T_JOB" & vbCrLf & _
				" WHERE JB_job_id=" & job_id & " AND AT_mail_id=JB_mail_id AND AT_content_type IS NOT NULL"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		body = Replace(body, ds("AT_file_name").Value, site_url & "pict.asp?cid=" & ds("AT_content_type").Value)
		ds.MoveNext
	Loop
End Sub

'=== メイン処理 ===
SetHeaderTitle "発信コンテンツ表示"

Dim subject, body, header, impression, footer, mail_format

subject = Request.Form("subject")
body = LaxxxrLf(Request.Form("body"))
header = Request.Form("header")
impression =Request.Form("impression")
footer = Request.Form("footer")
mail_format = Request.Form("mail_format")

'ヘッダ付加
If Len(header) > 0 Then
	body = LaxxxrLf(header) & vbCrLf & body
End If

'感想文付加
If Len(impression) > 0 Then
	body = body & vbCrLf & LaxxxrLf(impression)
End If

'フッタ付加
If Len("footer") > 0 Then
	body = body & vbCrLf & LaxxxrLf(footer)
End If

If mail_format = "0" Then
	'クリッカブルURL対応
	Clickable body
Else
	'HTML画像表示処理
	HtmlImage body
End If
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../../haimail.css">
</head>
<body>

<center>
<table border=0 cellspacing=1 cellpadding=0>
	<tr>
		<td class="m0">■<%=subject%></td>
	</tr>
	<tr>
		<td colspan=2>
			<table border=1 cellspacing=0 cellpadding=3 bordercolor="navy" width="100%">
				<tr>
					<td>
<%
If mail_format = "0" Then
	Writeln	"<pre class='np' style='margin:0'>" & body & "</pre>"
Else
	Writeln	body
End If
%>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</center>

<center>
<input type="button" value=" 閉じる " onclick="window.close()">
</center>

</body>
</html>
