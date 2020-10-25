<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�r�W�l�X�z�M����
'******************************************************

'=== ���C������ ===
Dim sql, ds, line, nline, status, start_end

SetHeaderTitle "�r�W�l�X�z�MJOB�ꗗ"

nline = Request("nline")
If IsEmpty(nline) Then
	nline = Session("BH_nline")
Else
	Session("BH_nline") = nline
End If
If Len(nline) > 0 Then
	nline = CInt(nline)
Else
	nline = 50
End If

sql = "SELECT BH_job_id,BH_job_name,SM_limit_date,SM_point_enquete,SM_point_kansou,SM_start_date,SM_end_date,SM_status,SM_real_send" & vbCrLf & _
			"FROM T_BSHAISHIN,T_SEND_MAIL" & vbCrLf & _
			"WHERE BH_mail_id=SM_mail_id(+)" & vbCrLf & _
			"ORDER BY BH_job_id DESC"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
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

<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<form method="post">
	<tr>
		<td>�����F<%=ds.RecordCount%></td>
		<td>
			<font class="hc">�\���s��</font><select name="nline" onchange="submit()"><%SelectDispLine(nline)%></select>
		</td>
		<td align="right"><input type="button" value="�V�K�o�^" onclick="location.href='bh_job.asp'"></td>
	</tr>
	</form>
</table>

<table <%=LIST_TABLE%> width="100%" class="small">
	<tr bgcolor="#E0FFE0">
		<th>JOBID</th>
		<th>JOB��</th>
		<th>���M����</th>
		<th>���؂�</th>
		<th>���M��</th>
		<th>���M���</th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"

	If IsNull(ds("SM_start_date").Value) Then
		start_end = "-"
	Else
		start_end = DateFormat2(ds("SM_start_date").Value) & "�`" & DateFormat2(ds("SM_end_date").Value)
	End If

	If IsNull(ds("SM_status").Value) Then
		status = "-"
	Else
		Select Case ds("SM_status").Value
		Case "0"
			status = "�����M"
		Case "1"
			status = "�����M"
		Case "2"
			status = "���M��"
		Case "3"
			status = "���M�װ"
		Case "4"
			status = "���M�ҋ@"
		Case "9"
			status = "���M�I��"
		End Select
	End If

	Writeln "<td align='center'><a href='BH_job.asp?id=" & ds("BH_job_id").Value & "' title='�W���u�o�^����\���E�ύX���܂�'>" & ds("BH_job_id").Value & "</a></td>"
	Writeln "<td><a href='BH_mail.asp?id=" & ds("BH_job_id").Value & "' title='���e�����M������o�^���܂�'>" & ReplaceTag(ds("BH_job_name").Value) & "</a></td>"
	Writeln "<td align='center'>" & start_end & "</td>"
	Writeln "<td align='center'>" & DateFormat2(ds("SM_limit_date").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat(ds("SM_real_send").Value) & "</td>"
	Writeln "<td align='center'>" & status & "</td>"
	Writeln "</tr>"
	ds.MoveNext

	line = line + 1
	If nline <> 0 And line > nline Then
		Exit Do
	End If
Loop
%>
</table>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
