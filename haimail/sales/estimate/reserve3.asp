<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/mail_header.asp"-->
<!--#INCLUDE FILE="../inc/mail_check.asp"-->
<!--#INCLUDE FILE="../inc/search.asp"-->
<!--#INCLUDE FILE="../inc/estimate.asp"-->
<!--#INCLUDE FILE="../inc/click_counter.asp"-->
<%
'******************************************************
' System :はいめーる・net営業共通ページ
' Content:配信予約受け付け画面処理
'******************************************************

'=== プロジェクト登録処理 ===
Private Sub RegistProject()
	Dim sql, ds

	project_id = GetNextVal("PJ_project_id")
	project_name = Right("0" & Month(Date), 2) & Right("0" & Day(Date), 2) & "発注"

	sql = "SELECT * FROM T_PROJECT"
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	ds.AddNew
	ds("PJ_project_id").Value = project_id
	ds("PJ_tantousha_id").Value = Request.Form("tantousha_id")
	ds("PJ_project_name").Value = project_name
	ds("PJ_regist_date").Value = Now
	ds.Update
End Sub

'=== ジョブ登録処理 ===
Private Sub RegistJob()
	Dim sql, ds

	job_id = GetNextVal("JB_job_id")

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
		ds("JB_status").value = "1"
		mail_id = MakeMailID
		ds("JB_mail_id").Value = mail_id
		search_id = GetNextVal("SE_search_id")
		ds("JB_search_id").Value = search_id
	Else
		mail_id = ds("JB_mail_id").Value
		search_id = ds("SM_search_id").Value
		ds.Edit
	End If
	ds("JB_project_id").Value = project_id
	ds("JB_job_name").Value = Request.Form("job_name")
	ds("JB_mail_type").Value = Request.Form("mail_type")
	ds("JB_job_type").Value = "0"
	ds("JB_regist_date").Value = Now
	ds("JB_orderer_kind").Value = g_login_type
	ds("JB_orderer_id").Value = g_login_id
	ds.Update
End Sub

'=== 送信メール登録処理 ===
Private Sub RegistMail()
	Dim sql, ds
	Dim point_kansou, body, header, footer, impression, id, imp_limit

	start_date = CDate(Request.Form("start_date_y") & "/" & Request.Form("start_date_m") & "/" & Request.Form("start_date_d"))
	end_date = DateAdd("d", 1, start_date)

	body = Request.Form("body")
	If Request.Form("click_counter") = "1" Then
		RegixxxlickCounter body
	End If

	If Request.Form("mail_format") = "0" Then
		id = 1	'TEXT
	Else
		id = 2	'HTML
	End If

	header = GetHeader(id, Request.Form("tantousha_id"))
	footer = GetFooter(id, search.kyoumi_genre1, search.kyoumi_genre2)

	If Request.Form("mail_type") <> "TG" Then
		imp_limit = DateAdd("d", 7, start_date)
		point_kansou = 1
		impression = GetImpression(id, imp_limit)
	Else
		imp_limit = Null
		point_kansou = 0
		impression = Null
	End If

	'ヘッダ、感想文、フッタ付加
	If Request.Form("mail_format") = "0" Then
		body2 = AddMailHeader(body, header, impression, footer)
	Else
		body2 = body
	End If

	sql = "SELECT * FROM T_SEND_MAIL WHERE SM_mail_id='" & mail_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If ds.EOF Then
		ds.AddNew
		ds("SM_mail_id").Value = mail_id
		ds("SM_from").Value = GetFromAddress("S")
		ds("SM_body").Value = Empty
		ds("SM_permit").Value = "0"
		ds("SM_status").Value = "0"
	Else
		ds.Edit
	End If
	ds("SM_mail_kind").Value = "S"
	ds("SM_subject").Value = Request.Form("subject")
	ds("SM_send_num").Value = Request.Form("send_num")
	ds("SM_start_date").Value = start_date
	ds("SM_end_date").Value = end_date
	ds("SM_imp_limit").Value = imp_limit
	ds("SM_sql").Value = Empty
	ds("SM_point_jushin").Value = 1
	ds("SM_point_kansou").Value = point_kansou
	ds("SM_point_enquete").Value = 0
	ds("SM_virgin_mail").Value = "0"
	ds("SM_header").Value = header
	ds("SM_footer").Value = footer
	ds("SM_impression").Value = impression
	ds("SM_mail_format").Value = Request.Form("mail_format")
	ds.Update

	ds.Edit
	ds("SM_body").Value.Write body
	ds("SM_sql").Value.Write search.MakeSQL2(job_id, "", "", "", "")
	ds.Update
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

'=== 発信通知先登録 ===
Private Sub RegistNoticeAdr()
	Dim sql, ds, mail_adr

	Select Case g_login_type
	Case LOGIN_AGENT
		sql = "SELECT OP_mail_adr FROM T_OPERATOR WHERE OP_operator_id='" & g_login_id & "'"
	Case LOGIN_CLIENT
		sql = "SELECT TS_mail_adr FROM T_TANTOUSHA WHERE TS_tantousha_id='" & g_login_id & "'"
	End Select
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		mail_adr = ds(0).Value

		sql = "DELETE T_SEND_NOTICE WHERE SN_mail_id='" & mail_id & "'"
		ExecuteSQL sql

		If Len(mail_adr) > 0 Then
			sql = "INSERT INTO T_SEND_NOTICE VALUES ('" & mail_id & "',0,'" & mail_adr & "')"
			ExecuteSQL sql
		End If
	End If
End Sub

'=== クリックカウンタ登録 ===
Private Sub RegixxxlickCounter(body)
	Dim sql, ds, p1, p2, jump_url, ct_id, click_url, end_date

	sql = "SELECT * FROM T_CLICK_COUNT"
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)

	end_date = DateAdd("m", 1, start_date)

	p1 = 1
	Do While True
		p1 = InStr(p1, body, "http://")
		If p1 = 0 Then
			Exit Do
		End If

		p2 = SearchUrlEnd(p1, body)
		jump_url = Mid(body, p1, p2 - p1)
		If InStr(jump_url, "%CLICK%") = 0 Then
			ct_id = GetNextVal("CC_ct_id")
			click_url = ClickCountURL(ct_id)

			ds.AddNew
			ds("CC_ct_id").Value = ct_id
			ds("CC_job_id").Value = job_id
			ds("CC_url_name").Value = "URL-" & ct_id
			ds("CC_jump_url").Value = jump_url
			ds("CC_start_date").Value = start_date
			ds("CC_end_date").Value = end_date
			ds.Update

			body = Left(body, p1 - 1) & Replace(body, jump_url, click_url, p1, 1)
			p1 = p1 + Len(click_url)
		Else
			p1 = p1 + Len(jump_url)
		End If
	Loop
End Sub

'=== 配信予約通知メール送信 ===
Private Sub SendReserveMail()
	Dim sql, ds
	Dim from, subject, body

	Select Case g_login_type
	Case LOGIN_AGENT
		sql = "SELECT OP_mail_adr FROM T_OPERATOR WHERE OP_operator_id='" & g_login_id & "'"
	Case LOGIN_CLIENT
		sql = "SELECT TS_mail_adr FROM T_TANTOUSHA WHERE TS_tantousha_id='" & g_login_id & "'"
	Case Else
		Exit Sub
	End Select

	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		If Not IsNull(ds(0).Value) Then
			GetSendMailInfo "RESERVE1", subject, from, body
			MakeReserveMail subject, body
			SendMail ds(0).Value, from, subject, body
		End If
	End If

	sql = "SELECT CT_reserve_adr FROM T_CONTROL WHERE CT_control_id='0'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		If Not IsNull(ds(0).Value) Then
			GetSendMailInfo "RESERVE2", subject, from, body
			MakeReserveMail subject, body
			SendMail ds(0).Value, from, subject, body
		End If
	End If
End Sub

'=== 配信予約通知メール送信作成 ===
Private Sub MakeReserveMail(subject, body)
	Dim sql, ds
	Dim login_dairiten_id, login_dairiten_name, login_tantousha_name
	Dim client_name, dairiten_name, click_counter

	Select Case g_login_type
	Case LOGIN_AGENT
		sql = "SELECT DA_dairiten_id,DA_name,OP_name1,OP_name2" & vbCrLf & _
					"FROM T_DAIRITEN,T_OPERATOR" & vbCrLf & _
					"WHERE OP_operator_id='" & g_login_id & "' AND OP_dairiten_id=DA_dairiten_id"
	Case LOGIN_CLIENT
		sql = "SELECT CM_dairiten_id,CM_name,TS_name1,TS_name2" & vbCrLf & _
					"FROM T_COMPANY,T_TANTOUSHA" & vbCrLf & _
					"WHERE TS_tantousha_id='" & g_login_id & "' AND TS_company_id=CM_company_id"
	Case Else
		Exit Sub
	End Select
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		login_dairiten_id = ds(0).Value
		login_dairiten_name = ds(1).Value
		login_tantousha_name = ds(2).Value & " " & ds(3).Value
	End If

	sql = "SELECT CM_name,DA_name" & vbCrLf & _
				"FROM T_TANTOUSHA,T_COMPANY,T_DAIRITEN" & vbCrLf & _
				"WHERE TS_tantousha_id='" & Request.Form("tantousha_id") & "' AND CM_company_id=TS_company_id AND DA_dairiten_id=CM_dairiten_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		client_name = ds("CM_name").Value
		dairiten_name = ds("DA_name").Value
	End If

	If Request.Form("click_counter") = "1" Then
		click_counter = "有"
	Else
		click_counter = "無"
	End If

	subject = Replace(subject, "%CLIENT_NAME%", client_name)

	body = Replace(body, "%NYUUKOU_BI%", DateFormat(Date) & "（" & GetWeekName(Date) & "）")
	body = Replace(body, "%LOGIN_DAIRITEN_ID%", login_dairiten_id)
	body = Replace(body, "%LOGIN_DAIRITEN_NAME%", login_dairiten_name)
	body = Replace(body, "%LOGIN_TANTOUSHA_NAME%", login_tantousha_name)
	body = Replace(body, "%PROJECT_ID%", project_id)
	body = Replace(body, "%PROJECT_NAME%", project_name)
	body = Replace(body, "%JOB_ID%", job_id)
	body = Replace(body, "%JOB_NAME%", Request.Form("job_name"))
	body = Replace(body, "%MENU%", MailTypeText(Request.Form("mail_type")))
	body = Replace(body, "%KEISAI_BI%", DateFormat(start_date) & "（" & GetWeekName(start_date) & "）")
	body = Replace(body, "%HAISHIN_SUU%", NumFormat(Request.Form("send_num")))
	body = Replace(body, "%TANKA%", HaimailTanka(Request.Form("mail_format"), Request.Form("mail_type")))
	body = Replace(body, "%RYOUKIN%", NumFormat(Estimate(Request.Form("send_num"), Request.Form("mail_format"), Request.Form("mail_type"))))
	body = Replace(body, "%SHIBORIKOMI%", vbCrLf & DeleteLaxxxrLf(search.GetConditionText))
	body = Replace(body, "%SUBJECT%", Request.Form("subject"))
	body = Replace(body, "%CLICK_COUNTER%", click_counter)
	body = Replace(body, "%CLIENT_NAME%", client_name)
	body = Replace(body, "%DAIRITEN_NAME%", dairiten_name)
	body = Replace(body, "%HONBUN%", body2)
End Sub

'=== メイン処理 ===
Dim search, project_id, project_name, job_id, mail_id, search_id, start_date, end_date, body2
Dim mail_err, enq_question, imp_question

Set search = New CSearch
search.ReadCondition

'インプレスを発信対象外とする（２月末までの限定処置）====
'If search.jogai_baitai <> "" Then
'	search.jogai_baitai = search.jogai_baitai & ","
'End If
'search.jogai_baitai = search.jogai_baitai & "30"
'====================================================

If TextMailCheck(Request.Form("body"), False, False, enq_question, imp_question, mail_err) = False Then
	Set Session("mail_err") = mail_err
	Session("body_err") = Request.Form("body")
	Writeln "<script LANGUAGE=javascript>"
	Writeln "window.open('../common/mail_err.asp', 'mail_err', 'width=630,scrollbars=yes');"
	Writeln "history.back();"
	Writeln "</script>"
	Response.End
End If

BeginTrans

RegistProject
RegistJob
RegistMail
RegistNoticeAdr
search.WriteDB search_id
StoreQuestion mail_id, enq_question, imp_question

CommitTrans

SendReserveMail

SetHeaderTitle "配信予約受け付け完了"
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
<form>
<p>
配信予約を受け付けました。<br><br>
センターから折り返し配信受付メールをお送りします。
</p>
<input type="button" value="メニューに戻る" onclick="location.href='../top/index.asp'">
    <table width="800" border="3" cellspacing="0" cellpadding="0" bordercolor="cd853f">
      <tr>
        <td>
          <table width="100%" border="0" cellspacing="5" cellpadding="0">
            <tr valign="top"> 
              <td nowrap width="3%" class="info"> 
                <div align="right"><font color="336699">■</font></div>
              </td>
              <td width="97%" class="info">配信予約をいただいた後、センターにて空枠状況を確認させていただきます。空枠状況によっては、予約日に配信されないことがありますことをご了承下さい。その際には、センターからご連絡差し上げます。</td>
            </tr>
            <tr valign="top"> 
              <td nowrap width="3%" class="info"> 
                <div align="right"><font color="336699">■</font></div>
              </td>
              <td width="97%" class="info">配信条件、メール内容をセンターで確認させていただきます。内容によっては、センターからご確認させていただくことを予めご了承下さい。</td>
            </tr>
            <tr valign="top">
              <td nowrap width="3%" class="info">
                <div align="right"><font color="336699">■</font></div>
              </td>
              <td width="97%" class="info">センターにて、承認後ご担当者様にメールにてご連絡させていただきます。</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
