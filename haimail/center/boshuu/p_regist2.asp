<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�o�q�o�^���
'******************************************************

'=== PR��DB�ɓo�^ ===
Private Sub RegistPR()
	Dim haishin_start, haishin_end, sql, ds, fsize

	If Request.Form("haishin_start_y") = "" Then
		haishin_start = Null
	Else
		haishin_start = CDate(Request.Form("haishin_start_y") & "/" & Request.Form("haishin_start_m") & "/" & Request.Form("haishin_start_d"))
	End If

	If Request.Form("haishin_end_y") = "" Then
		haishin_end = Null
	Else
		haishin_end = CDate(Request.Form("haishin_end_y") & "/" & Request.Form("haishin_end_m") & "/" & Request.Form("haishin_end_d"))
	End If

	sql = "SELECT * FROM T_PR WHERE PR_pr_id='" & pr_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If ds.EOF Then
		ds.AddNew
		ds("PR_pr_id").Value = Request.Form("pr_id")
		ds("PR_click_count").Value = 0
	Else
		ds.Edit
	End If
	ds("PR_baitai_id").Value = "03"
	ds("PR_koukoku_id").Value = "10"
	ds("PR_url").Value = Request.Form("url")
	ds("PR_haishin_start").Value = haishin_start
	ds("PR_haishin_end").Value = haishin_end
	ds("PR_affiliate").Value = Request.Form("affiliate")
	ds("PR_bikou").Value = Request.Form("bikou")
	ds("PR_date").Value = Now
	ds("PR_member_id").Value = Right(String(10, "0") & Request.Form("member_id"), 10)
	ds("PR_member_hp").Value = Request.Form("member_hp")
	ds("PR_regist_adr").Value = Request.Form("regist_adr")
	ds.Update
End Sub

'=== PR��DB����폜 ===
Private Sub DeletePR()
	Dim sql

	sql = "DELETE T_PR WHERE PR_pr_id='" & pr_id & "'"
	ExecuteSQL sql
End Sub

'=== ���C������ ===
SetHeaderTitle "�o�q�o�^����"

Dim pr_id, action, msg

pr_id = Request.Form("pr_id")
If IsEmpty(pr_id) Then
	Response.Redirect "personal.asp"
End If

action = Request.Form("action")
If action = "regist" Then
	RegistPR
	msg = "�o�q��o�^���܂����B"
ElseIf action = "update" Then
	RegistPR
	msg = "�o�q���X�V���܂����B"
ElseIf action = "delete" Then
	DeletePR
	msg = "�o�q���폜���܂����B"
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
<input type="button" value=" �߂� " onclick="location.href='personal.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
