<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/mail_check.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�S�z�M�A���P�[�g�e�L�X�g��ʏ���
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

'=== ���ʂ̃e�L�X�g�\�� ===
Private Function SeibetsuText(seibetsu)
	Select Case seibetsu
	Case "0"
		SeibetsuText = "�j"
	Case "1"
		SeibetsuText = "��"
	Case Else
		SeibetsuText = ""
	End Select
End Function

'=== �������̃e�L�X�g�\�� ===
Private Function MikikonText(mikikon)
	Select Case mikikon
	Case "0"
		MikikonText = "�Ɛg"
	Case "1"
		MikikonText = "����"
	Case Else
		MikikonText = ""
	End Select
End Function

'=== ���C������ ===
Dim job_id, job_name, sql, ds, body, ary, s, flag, hr, seq, seq_from, seq_to, exist

job_id = Request.Form("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

SetHeaderTitle "�S�z�M �A���P�[�g�e�L�X�g�\��"

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
sql = "SELECT MM_member_id,MM_seinengappi,MM_seibetsu,MM_mikikon,ZC_todoufuken,SG_shokugyou_name,RM_subject,RM_recv_date,RM_body" & vbCrLf & _
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

<table cellspacing=0 cellpadding=0>
	<tr>
		<td>JOBID</td>
		<td><%=job_id%></td>
	</tr>
	<tr>
		<td>JOB��</td>
		<td><%=job_name%></td>
	</tr>
</table>

<center>
<table class="log" cellpadding=3 width="100%">
	<tr>
		<td>
			<font class="np">
<%
If Not IsEmpty(seq_from) Then
	ds.MoveTo seq_from
End If

exist = False
seq = 1
hr = False
Do Until ds.EOF
	If Not IsEmpty(seq_to) Then
		If seq > seq_to Then
			Exit Do
		End If
	End If

	If hr Then
		Writeln "<hr class='separator'>"
	Else
		hr = True
	End If

	Writeln "<font color='blue'>"
	Writeln "SEQ " & seq & "�@���ID " & ds("MM_member_id").Value & "�@�ŐV��M�� " & DateFormat(ds("RM_recv_date").Value) & "<br>"
	Writeln "�N�� " & GetAge(ds("MM_seinengappi").Value) & "�@���� " & SeibetsuText(ds("MM_seibetsu").Value) & "�@�Z�� " & ds("ZC_todoufuken").Value & "�@�E�� " & ds("SG_shokugyou_name").Value & "�@������ " & MikikonText(ds("MM_mikikon").Value) & "<br>"
	Writeln "******** " & ReplaceTag(ds("RM_subject").Value) & " ********<br>"
	Writeln "</font>"

	body = ReadCLOB(ds("RM_body").Value)
	ary = Split(body, vbCrLf)
	flag = False
	For Each s In ary
		If SeparatorCheck(s, ENQ_START) Then
			flag = True
		End If

		If flag Then
			Writeln ReplaceTag(DeleteQuote1(s)) & "<br>"
		End If

		If SeparatorCheck(s, ENQ_END) Then
			flag = False
		End If
	Next

	exist = True
	seq = seq + 1

	ds.MoveNext
Loop

If exist = False Then
	Writeln "�Y������f�[�^�͂���܂���B"
End If
%>
			</font>
		</td>
	</tr>
</table>
<br>
<input type="button" value=" �߂� " onclick="history.back()">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
