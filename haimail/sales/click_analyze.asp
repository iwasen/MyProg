<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����ǁE�c�Ƌ��ʃy�[�W
' Content:�N���b�N��͉�ʏ���
'******************************************************

'=== �W���u���擾 ===
Private Sub ReadJob(job_id)
	Dim sql, ds

	sql = "SELECT PJ_project_id,PJ_project_name,JB_job_name" & vbCrLf & _
				"FROM T_PROJECT,T_JOB" & vbCrLf & _
				"WHERE JB_job_id=" & job_id & " AND PJ_project_id=JB_project_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		project_id = ds("PJ_project_id").Value
		project_name = ds("PJ_project_name").Value
		job_name = ds("JB_job_name").Value
	End If
End Sub

'=== ���C������ ===
Dim project_id, project_name, job_id, job_name

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

ReadJob job_id

SetHeaderTitle "�N���b�N���"
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
<form method="post" name="form1">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
			<tr>
				<td class="m0">
					<p><font color="#000000">�eURL�ʂ̃N���b�N������A�ڍ׃��|�[�g�̃G�N�Z���t�@�C���o�͂��s���܂�</font></p>
					<p>�@</p>
				</td>
			</tr>
			<%If CheckPermission(PM_REGIST_PROJECT) Then%>
			<tr>
				<td class="m0">���v���W�F�N�g</td>
			</tr>
			<tr>
				<td>
					<table border=0 cellspacing=2 cellpadding=3 width='100%'>
						<tr>
							<td class="m3" width="25%">�v���W�F�N�gID</td>
							<td class="n3"><%=project_id%></td>
						</tr>
						<tr>
							<td class="m3" width="25%">�v���W�F�N�g��</td>
							<td class="n3"><%=ReplaceTag(project_name)%></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td><br>
				</td>
			</tr>
			<%End If%>
			<tr>
				<td class="m0">���W���u</td>
			</tr>
			<tr>
				<td>
					<table border=0 cellspacing=2 cellpadding=3 width='100%'>
						<tr>
							<td class="m3" width="25%">�W���uID</td>
							<td class="n3"><%=job_id%></td>
						</tr>
						<tr>
							<td class="m3">�W���u��</td>
							<td class="n3"><%=ReplaceTag(job_name)%></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<p>�@</p><p><%If CheckPermission(PM_CLICK_URL) Then%>
			<input type="button" name="click_log" value="URL�ʃN���b�N��" onclick="location.href='click_contents.asp?job_id=<%=job_id%>'">
			<%End If%> <%If CheckPermission(PM_CLICK_CSV) Then%>
			<input type="button" name="click_csv" value="CSV�擾" onclick="location.href='click_csv.asp?job_id=<%=job_id%>'">
			<%End If%> <%If CheckPermission(PM_CLICK_REPORT) Then%>
			<input type="button" name="click_report" value="�N���b�N���|�[�g" onclick="window.open('click_report.asp?job_id=<%=job_id%>&file=report.dat', '_blank')">
			<%End If%>
			<input type="button" name="back" value=" �߂� " onclick="location.href='../pjt_go.asp'">
		</p>
	</form>
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->
</body>
</html>
