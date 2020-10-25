<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�S�z�M����
'******************************************************

'=== ���C������ ===
Dim sql, ds, line, nline, status, start_end, job_id, count

SetHeaderTitle "�S�z�MJOB�ꗗ"

nline = Request("nline")
If IsEmpty(nline) Then
	nline = Session("zh_nline")
Else
	Session("zh_nline") = nline
End If
If Len(nline) > 0 Then
	nline = CInt(nline)
Else
	nline = 50
End If

sql = "SELECT ZH_job_id,ZH_job_name,SM_limit_date,SM_point_enquete,SM_point_kansou,SM_start_date,SM_end_date,SM_status,SM_real_send,SM_enq_count,SM_imp_count" & vbCrLf & _
			"FROM T_SEND_MAIL,T_ZENHAISHIN" & vbCrLf & _
			"WHERE ZH_mail_id=SM_mail_id(+)" & vbCrLf & _
			"ORDER BY ZH_job_id DESC"
If nline > 0 Then
	sql = "SELECT * FROM (" & sql & ") WHERE ROWNUM<=" & nline
End If
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
		<td align="right"><input type="button" value="�V�K�o�^" onclick="location.href='zh_job.asp'"></td>
	</tr>
	</form>
</table>

<table <%=LIST_TABLE%> width="100%" class="small">
	<tr bgcolor="#E0FFE0">
		<th rowspan=2>JOBID</th>
		<th rowspan=2>JOB��</th>
		<th rowspan=2>���M����</th>
		<th rowspan=2>���؂�</th>
		<th colspan=2>���Z�|�C���g</th>
		<th rowspan=2>���M��</th>
		<th colspan=2>�ԐM��</th>
		<th rowspan=2>���M���</th>
	</tr>
	<tr bgcolor="#E0FFE0">
		<th>�ݹ��</th>
		<th>���z��</th>
		<th>�ݹ��</th>
		<th>���z��</th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"

	job_id = ds("ZH_job_id").Value

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

	Writeln "<td align='center'><a href='zh_job.asp?id=" & job_id & "' title='�W���u�o�^����\���E�ύX���܂�'>" & job_id & "</a></td>"
	Writeln "<td><a href='zh_mail.asp?id=" & job_id & "' title='���e�����M������o�^���܂�'>" & ReplaceTag(ds("ZH_job_name").Value) & "</a></td>"
	Writeln "<td align='center'>" & start_end & "</td>"
	Writeln "<td align='center'>" & DateFormat2(ds("SM_limit_date").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat2(ds("SM_point_enquete").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat2(ds("SM_point_kansou").Value) & "</td>"

	count = ds("SM_real_send").Value
	If count > 0 Then
		Writeln "<td align='right'><a href='send_csv.asp?job_id=" & job_id & "' title='���M�ҏ���CSV�o�͂��܂�'>" & NumFormat(count) & "</a></td>"
	Else
		Writeln "<td align='right'>" & NumFormat(count) & "</td>"
	End If

	count = ds("SM_enq_count").Value
	If count > 0 Then
		Writeln "<td align='right'><a href='enquete.asp?job_id=" & job_id & "' title='�A���P�[�g���X�|���X��\�����܂�'>" & NumFormat(count) & "</a></td>"
	Else
		Writeln "<td align='right'>" & NumFormat(count) & "</td>"
	End If

	count = ds("SM_imp_count").Value
	If count > 0 Then
		Writeln "<td align='right'><a href='impression.asp?job_id=" & job_id & "' title='���z�����X�|���X��\�����܂�'>" & NumFormat(count) & "</a></td>"
	Else
		Writeln "<td align='right'>" & NumFormat(count) & "</td>"
	End If

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
