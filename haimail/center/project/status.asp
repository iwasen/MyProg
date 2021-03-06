<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:状態変更画面処理
'******************************************************

'=== ジョブ情報取得 ===
Private Sub ReadJob(job_id)
	Dim sql, ds

	sql = "SELECT PJ_project_id,PJ_project_name,JB_job_name,JB_status" & vbCrLf & _
				"FROM T_JOB,T_PROJECT WHERE JB_job_id=" & job_id & " AND JB_project_id=PJ_project_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		project_id = ds("PJ_project_id").Value
		project_name = ds("PJ_project_name").Value
		job_name = ds("JB_job_name").Value
		status = ds("JB_status").Value
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
Dim project_id, project_name, job_id, job_name, status

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

ReadJob job_id

SetHeaderTitle "ジョブ承認状態変更"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1() {
	return confirm("このジョブを未承認状態へ変更します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" name="form1" action="status2.asp" onsubmit="return OnSubmit_form1()">
	<input type="hidden" name="job_id" value="<%=job_id%>">
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
						<td class="m3" width="25%">ジョブ名</td>
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
	<input type="submit" value="未承認状態へ変更">
	<input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
