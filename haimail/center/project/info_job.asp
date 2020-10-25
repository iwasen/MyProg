<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:ジョブ内容画面処理
'******************************************************

'=== ジョブ情報取得 ===
Private Sub ReadJob(job_id)
	Dim sql, ds

	sql = "SELECT PJ_project_id,PJ_project_name,JB_job_name,JB_status,SM_subject,SM_start_date" & vbCrLf & _
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
	End If
End Sub

'=== 承認状態表示 ===
Private Function ApproveStatus(status)
	Select Case status
	Case "0"
		ApproveStatus = "未承認"
	Case "1"
		ApproveStatus = "承認待ち"
	Case "2"
		ApproveStatus = "承認済み"
	End Select
End Function

'=== メイン処理 ===
Dim project_id, project_name, job_id, job_name, status, subject, start_date

job_id = Request.QueryString("id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

ReadJob job_id

SetHeaderTitle "ジョブ情報表示"
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
<form method="post" name="form1">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
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
						<td class="m3">承認</td>
						<td class="n3"><%=ApproveStatus(status)%></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="button" name="click" value="クリックカウンタ" onclick="location.href='click_count.asp'">
	<input type="button" name="mail" value="メールコンテンツ" onclick="location.href='mail_contents.asp'">
	<input type="button" name="send" value=" 発信条件 " onclick="location.href='send_condition.asp'">
	<input type="button" value=" 閉じる " onclick="window.close()">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
