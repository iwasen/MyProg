<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :‚Í‚¢‚ß[‚éEnetŽ––±‹Ç—pƒy[ƒW
' Content:‚o‚q“o˜^‰æ–Ê
'******************************************************

'=== PR‚ðDB‚É“o˜^ ===
Private Sub RegistPR()
	Dim haishin_start, haishin_end, sql, ds, fsize

	If basp.Form(b, "haishin_start_y") = "" Then
		haishin_start = Null
	Else
		haishin_start = CDate(basp.Form(b, "haishin_start_y") & "/" & basp.Form(b, "haishin_start_m") & "/" & basp.Form(b, "haishin_start_d"))
	End If

	If basp.Form(b, "haishin_end_y") = "" Then
		haishin_end = Null
	Else
		haishin_end = CDate(basp.Form(b, "haishin_end_y") & "/" & basp.Form(b, "haishin_end_m") & "/" & basp.Form(b, "haishin_end_d"))
	End If

	sql = "SELECT * FROM T_PR WHERE PR_pr_id='" & pr_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If ds.EOF Then
		ds.AddNew
		ds("PR_pr_id").Value = basp.Form(b, "pr_id")
		ds("PR_contents_text").Value= Empty
		ds("PR_contents_pict").Value = Empty
		ds("PR_click_count").Value = 0
	Else
		ds.Edit
	End If
	ds("PR_baitai_id").Value = basp.Form(b, "baitai_id")
	ds("PR_pr_name").Value = basp.Form(b, "pr_name")
	ds("PR_koukoku_id").Value = basp.Form(b, "koukoku_id")
	'ds("PR_contents_type").Value = basp.Form(b, "contents_type")
	ds("PR_url").Value = basp.Form(b, "url")
	ds("PR_haishin_suu").Value = basp.Form(b, "haishin_suu")
	ds("PR_click_suu").Value = basp.Form(b, "click_suu")
	ds("PR_haishin_start").Value = haishin_start
	ds("PR_haishin_end").Value = haishin_end
	ds("PR_cost").Value = basp.Form(b, "cost")
	ds("PR_affiliate").Value = basp.Form(b, "affiliate")
	ds("PR_bikou").Value = basp.Form(b, "bikou")
	ds("PR_date").Value = Now
	ds.Update

	ds.Edit
	ds("PR_contents_text").Value.Trim(0)
	If basp.Form(b, "contents_text") <> "" Then
		ds("PR_contents_text").Value.Write basp.Form(b, "contents_text")
	End If
	ds.Update

	fsize = basp.FormFileSize(b, "contents_pict")
	If fsize > 0 Then
		ds.Edit
		ds("PR_contents_pict").Value.Trim(0)
		ds("PR_contents_pict").Value.Write basp.FormBinary(b, "contents_pict"), fsize
		ds.Update
	End If
End Sub

'=== PR‚ðDB‚©‚çíœ ===
Private Sub DeletePR()
	Dim sql

	sql = "DELETE T_PR WHERE PR_pr_id='" & pr_id & "'"
	ExecuteSQL sql
End Sub

'=== ƒƒCƒ“ˆ— ===
SetHeaderTitle "‚o‚q“o˜^Œ‹‰Ê"

Dim pr_id, b, basp, action, msg

b = Request.BinaryRead(Request.TotalBytes)
Set basp = Server.CreateObject("basp21")

pr_id = basp.Form(b, "pr_id")
If pr_id = "" Then
	Response.Redirect "general.asp"
End If

action = basp.Form(b, "action")
If action = "regist" Then
	RegistPR
	msg = "‚o‚q‚ð“o˜^‚µ‚Ü‚µ‚½B"
ElseIf action = "update" Then
	RegistPR
	msg = "‚o‚q‚ðXV‚µ‚Ü‚µ‚½B"
ElseIf action = "delete" Then
	DeletePR
	msg = "‚o‚q‚ðíœ‚µ‚Ü‚µ‚½B"
End If
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
<%
If action = "regist" Then
	Writeln "var host = location.host;"
	Writeln "if (host.search('manyu') != -1) {"
	Writeln " var win = open('http://www.haimail.net/pr/create_url.asp?id=" & pr_id & "','_blank','width=1,height=1');"
	'Writeln " win.close();"
	Writeln "}"
End If
%>
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<p><%=msg%></p>
<input type="button" value=" –ß‚é " onclick="location.href='general.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
