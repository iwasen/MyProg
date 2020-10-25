<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :‚Í‚¢‚ß[‚éEnetŽ––±‹Ç—pƒy[ƒW
' Content:ƒrƒWƒlƒX”zMŒ´e•”­MðŒ“o˜^‰æ–Ê
'******************************************************

'=== Œ´e•”­MðŒ‚ðDB‚É“o˜^ ===
Private Sub RegistMail()
	Dim start_date, end_date, limit_date, mail_id, notify_adr
	Dim sql, ds

	If Request.Form("start_date_y") = "" Then
		start_date = Null
	Else
		start_date = CDate(Request.Form("start_date_y") & "/" & Request.Form("start_date_m") & "/" & Request.Form("start_date_d") & " " & Request.Form("start_date_h") & ":0:0")
	End If

	If Request.Form("end_date_y") = "" Then
		end_date = Null
	Else
		end_date = CDate(Request.Form("end_date_y") & "/" & Request.Form("end_date_m") & "/" & Request.Form("end_date_d") & " 23:59:59")
	End If

	If Request.Form("limit_date_y") = "" Then
		limit_date = Null
	Else
		limit_date = CDate(Request.Form("limit_date_y") & "/" & Request.Form("limit_date_m") & "/" & Request.Form("limit_date_d") & " " & Request.Form("limit_date_h") & ":0:0")
	End If

	BeginTrans

	sql = "SELECT * FROM T_BSHAISHIN WHERE BH_job_id=" & job_id
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If Not ds.EOF Then
		ds.Edit

		If IsNull(ds("BH_mail_id").Value) Then
			mail_id = MakeMailID
			ds("BH_mail_id").Value = mail_id
		Else
			mail_id = ds("BH_mail_id").Value
		End If
		notify_adr = ds("BH_notify_adr")
		ds.Update

		sql = "SELECT * FROM T_SEND_MAIL WHERE SM_mail_id='" & mail_id & "'"
		Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
		If ds.EOF Then
			ds.AddNew
			ds("SM_mail_id").Value = mail_id
			ds("SM_body").Value = Empty
			ds("SM_permit").Value = "1"
			ds("SM_status").Value = "0"
			ds("SM_virgin_mail").Value = "0"
			ds("SM_mail_format").Value = "0"
		Else
			ds.Edit
			ds("SM_body").Value.Trim 0
		End If
		ds("SM_mail_kind").Value = "B"
		ds("SM_subject").Value = Request.Form("subject")
		ds("SM_from").Value = notify_adr
		ds("SM_start_date").Value = start_date
		ds("SM_end_date").Value = end_date
		ds("SM_limit_date").Value = limit_date
		ds("SM_sql").Value = Null
		ds("SM_send_num").Value = Null
		ds("SM_point_enquete").Value = Request.Form("point_enquete")
		ds("SM_point_kansou").Value = Request.Form("point_kansou")
		ds.Update

		ds.MoveLast
		ds.Edit
		ds("SM_body").Value.Write Request.Form("body")
		ds.Update
	End If

	CommitTrans
End Sub

'=== ƒƒCƒ“ˆ— ===
SetHeaderTitle "Œ´e•”­MðŒ“o˜^Œ‹‰Ê"

Dim job_id, msg

job_id = Request.Form("job_id")
If IsEmpty(job_id) Then
	Response.Redirect "index.asp"
End If

RegistMail
msg = "Œ´e•”­MðŒ‚ð“o˜^‚µ‚Ü‚µ‚½B"
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
