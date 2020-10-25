<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:ジョブ内容画面処理
'******************************************************

'=== ジョブ情報取得 ===
Private Sub ReadJob(job_id)
	Dim sql, ds

	sql = "SELECT PJ_project_id,PJ_project_name,JB_job_name,JB_status,JB_mail_type,JB_job_type,JB_parent_job_id,SM_subject,SM_start_date,SM_mail_format" & vbCrLf & _
				"FROM T_PROJECT,T_JOB,T_SEND_MAIL" & vbCrLf & _
				"WHERE JB_job_id=" & job_id & " AND PJ_project_id=JB_project_id AND JB_mail_id=SM_mail_id(+)"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		project_id = ds("PJ_project_id").Value
		project_name = ds("PJ_project_name").Value
		job_name = ds("JB_job_name").Value
		status = ds("JB_status").Value
		subject = ds("SM_subject").value
		start_date = ds("SM_start_date").Value
		mail_type = ds("JB_mail_type").Value
		mail_format = NVL(ds("SM_mail_format").Value, "0")
		job_type = NVL(ds("JB_job_type").Value, "0")
		parent_job_id = ds("JB_parent_job_id").Value
	End If
End Sub

'=== ジョブ種別名取得 ===
Private Function JobTypeText(job_type)
	Select Case job_type
	Case "0"
		JobTypeText = "発信JOB"
	Case "1"
		JobTypeText = "アンケートレスポンスJOB"
	Case "2"
		JobTypeText = "クリックレスポンスJOB"
	End Select
End Function

'=== 子JOB表示 ===
Private Sub ChildJob()
	Dim sql, ds

	sql = "SELECT JB_job_id,JB_job_name from T_JOB where JB_parent_job_id=" & job_id & " ORDER BY JB_job_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If ds.EOF Then
		Response.Write "無し"
	Else
		Do Until ds.EOF
			Response.Write ds("JB_job_id").Value & " " & ReplaceTag(ds("JB_job_name").Value) & "<br>"
			ds.MoveNext
		Loop
	End If		
End Sub

'=== 親JOB表示 ===
Private Sub ParentJob()
	Dim sql, ds

	If IsNull(parent_job_id) Then
		Response.Write "ありません"
	Else
		sql = "SELECT JB_job_id,JB_job_name from T_JOB where JB_job_id='" & parent_job_id & "'"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds.EOF Then
			Response.Write ds("JB_job_id").Value & " " & ReplaceTag(ds("JB_job_name").Value) & "<br>"
		End If
	End If		
End Sub

'=== メイン処理 ===
Dim project_id, project_name, job_id, job_name, status, subject, start_date, back, a_type
Dim mail_type, mail_format, job_type, parent_job_id

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

back = Request.QueryString("back")
If IsEmpty(back) Then
	back = Session("pjt_back")
	If IsEmpty(back) Then
		back = top_page
	End If
Else
	back = "../" & back
	Session("pjt_back") = back
End If

If CheckPermission(PM_REGIST_JOB_A) Then
	a_type = True
ElseIf CheckPermission(PM_REGIST_JOB_B) Then
	a_type = False
Else
	NoPermission
End If

ReadJob job_id

SetHeaderTitle "ジョブ内容登録"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_approve() {
<%If IsNull(subject) Then%>
	alert("メールコンテンツを登録してください。");
<%ElseIf IsNull(start_date) Then%>
	alert("発信条件を登録してください。");
<%Else%>
	if (confirm("このJOBの承認を要求しますか？"))
		location.href = "approve.asp?job_id=<%=job_id%>";
<%End If%>
}
function OnClick_kaijo() {
	if (confirm("このJOBの承認要求を取り消しますか？"))
		location.href = "approve2.asp?job_id=<%=job_id%>";
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../../inc/header.asp"-->

<center>
<form method="post" name="form1">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
<%If CheckPermission(PM_REGIST_PROJECT) Then%>
		<tr>
			<td class="m0">■プロジェクト</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
					<tr>
						<td class="m3" width="25%">プロジェクトID</td>
						<td class="n3"><%=project_id%></td>
					</tr>
					<tr>
						<td class="m3" width="25%">プロジェクト名</td>
						<td class="n3"><%=ReplaceTag(project_name)%></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td><br></td>
		</tr>
<%End If%>
		<tr>
			<td class="m0">■ジョブ</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
					<tr>
						<td class="m3" width="25%">ジョブID</td>
						<td class="n3"><%=job_id%></td>
					</tr>
					<tr>
						<td class="m3">ジョブ名</td>
						<td class="n3"><%=ReplaceTag(job_name)%></td>
					</tr>
					<tr>
						<td class="m3">メールタイプ</td>
						<td class="n3"><%=MailTypeText(mail_type)%></td>
					</tr>
					<tr>
						<td class="m3">メール形式</td>
						<td class="n3"><%=MailFormatText(mail_format)%></td>
					</tr>
					<tr>
						<td class="m3">ジョブ種別</td>
						<td class="n3"><%=JobTypeText(job_type)%></td>
					</tr>
<%If job_type = "0" Then%>
					<tr>
						<td class="m3">子レスポンスJOB</td>
						<td class="n3"><%ChildJob%></td>
					</tr>
<%Else%>
					<tr>
						<td class="m3">親発信JOB</td>
						<td class="n3"><%ParentJob%></td>
					</tr>
<%End If%>
					<tr>
						<td class="m3">承認</td>
						<td class="n3"><%=ApproveStatus(status)%></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
<%If a_type Then%>
	<input type="button" name="click" value="クリックカウンタ" onclick="location.href='click_count.asp?job_id=<%=job_id%>'">
	<input type="button" name="mail" value="メールコンテンツ" onclick="location.href='mail_contents.asp?job_id=<%=job_id%>'">
	<input type="button" name="send" value=" 発信条件 " onclick="location.href='send_condition.asp?job_id=<%=job_id%>'">
<%	If status = "0" Then%>
	<input type="button" name="approve" value=" 承認要求 " onclick="OnClick_approve()">
<%	ElseIf status = "1" Then%>
	<input type="button" name="approve" value=" 承認要求取消 " onclick="OnClick_kaijo()">
<%	End If%>
<%Else%>
<%	If CheckPermission(PM_JOB_CLICK_COUNTER) Then%>
	<input type="button" name="click" value="クリックカウンタ" onclick="location.href='click_count.asp?job_id=<%=job_id%>'">
<%	End If%>
	<input type="button" name="mail" value="メールコンテンツ" onclick="location.href='mail_contents.asp?job_id=<%=job_id%>'">
	<input type="button" name="send" value=" 発信条件 " onclick="location.href='send_condition.asp?job_id=<%=job_id%>'">
	<input type="button" name="estimate" value=" 見積り ">
<%End If%>
	<input type="button" value=" 戻る " onclick="location.href='<%=back%>'">
</form>
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
