<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<!--#INCLUDE FILE="../../inc/mail_check.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:メールコンテンツ登録処理
'******************************************************

'=== メールコンテンツをDBに登録 ===
Private Sub RegistMail(job_id, body, enq_question, imp_question)
	Dim sql, ds, mail_id

	BeginTrans

	sql = "SELECT * FROM T_JOB WHERE JB_job_id=" & job_id
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If Not ds.EOF Then
		ds.Edit
		ds("JB_get_jushin").Value = basp.Form(b, "get_jushin")
		ds("JB_get_kansou").Value = basp.Form(b, "get_kansou")
		ds("JB_get_profile").Value = basp.Form(b, "get_profile")
		ds("JB_get_sa").Value = basp.Form(b, "get_sa")
		ds("JB_get_fa").Value = basp.Form(b, "get_fa")
		If IsNull(ds("JB_mail_id").Value) Then
			mail_id = MakeMailID
			ds("JB_mail_id").Value = mail_id
		Else
			mail_id = ds("JB_mail_id").Value
		End If
		ds.Update

		sql = "SELECT * FROM T_SEND_MAIL WHERE SM_mail_id='" & mail_id & "'"
		Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
		If ds.EOF Then
			ds.AddNew
			ds("SM_mail_id").Value = mail_id
			ds("SM_from").Value = GetFromAddress("S")
			ds("SM_body").Value = Empty
			ds("SM_permit").Value = "0"
			ds("SM_status").Value = "0"
			ds("SM_virgin_mail").Value = "0"
		Else
			ds.Edit
			ds("SM_body").Value.Trim 0
		End If
		ds("SM_mail_kind").Value = "S"
		ds("SM_subject").Value = basp.Form(b, "subject")
		ds("SM_header").Value = basp.Form(b, "header")
		ds("SM_footer").Value = basp.Form(b, "footer")
		ds("SM_impression").Value = basp.Form(b, "impression")
		ds("SM_html_pict").Value = basp.Form(b, "html_pict")
		ds.Update

		ds.MoveLast
		ds.Edit
		ds("SM_body").Value.Write basp.Form(b, "body")
		ds.Update
	End If

	StoreQuestion mail_id, enq_question, imp_question

	CommitTrans
End Sub

'=== Fromアドレス取得 ===
Private Function GetFromAddress(mail_kind)
	Dim sql, ds

	sql = "SELECT MA_mail_adr FROM T_MAIL_TYPE WHERE MA_mail_kind='" & mail_kind & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If ds.EOF Then
		SystemError "not found mail_kind"
	End If

	GetFromAddress = ds(0).Value
End Function

'=== クリックカウンタ登録 ===
Private Sub RegixxxlickCounter(job_id)
	Dim sql, ds, ct_id, url_name, jump_url, i

	ct_id = Split(basp.Form(b, "ct_id"), "||")
	url_name = Split(basp.Form(b, "url_name"), "||")
	jump_url = Split(basp.Form(b, "jump_url"), "||")

	sql = "SELECT * FROM T_CLICK_COUNT"
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	For i = 0 To Ubound(ct_id)
		If ct_id(i) <> "" Then
			ds.AddNew
			ds("CC_ct_id").Value = CLng(ct_id(i))
			ds("CC_job_id").Value = job_id
			ds("CC_url_name").Value = url_name(i)
			ds("CC_jump_url").Value = jump_url(i)
			ds("CC_start_date").Value = basp.Form(b, "start_date")
			ds("CC_end_date").Value = basp.Form(b, "end_date")
			ds.Update
		End If
	Next
End Sub

'=== 添付ファイル保存 ===
Private Sub SaveAttachment(job_id)
	Dim sql, ds, mail_id, i, fsize

	sql = "SELECT JB_mail_id FROM T_JOB WHERE JB_job_id=" & job_id
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		mail_id = ds(0).Value

		If basp.Form(b, "attachment") = "1" Then
			For i = 1 To 5
				fsize = basp.FormFileSize(b, "attachment" & i)
				If fsize > 0 Then
					sql = "SELECT * FROM T_ATTACHMENT WHERE AT_mail_id='" & mail_id & "' AND AT_seq_no=" & i
					Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
					If ds.EOF Then
						ds.AddNew
						ds("AT_mail_id").Value = mail_id
						ds("AT_seq_no").Value = i
					Else
						ds.Edit
					End If
					ds("AT_file_name").Value = GetFileName(basp.FormFileName(b, "attachment" & i))
					ds("AT_file_body").Value = Empty
					ds.Update

					ds.Edit
					ds("AT_file_body").Value.Trim(0)
					ds("AT_file_body").Value.Write basp.FormBinary(b, "attachment" & i), fsize
					ds.Update
				End If
			Next
		Else
			sql = "DELETE T_ATTACHMENT WHERE AT_mail_id='" & mail_id & "' AND AT_content_type IS NULL"
			ExecuteSQL sql
		End If
	End If
End Sub

'=== パス名からファイル名だけ取得 ===
Private Function GetFileName(path)
	Dim n

	n = InStrRev(path, "\")
	If n > 0 Then
		GetFileName = Mid(path, n + 1)
	Else
		GetFileName = path
	End If
End Function

'=== メイン処理 ===
SetHeaderTitle "メールコンテンツ登録"

Dim job_id, enq_check, imp_check, enq_question, imp_question, mail_err, msg
Dim subject, body, header, impression, footer, b, basp, mail_format

b = Request.BinaryRead(Request.TotalBytes)
Set basp = Server.CreateObject("basp21")

job_id = basp.Form(b, "job_id")
If job_id = "" Then
	SystemError "job_id is empty"
End If

If Number("0" & basp.Form(b, "get_sa")) > 0 Or Number("0" & basp.Form(b, "get_fa")) > 0 Then
	enq_check = True
Else
	enq_check = False
End If

If basp.Form(b, "get_kansou") = "1" Then
	imp_check = True
Else
	imp_check = False
End If

subject = basp.Form(b, "subject")
body = LaxxxrLf(basp.Form(b, "body"))
header = basp.Form(b, "header")
impression =basp.Form(b, "impression")
footer = basp.Form(b, "footer")
mail_format = basp.Form(b, "mail_format")

SaveAttachment job_id

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

'メールコンテンツチェック
If MailCheck(mail_format, body, enq_check, imp_check, enq_question, imp_question, mail_err) = False Then
	Set Session("mail_err") = mail_err
	Session("body_err") = body
	Writeln "<script LANGUAGE=javascript>"
	Writeln "window.open('../../common/mail_err.asp', 'mail_err', 'width=630,scrollbars=yes');"
	Writeln "history.back();"
	Writeln "</script>"
	Response.End
End If

RegistMail job_id, body, enq_question, imp_question
RegixxxlickCounter job_id

'画像ファイル取得
If mail_format <> "0" Then
	Response.Redirect "mail_contents6.asp?job_id=" & job_id
End If

msg = "メールコンテンツを登録しました。"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../../inc/header.asp"-->

<center>
<p><%=msg%></p>
<input type="button" value=" OK " onclick="location.href='job_contents.asp?job_id=<%=job_id%>'">
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
