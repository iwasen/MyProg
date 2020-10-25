<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :‚Í‚¢‚ß[‚éEnetŽ––±‹Ç—pƒy[ƒW
' Content:‘S”zMJOB“o˜^‰æ–Ê
'******************************************************

'=== JOB‚ðDB‚É“o˜^ ===
Private Sub RegistJob(job_id)
	Dim sql, ds

	sql = "SELECT * FROM T_ZENHAISHIN WHERE ZH_job_id=" & job_id
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If ds.EOF Then
		ds.AddNew
		ds("ZH_job_id").Value = job_id
	Else
		ds.Edit
	End If
	ds("ZH_job_name").Value = Request.Form("job_name")
	ds("ZH_notify_adr").Value = Request.Form("notify_adr")
	ds("ZH_date").Value = Now
	ds.Update
End Sub

'=== JOB‚ðDB‚©‚çíœ ===
Private Sub DeleteJob(job_id)
	Dim sql, ds, mail_id

	BeginTrans
	sql = "SELECT * FROM T_ZENHAISHIN WHERE ZH_job_id=" & job_id
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If Not ds.EOF Then
		mail_id = ds("ZH_mail_id")
		If Not IsNull(mail_id) Then
			sql = "DELETE T_SEND_MAIL WHERE SM_mail_id='" & mail_id & "'"
			ExecuteSQL sql
			sql = "DELETE T_SENDMAIL_RR WHERE SR_mail_id='" & mail_id & "'"
			ExecuteSQL sql
		End If
		ds.Delete
	End If
	CommitTrans
End Sub

'=== ƒƒCƒ“ˆ— ===
SetHeaderTitle "‘S”zMJOB“o˜^Œ‹‰Ê"

Dim job_id, job_name, action, msg

job_id = Request.Form("job_id")
If IsEmpty(job_id) Then
	Response.Redirect "index.asp"
End If

job_name = "<b>" & ReplaceTag(Request.Form("job_name")) & "</b>"
action = Request.Form("action")
If action = "regist" Then
	RegistJob GetNextVal("ZH_job_id")
	msg = "‚i‚n‚a–¼ " & job_name & " ‚ð“o˜^‚µ‚Ü‚µ‚½B"
ElseIf action = "update" Then
	RegistJob job_id
	msg = "‚i‚n‚a–¼ " & job_name & " ‚ðXV‚µ‚Ü‚µ‚½B"
ElseIf action = "delete" Then
	DeleteJob job_id
	msg = "‚i‚n‚a–¼ " & job_name & " ‚ðíœ‚µ‚Ü‚µ‚½B"
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
<p><%=msg%></p>
<input type="button" value=" OK " onclick="location.href='index.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
