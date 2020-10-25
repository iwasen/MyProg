<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:ジョブ登録・更新登録画面
'******************************************************

'=== JOBをDBに登録 ===
Private Sub RegistJob(job_id)
	Dim sql, ds, mail_id, project_id

	project_id = Request.Form("project_id")
	If IsEmpty(project_id) Then
		project_id = RegistProject
	End If

	sql = "SELECT * FROM T_JOB WHERE JB_job_id=" & job_id
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If ds.EOF Then
		ds.AddNew
		ds("JB_job_id").Value = job_id
		ds("JB_get_jushin").Value = "1"
		If Request.Form("mail_type") <> "TG" Then
			ds("JB_get_kansou").Value = "1"
		Else
			ds("JB_get_kansou").Value = "0"
		End If
		ds("JB_get_profile").Value = "0"
		ds("JB_get_sa").Value = 0
		ds("JB_get_fa").Value = 0
		ds("JB_send_timing").Value = "1"
		ds("JB_teikei_andor").Value = "O"
		ds("JB_status").value = "0"
		mail_id = MakeMailID
		ds("JB_mail_id").Value = mail_id
	Else
		mail_id = ds("JB_mail_id").Value
		ds.Edit
	End If
	ds("JB_project_id").Value = project_id
	ds("JB_job_name").Value = Request.Form("job_name")
	ds("JB_mail_type").Value = Request.Form("mail_type")
	ds("JB_job_type").Value = Request.Form("job_type")
	If Request.Form("job_type") = "1" Then
		ds("JB_parent_job_id").Value = Request.Form("parent_job_id1")
	ElseIf Request.Form("job_type") = "2" Then
		ds("JB_parent_job_id").Value = Request.Form("parent_job_id2")
	Else
		ds("JB_parent_job_id").Value = Null
	End If
	ds("JB_regist_date").Value = Now
	ds("JB_orderer_kind").Value = g_login_type
	ds("JB_orderer_id").Value = g_login_id
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
		ds("SM_mail_kind").Value = "S"
		ds("SM_notice_flag").Value = "0"
	Else
		ds.Edit
	End If
	ds("SM_mail_format").Value = Request.Form("mail_format")
	ds.Update

	RegistNoticeAdr mail_id
End Sub

'=== PROJECT登録 ===
Private Function RegistProject()
	Dim sql, ds, project_id

	project_id = GetNextVal("PJ_project_id")

	sql = "SELECT * FROM T_PROJECT"
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	ds.AddNew
	ds("PJ_project_id").Value = project_id
	ds("PJ_tantousha_id").Value = Request.Form("tantousha_id")
	ds("PJ_project_name").Value = Right("0" & Month(Date), 2) & Right("0" & Day(Date), 2) & "発注"
	ds("PJ_regist_date").Value = Now
	ds.Update

	RegistProject = project_id
End Function

'=== 発信通知先登録 ===
Private Sub RegistNoticeAdr(mail_id)
	Dim sql, ds, send_notice, seq_no, mail_adr

	sql = "DELETE T_SEND_NOTICE WHERE SN_mail_id='" & mail_id & "'"
	ExecuteSQL sql

	Set send_notice = Request.Form("send_notice")
	If Not IsEmpty(send_notice) Then
		seq_no = 0
		For Each mail_adr In send_notice
			If Len(mail_adr) > 0 Then
				sql = "INSERT INTO T_SEND_NOTICE VALUES ('" & mail_id & "'," & seq_no & ",'" & mail_adr & "')"
				ExecuteSQL sql
				seq_no = seq_no + 1
			End If
		Next
	End If
End Sub

'=== JOBをコピー ===
Private Sub CopyJob(job_id, copy_job)
	Dim sql, ds1, ds2, ds3, project_id, mail_id, search_id

	project_id = Request.Form("project_id")
	If IsEmpty(project_id) Then
		project_id = RegistProject
	End If

	sql = "SELECT * FROM T_JOB WHERE JB_job_id=" & copy_job
	Set ds1 = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds1.EOF Then
		'送信メールコピー
		If Not IsNull(ds1("JB_mail_id").Value) Then
			sql = "SELECT * FROM T_SEND_MAIL WHERE SM_mail_id='" & ds1("JB_mail_id").Value & "'"
			Set ds2 = CreateDynaset(sql, ORADYN_READONLY)
			If Not ds2.EOF Then
				mail_id = MakeMailID
				sql = "SELECT * FROM T_SEND_MAIL WHERE 0=1"
				Set ds3 = CreateDynaset(sql, ORADYN_DEFAULT)
				ds3.AddNew
				CopyDynaset ds2, ds3
				ds3("SM_mail_id").Value = mail_id
				ds3("SM_body").Value = Empty
				ds3("SM_permit").Value = "0"
				ds3("SM_status").Value = "0"
				ds3("SM_real_send").Value = 0
				ds3("SM_enq_count").Value = 0
				ds3("SM_imp_count").Value = 0
				ds3("SM_notice_flag").Value = "0"
				ds3.Update

				If Not IsNull(ds2("SM_body").Value) Then
					If Not ds2("SM_body").Value.IsNull Then
						If ds2("SM_body").Value.Size > 0 Then
							ds3.Edit
							ds3("SM_body").Value.Write OriginalMail(copy_job, ReadCLOB(ds2("SM_body").Value))
							ds3.Update
						End If
					End If
				End If
			End If
		End If

		'検索条件コピー
		If Not IsNull(ds1("JB_search_id").Value) Then
			sql = "SELECT * FROM T_SEARCH WHERE SE_search_id=" & ds1("JB_search_id").Value
			Set ds2 = CreateDynaset(sql, ORADYN_READONLY)
			If Not ds2.EOF Then
				search_id = GetNextVal("SE_search_id")
				sql = "SELECT * FROM T_SEARCH"
				Set ds3 = CreateDynaset(sql, ORADYN_DEFAULT)
				ds3.AddNew
				CopyDynaset ds2, ds3
				ds3("SE_search_id").Value = search_id
				ds3.Update
			End If
		End If

		'ジョブ情報コピー
		sql = "SELECT * FROM T_JOB"
		Set ds2 = CreateDynaset(sql, ORADYN_DEFAULT)
		ds2.AddNew
		CopyDynaset ds1, ds2
		ds2("JB_job_id").Value = job_id
		ds2("JB_project_id").Value = project_id
		ds2("JB_mail_id").Value = mail_id
		ds2("JB_search_id").Value = search_id
'		If Request.Form("mail_type") <> "TG" Then
'			ds2("JB_get_kansou").Value = "1"
'		Else
'			ds2("JB_get_kansou").Value = "0"
'		End If
		ds2("JB_job_name").Value = Request.Form("job_name")
		ds2("JB_mail_type").Value = Request.Form("mail_type")
		ds2("JB_job_type").Value = Request.Form("job_type")
		If Request.Form("job_type") = "1" Then
			ds2("JB_parent_job_id").Value = Request.Form("parent_job_id1")
		ElseIf Request.Form("job_type") = "2" Then
			ds2("JB_parent_job_id").Value = Request.Form("parent_job_id2")
		Else
			ds2("JB_parent_job_id").Value = Null
		End If
		ds2("JB_status").Value = "0"
		ds2("JB_regist_date").Value = Now
		ds2("JB_orderer_kind").Value = g_login_type
		ds2("JB_orderer_id").Value = g_login_id
		ds2.Update

		'質問情報コピー
		sql = "SELECT * FROM T_QUESTION WHERE QU_mail_id='" & ds1("JB_mail_id").Value & "'"
		Set ds2 = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds2.EOF Then
			sql = "SELECT * FROM T_QUESTION"
			Set ds3 = CreateDynaset(sql, ORADYN_DEFAULT)
			Do Until ds2.EOF
				ds3.AddNew
				CopyDynaset ds2, ds3
				ds3("QU_mail_id").Value = mail_id
				ds3.Update
				ds2.MoveNext
			Loop
		End If

		'添付ファイルコピー
		sql = "SELECT * FROM T_ATTACHMENT WHERE AT_mail_id='" & ds1("JB_mail_id").Value & "'"
		Set ds2 = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds2.EOF Then
			sql = "SELECT * FROM T_ATTACHMENT"
			Set ds3 = CreateDynaset(sql, ORADYN_DEFAULT)
			Do Until ds2.EOF
				ds3.AddNew
				CopyDynaset ds2, ds3
				ds3("AT_mail_id").Value = mail_id
				If Not IsNull(ds2("AT_content_type").Value) Then
					ds3("AT_content_type").Value = mail_id & MakeID(ds2("AT_seq_no").Value, 4)
				End If
				ds3.Update
				ds2.MoveNext
			Loop
		End If

		RegistNoticeAdr mail_id
	End If
End Sub

'=== Dynasetコピー ===
Private Sub CopyDynaset(ds1, ds2)
	Dim i, n

	n = ds1.Fields.Count
	For i = 0 To n - 1
		If ds1(i).OraIDataType = 112 Then
			If Not ds1(i).Value.IsNull Then
				ds2(i).Value = ds1(i).Value
			End If
		Else
			ds2(i).Value = ds1(i).Value
		End If
	Next
End Sub

'=== オリジナルメールコンテンツ取得 ===
Private Function OriginalMail(job_id, body)
	Dim sql, ds

	OriginalMail = body
	sql = "SELECT CC_ct_id,CC_jump_url FROM T_CLICK_COUNT WHERE CC_job_id=" & job_id
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		OriginalMail = Replace(OriginalMail, ClickCountURL(ds(0).Value), ds(1).Value)
		ds.MoveNext
	Loop
End Function

'=== クリックカウント用URL名生成 ===
Private Function ClickCountURL(ct_id)
	ClickCountURL = "http://www.haimail.net/ct/c.asp?" & ct_id & "-%CLICK%"
End Function

'=== JOBをDBから削除 ===
Private Sub DeleteJob(job_id)
	Dim sql, ds, mail_id, search_id

	sql = "SELECT * FROM T_JOB WHERE JB_job_id=" & job_id
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If Not ds.EOF Then
		mail_id = ds("JB_mail_id").Value
		If Not IsNull(mail_id) Then
			sql = "DELETE T_SEND_MAIL WHERE SM_mail_id='" & mail_id & "'"
			ExecuteSQL sql
			sql = "DELETE T_SENDMAIL_RR WHERE SR_mail_id='" & mail_id & "'"
			ExecuteSQL sql
			sql = "DELETE T_QUESTION WHERE QU_mail_id='" & mail_id & "'"
			ExecuteSQL sql
			sql = "DELETE T_SEND_NOTICE WHERE SN_mail_id='" & mail_id & "'"
			ExecuteSQL sql
			sql = "DELETE T_ATTACHMENT WHERE AT_mail_id='" & mail_id & "'"
			ExecuteSQL sql
		End If

		search_id = ds("JB_search_id")
		If Not IsNull(search_id) Then
			sql = "DELETE T_SEARCH WHERE SE_search_id=" & search_id
			ExecuteSQL sql
		End If
		ds.Delete
	End If
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

'=== メイン処理 ===
Dim job_id, job_name, action, msg, copy_job

SetHeaderTitle "ジョブ登録"

job_id = Request.Form("job_id")

BeginTrans

job_name = "<b>" & ReplaceTag(Request.Form("job_name")) & "</b>"
Select Case Request.Form("action")
Case "regist"
	copy_job = Request.Form("copy_job")
	If Len(copy_job) > 0 Then
		CopyJob GetNextVal("JB_job_id"), copy_job
	Else
		RegistJob GetNextVal("JB_job_id")
	End If
	msg = "ジョブ名：" & job_name & " を登録しました。"
Case "update"
	If IsEmpty(job_id) Then
		SystemError "job_id is empty"
	End If
	RegistJob job_id
	msg = "ジョブ名：" & job_name & " を更新しました。"
Case "delete"
	If IsEmpty(job_id) Then
		SystemError "job_id is empty"
	End If
	DeleteJob job_id
	msg = "ジョブ名：" & job_name & " を削除しました。"
End Select

CommitTrans
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
	<p class="np"><%=msg%><br>
		「プロジェクト管理」トップページに戻り、 <br>
		「プロジェクト名」をクリックし、 詳細設定を行って下さい。 </p>
<input type="button" value=" OK " onclick="location.href='pjt_go.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>

