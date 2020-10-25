<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/search.asp"-->
<!--#INCLUDE FILE="../inc/mail_header.asp"-->
<!--#INCLUDE FILE="../inc/estimate.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�W���u���F����
'******************************************************

'=== JOB�����F ===
Private Sub ShouninJob(job_id)
	Dim sql, ds

	sql = "SELECT JB_mail_id,JB_status FROM T_JOB WHERE JB_job_id=" & job_id
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If Not ds.EOF Then
		ds.Edit
		ds("JB_status").Value = "2"
		ds.Update

		sql = "UPDATE T_SEND_MAIL SET SM_permit='1' WHERE SM_mail_id='" & ds("JB_mail_id").Value & "'"
		ExecuteSQL sql
	Else
		SystemError "not found job_id"
	End If
End Sub

'=== ���F�ʒm���[�����M ===
Private Sub SendNotifyMail(job_id)
	Dim sql, ds
	Dim login_dairiten_id, login_dairiten_name, login_tantousha_name, search_id
	Dim search, mail_adr, subject, from, body, contents, click_counter

	'������ID�擾
	sql = "SELECT JB_orderer_kind,JB_orderer_id,JB_search_id FROM T_JOB WHERE JB_job_id=" & job_id
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If ds.EOF Then
		Exit Sub
	End If

	'��������ID�ۑ�
	search_id = ds("JB_search_id").Value

	'�����Җ��擾
	Select Case ds("JB_orderer_kind").Value
	Case LOGIN_AGENT
		sql = "SELECT DA_dairiten_id,DA_name,OP_name1,OP_name2,OP_mail_adr" & vbCrLf & _
					"FROM T_DAIRITEN,T_OPERATOR" & vbCrLf & _
					"WHERE OP_operator_id='" & ds("JB_orderer_id").Value & "' AND OP_dairiten_id=DA_dairiten_id"
	Case LOGIN_CLIENT
		sql = "SELECT CM_dairiten_id,CM_name,TS_name1,TS_name2,TS_mail_adr" & vbCrLf & _
					"FROM T_COMPANY,T_TANTOUSHA" & vbCrLf & _
					"WHERE TS_tantousha_id='" & ds("JB_orderer_id").Value & "' AND TS_company_id=CM_company_id"
	Case Else
		Exit Sub
	End Select
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		login_dairiten_id = ds(0).Value
		login_dairiten_name = ds(1).Value
		login_tantousha_name = ds(2).Value & " " & ds(3).Value
		mail_adr = ds(4).Value
	End If

	'�N���b�N�J�E���^�L���擾
	sql = "SELECT cc_ct_id FROM T_CLICK_COUNT WHERE CC_job_id=" & job_id
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		click_counter = "�L"
	Else
		click_counter = "��"
	End If

	'���������擾
	Set search = New CSearch
	search.ReadDB(search_id)

	'�W���u�o�^���e�擾
	sql = "SELECT JB_job_name,JB_mail_type,PJ_project_id,PJ_project_name,CM_name,DA_name,SM_start_date,SM_send_num,SM_mail_format,SM_subject,SM_body,SM_header,SM_footer,SM_impression" & vbCrLf & _
				"FROM T_JOB,T_PROJECT,T_TANTOUSHA,T_COMPANY,T_DAIRITEN,T_SEND_MAIL" & vbCrLf & _
				"WHERE JB_job_id=" & job_id & " AND PJ_project_id=JB_project_id AND TS_tantousha_id=PJ_tantousha_id AND CM_company_id=TS_company_id AND DA_dairiten_id=CM_dairiten_id AND SM_mail_id=JB_mail_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If ds.EOF Then
		Exit Sub
	End If

	'���[���R���e���c�擾
	If ds("SM_mail_format") = "0" Then
		contents = AddMailHeader(ReadCLOB(ds("SM_body").Value), ds("SM_header").Value, ds("SM_impression").Value, ds("SM_footer").Value)
	Else
		contents = ""
	End If

	'���[���e���v���[�g�擾
	GetSendMailInfo "SHOUNIN", subject, from, body

	'�p�����[�^�Z�b�g
	body = Replace(body, "%LOGIN_DAIRITEN_ID%", login_dairiten_id)
	body = Replace(body, "%LOGIN_DAIRITEN_NAME%", login_dairiten_name)
	body = Replace(body, "%LOGIN_TANTOUSHA_NAME%", login_tantousha_name)
	body = Replace(body, "%PROJECT_ID%", ds("PJ_project_id").Value)
	body = Replace(body, "%PROJECT_NAME%", ds("PJ_project_name").Value)
	body = Replace(body, "%JOB_ID%", job_id)
	body = Replace(body, "%JOB_NAME%", ds("JB_job_name").Value)
	body = Replace(body, "%MENU%", MailTypeText(ds("JB_mail_type").Value))
	body = Replace(body, "%KEISAI_BI%", DateFormat(ds("SM_start_date").Value) & "�i" & GetWeekName(ds("SM_start_date").Value) & "�j")
	body = Replace(body, "%HAISHIN_SUU%", NumFormat(ds("SM_send_num").Value))
	body = Replace(body, "%TANKA%", HaimailTanka(ds("SM_mail_format").Value, ds("JB_mail_type").Value))
	body = Replace(body, "%RYOUKIN%", NumFormat(Estimate(ds("SM_send_num").Value, ds("SM_mail_format").Value, ds("JB_mail_type").Value)))
	body = Replace(body, "%SHIBORIKOMI%", vbCrLf & DeleteLaxxxrLf(search.GetConditionText))
	body = Replace(body, "%SUBJECT%", ds("SM_subject").Value)
	body = Replace(body, "%CLICK_COUNTER%", click_counter)
	body = Replace(body, "%CLIENT_NAME%", ds("CM_name").Value)
	body = Replace(body, "%DAIRITEN_NAME%", ds("DA_name").Value)
	body = Replace(body, "%HONBUN%", contents)

	'���[�����M
	SendMail mail_adr, from, subject, body
End Sub

'=== ���C������ ===
Dim job_id

SetHeaderTitle "�W���u���F"

job_id = Request.Form("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

ShouninJob job_id

SendNotifyMail job_id
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
<p>�W���uID�F<%=job_id%> �����F���܂����B</p>
<input type="button" value=" OK " onclick="location.href='shounin.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
