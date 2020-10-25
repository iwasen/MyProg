<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:プロジェクト登録・更新登録画面
'******************************************************

'=== PROJECTをDBに登録 ===
Private Sub RegistProject(project_id)
	Dim sql, ds

	sql = "SELECT * FROM T_PROJECT WHERE PJ_project_id=" & project_id
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If ds.EOF Then
		ds.AddNew
		ds("PJ_project_id").Value = project_id
	Else
		ds.Edit
	End If
	ds("PJ_tantousha_id").Value = Request.Form("tantousha_id")
	ds("PJ_project_name").Value = Request.Form("project_name")
	ds("PJ_regist_date").Value = Now
	ds("PJ_summary").Value = Request.Form("summary")
	ds.Update
End Sub

'=== PROJECTをDBから削除 ===
Private Sub DeleteProject(project_id)
	Dim sql, ds

	sql = "DELETE T_PROJECT WHERE PJ_project_id=" & project_id
	ExecuteSQL sql

	sql = "SELECT JB_job_id FROM T_JOB WHERE JB_project_id=" & project_id
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	Do Until ds.EOF
		DeleteJob ds("JB_job_id").Value
		ds.MoveNext
	Loop
End Sub

'=== JOBをDBから削除 ===
Private Sub DeleteJob(job_id)
	Dim sql, ds, mail_id, search_id

	sql = "SELECT * FROM T_JOB WHERE JB_job_id=" & job_id
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If Not ds.EOF Then
		mail_id = ds("JB_mail_id")
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

'=== メイン処理 ===
Dim project_id, project_name, action, msg

SetHeaderTitle "プロジェクト登録"

project_id = Request.Form("project_id")
If IsEmpty(project_id) Then
	SystemError "project_id is empty"
End If

BeginTrans

project_name = "<b>" & ReplaceTag(Request.Form("project_name")) & "</b>"
action = Request.Form("action")
If action = "regist" Then
	RegistProject GetNextVal("PJ_project_id")
	msg = "プロジェクト名：" & project_name & " を登録しました。"
ElseIf action = "update" Then
	RegistProject project_id
	msg = "プロジェクト名：" & project_name & " を更新しました。"
ElseIf action = "delete" Then
	DeleteProject project_id
	msg = "プロジェクト名：" & project_name & " を削除しました。"
End If

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
<p><%=msg%></p>
<input type="button" value=" OK " onclick="location.href='pjt_go.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>

