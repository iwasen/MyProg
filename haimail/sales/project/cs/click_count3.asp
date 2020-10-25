<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����ǁE�c�Ƌ��ʃy�[�W
' Content:�N���b�N�J�E���^�o�^�E�X�V�o�^���
'******************************************************

'=== �N���b�N�J�E���^��DB�ɓo�^ ===
Private Sub RegixxxlickCount(ct_id)
	Dim sql, ds

	sql = "SELECT * FROM T_CLICK_COUNT WHERE CC_ct_id=" & ct_id
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If ds.EOF Then
		ds.AddNew
		ds("CC_ct_id").Value = ct_id
	Else
		ds.Edit
	End If
	ds("CC_url_name").Value = Request.Form("url_name")
	ds("CC_job_id").Value = Request.Form("job_id")

	If Request.Form("start_date_y") = "" Then
		ds("CC_start_date").Value = Null
	Else
		ds("CC_start_date").Value = Request.Form("start_date_y") & "/" & Request.Form("start_date_m") & "/" & Request.Form("start_date_d")
	End If

	If Request.Form("end_date_y") = "" Then
		ds("CC_end_date").Value = Null
	Else
		ds("CC_end_date").Value = Request.Form("end_date_y") & "/" & Request.Form("end_date_m") & "/" & Request.Form("end_date_d")
	End If

	ds("CC_jump_url").Value = Request.Form("jump_url")

	ds.Update
End Sub

'=== �N���b�N�J�E���^��DB����폜 ===
Private Sub DeleteClickCount(ct_id)
	Dim sql

	sql = "DELETE T_CLICK_COUNT WHERE CC_ct_id=" & ct_id
	ExecuteSQL sql
End Sub

'=== ���C������ ===
Dim job_id, ct_id, url_name, action, msg

SetHeaderTitle "�N���b�N�J�E���^�o�^"

job_id = Request.Form("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

ct_id = Request.Form("ct_id")
If IsEmpty(ct_id) Then
	SystemError "ct_id is empty"
End If

url_name = "<b>" & ReplaceTag(Request.Form("url_name")) & "</b>"
action = Request.Form("action")
If action = "regist" Then
	ct_id = GetNextVal("CC_ct_id")
	RegixxxlickCount ct_id
	msg = "�N���b�N�J�E���^�F" & url_name & " ��o�^���܂����B"
ElseIf action = "update" Then
	RegixxxlickCount ct_id
	msg = "�N���b�N�J�E���^�F" & url_name & " ���X�V���܂����B"
ElseIf action = "delete" Then
	DeleteClickCount ct_id
	msg = "�N���b�N�J�E���^�F" & url_name & " ���폜���܂����B"
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

<!--#INCLUDE FILE="../../inc/header.asp"-->

<center>
<p><%=msg%></p>
<input type="button" value=" OK " onclick="location.href='click_count.asp?job_id=<%=job_id%>'">
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
