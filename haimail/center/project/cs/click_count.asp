<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����ǁE�c�Ƌ��ʃy�[�W
' Content:�N���b�N�J�E���^�ꗗ��ʏ���
'******************************************************

'=== �N���b�N�J�E���g�pURL������ ===
Private Function ClickCountURL(ct_id)
	ClickCountURL = "http://www.haimail.net/ct/c.asp?" & ct_id & "-%CLICK%"
End Function

'=== �W���u���擾 ===
Private Sub ReadJob(job_id)
	Dim sql, ds

	sql = "SELECT JB_status FROM T_JOB WHERE JB_job_id=" & job_id
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		status = ds("JB_status").Value
	End If
End Sub

'=== ���C������ ===
Dim sql, ds, line, job_id, status

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

ReadJob job_id

SetHeaderTitle "�N���b�N�J�E���^�o�^"

sql = "SELECT *" & vbCrLf & _
			"FROM T_CLICK_COUNT" & vbCrLf & _
			"WHERE CC_job_id=" & job_id & vbCrLf & _
			"ORDER BY CC_ct_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
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

<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<form method="post" id=form1 name=form1>
	<tr>
		<td>�����F<%=ds.RecordCount%></td>
		<td align="right">
<%
If status = "0" Or system_id = "center" Then
%>
			<input type="button" value="�V�K�o�^" onclick="location.href='click_count2.asp?job_id=<%=job_id%>'">
<%
End If
%>
			<input type="button" value=" �߂� " onclick="location.href='job_contents.asp?job_id=<%=job_id%>'">
		</td>
	</tr>
	</form>
</table>

<table border=1 cellpadding=0 cellspacing=1 width="100%" class="small">
	<tr bgcolor="#E0FFE0">
		<th>CTID</th>
		<th>URL����</th>
		<th>�J�E���g����</th>
		<th>��ѐ�URL</th>
		<th>����URL</th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td align='center'>" & ds("CC_ct_id").Value & "</td>"
	If status = "0" Or system_id = "center" Then
		Writeln "<td><a href='click_count2.asp?job_id=" & job_id & "&ct_id=" & ds("CC_ct_id").Value & "'>" & ReplaceTag(ds("CC_url_name").Value) & "</a></td>"
	Else
		Writeln "<td>" & ReplaceTag(ds("CC_url_name").Value) & "</td>"
	End If
	Writeln "<td align='center'>" & DateFormat3(ds("CC_start_date").Value, ds("CC_end_date").Value) & "</td>"
	Writeln "<td><a href='" & ds("CC_jump_url").Value & "' target='_blank'>" & ReplaceTag(ds("CC_jump_url").Value) & "</a></td>"
	Writeln "<td>" & ClickCountURL(ds("CC_ct_id").Value) & "</td>"
	Writeln "</tr>"
	ds.MoveNext

	line = line + 1
Loop
%>
</table>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
