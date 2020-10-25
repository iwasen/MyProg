<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�S�z�M�A���P�[�g���X�g��ʏ���
'******************************************************

'=== ���̌��� ===
Private Sub AndJoin(exp1, exp2)
	If exp2 <> "" Then
		If exp1 <> "" Then
			exp1 = exp1 & " AND "
		End If
		exp1 = exp1 & exp2
	End If
End Sub

'=== ���C������ ===
Dim job_id, job_name, sql, ds, seq, seq_from, seq_to, line, subject

job_id = Request.Form("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

SetHeaderTitle "�S�z�M �A���P�[�g���X�g�\��"

'JOB���擾
sql = "SELECT ZH_job_name FROM T_ZENHAISHIN WHERE ZH_job_id=" & job_id
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If Not ds.EOF Then
	job_name = ds(0).Value
End If

If Request.Form("seq_from") <> "" Then
	seq_from = CLng(Request.Form("seq_from"))
End If

If Request.Form("seq_to") <> "" Then
	seq_to = CLng(Request.Form("seq_to"))
End If

sql = "ZH_job_id=" & job_id & " AND RM_enq_valid='1'"
If Request.Form("start_date_y") <> "" Then
	AndJoin sql, "RM_recv_date>='" & Request.Form("start_date_y") & "/" & Request.Form("start_date_m") & "/" & Request.Form("start_date_d") & "'"
End If
If Request.Form("end_date_y") <> "" Then
	AndJoin sql, "RM_recv_date<='" & Request.Form("end_date_y") & "/" & Request.Form("end_date_m") & "/" & Request.Form("end_date_d") & "'"
End If
If Request.Form("midoku") = "1" Then
	AndJoin sql, "RM_seq_no NOT IN (SELECT MR_recv_mail FROM T_MAIL_READ WHERE MR_login_type='" & g_login_type & "' AND MR_login_id='" & g_login_id & "' AND MR_enq_imp='E')"
End If
AndJoin sql, "ZH_mail_id=RM_mail_id AND RM_member_id=MM_member_id AND MM_zip_kyojuu=ZC_zip_uniq(+) AND MM_shokugyou=SG_shokugyou_code(+)"
sql = "SELECT MM_member_id,MM_seinengappi,MM_seibetsu,MM_mikikon,ZC_todoufuken,SG_shokugyou_name,RM_seq_no,RM_subject,RM_date,RM_recv_date,RM_body" & vbCrLf & _
			"FROM T_ZENHAISHIN,T_RECV_MAIL,T_MEMBER,T_ZIPCODE,T_SHOKUGYOU" & vbCrLf & _
			"WHERE " & sql & vbCrLf & _
			"ORDER BY RM_seq_no"
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
	<form method="post" id=form1 name=form1>
	<tr>
		<td>�����F<%=ds.RecordCount%></td>
		<td align="right">
			<input type="button" value=" �߂� " onclick="location.href='enquete.asp?job_id=<%=job_id%>'">
		</td>
	</tr>
	</form>
</table>

<table <%=LIST_TABLE%> width="100%" class="small">
	<tr bgcolor="#E0FFE0">
		<th>SEQ</th>
		<th>�T�u�W�F�N�g</th>
		<th>�N��</th>
		<th>����</th>
		<th>���Z�n</th>
		<th>�E��</th>
		<th>������</th>
		<th>���M��</th>
		<th>��M��</th>
	</tr>
<%
If Not IsEmpty(seq_from) Then
	ds.MoveTo seq_from
End If

line = 1
seq = 1
Do Until ds.EOF
	If Not IsEmpty(seq_to) Then
		If seq > seq_to Then
			Exit Do
		End If
	End If

	subject = ds("RM_subject").Value
	If IsNull(subject) Then
		subject = "����"
	End If

	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td align='right'>" & seq & "</td>"
	Writeln "<td><a href='enq_list2.asp?seq=" & seq & "&id=" & ds("RM_seq_no").Value & "' title='�A���P�[�g���X�|���X��\�����܂�'>" & ReplaceTag(subject) & "</a></td>"
	Writeln "<td align='right'>" & GetAge(ds("MM_seinengappi").Value) & "</td>"
	Writeln "<td align='center'>" & SeibetsuText(ds("MM_seibetsu").Value) & "</td>"
	Writeln "<td>" & ds("ZC_todoufuken").Value & "</td>"
	Writeln "<td>" & ds("SG_shokugyou_name").Value & "</td>"
	Writeln "<td align='center'>" & MikikonText(ds("MM_mikikon").Value) & "</td>"
	Writeln "<td align='center'>" & DateFormat(ds("RM_date").Value) & "</td>"
	Writeln "<td align='center'>" & DateFormat(ds("RM_recv_date").Value) & "</td>"
	Writeln "</tr>"
	ds.MoveNext

	seq = seq + 1
	line = line + 1
Loop
%>
</table>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
