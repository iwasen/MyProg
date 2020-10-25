<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ɨp�y�[�W
' Content:�v���W�F�N�g�i�s�Ǘ����
'******************************************************

'=== ���C������ ===
Dim sub1, sub2, sub3, sql, ds, s_year, s_month, s_ym
Dim	PJ_count, SR_count, RM_enq_count, CC_count, RM_imp_count

s_year = Request.Form("s_year")
s_month = Request.Form("s_month")

s_ym = s_year & Right("0" & s_month, 2)

SetHeaderTitle "�㗝�X�`�ʌ��ʃg�[�^��"
Response.ContentType = "application/vnd.ms-excel"

sub1 = "SELECT SR_mail_id,COUNT(*) AS SR_count FROM T_SENDMAIL_RR WHERE SR_status='1' GROUP BY SR_mail_id"
sub2 = "SELECT RM_mail_id,COUNT(RM_enq_valid) AS RM_enq_count,COUNT(RM_imp_valid) AS RM_imp_count FROM T_RECV_MAIL GROUP BY RM_mail_id"
sub3 = "SELECT CC_job_id,COUNT(*) AS CC_count FROM T_MAIL_CLICK,T_CLICK_COUNT WHERE MC_ct_id=CC_ct_id GROUP BY CC_job_id"

sql = "SELECT DA_dairiten_id, DA_name, COUNT(PJ_project_id) AS PJ_count, SUM(SR_count) AS SR_count, SUM(RM_enq_count) AS RM_enq_count, SUM(CC_count) AS CC_count, SUM(RM_imp_count) RM_imp_count" & vbCrLf & _
			"FROM T_PROJECT, T_JOB, T_SEND_MAIL, T_TANTOUSHA, T_COMPANY, T_DAIRITEN, (" & sub1 & "),(" & sub2 & "),(" & sub3 & ")" & vbCrLf & _
			"WHERE PJ_project_id=JB_project_id AND SR_mail_id=JB_mail_id AND JB_project_id=PJ_project_id AND PJ_tantousha_id=TS_tantousha_id AND TS_company_id=CM_company_id" & vbCrLf & _
			"AND JB_mail_id=SM_mail_id(+) AND JB_mail_id=RM_mail_id(+) AND JB_job_id=CC_job_id(+)" & vbCrLf & _
			"AND (CM_dairiten_id=DA_dairiten_id OR CM_dairiten_id=DA_rep_id) AND TO_CHAR(SM_start_date,'YYYYMM') = '" & s_ym & "'" & vbCrLf & _
			"GROUP BY DA_dairiten_id, DA_name" & vbCrLf & _
			"ORDER BY DA_dairiten_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)

%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../../haimail.css">
</head>
<body>
<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<form method="post" name="form1">
	<tr>
		<td>�R�D�㗝�X�`�ʌ��ʃg�[�^��</td>
	</tr>
</table>
<br>
<table>
	<tr>
		<td>��<%=s_year%>�N<%=s_month%>��</td>
	</tr>
	</form>
</table>

<table border=1 cellpadding=0 cellspacing=1 width="100%" class="small">
  <tr bgcolor="#E0FFE0">
		<th>SEQ</th>
		<th>�㗝�X��</th>
		<th>��ۼު�Đ�</th>
		<th>���M��</th>
		<th>�ݹ��</th>
		<th>�د���</th>
    <th>���z��</th>
    <th>����</th>
		<th>����\����</th>
	</tr>
<%
Do Until ds.EOF
  Writeln "<td align='center'>" & ds("DA_dairiten_id").Value & "</td>"
  Writeln "<td>" & ReplaceTag(ds("DA_name").Value) & "</td>"
  Writeln "<td align='right'>" & ds("PJ_count").Value & "</td>"
  Writeln "<td align='right'>" & NumFormat(ds("SR_count").Value) & "</td>"
  Writeln "<td align='right'>" & NumFormat(ds("RM_enq_count").Value) & "</td>"
  Writeln "<td align='right'>" & NumFormat(ds("CC_count").Value) & "</td>"
  Writeln "<td align='right'>" & NumFormat(ds("RM_imp_count").Value) & "</td>"
  Writeln "<td align='right'>0</td>"
  Writeln "<td align='right'>0</td>"
	Writeln "</tr>"
	PJ_count = Number(ds("PJ_count").Value) + PJ_count
	SR_count = Number(ds("SR_count").Value) + SR_count
	RM_enq_count = Number(ds("RM_enq_count").Value) + RM_enq_count
	CC_count = Number(ds("CC_count").Value) + CC_count
	RM_imp_count = Number(ds("RM_imp_count").Value) + RM_imp_count
	ds.MoveNext
Loop
Writeln "<tr>"
	Writeln"<td align='center'>�v</td>"
	Writeln"<td><br></td>"
	Writeln"<td align='right'>" & NumFormat(PJ_count) & "</td>"
	Writeln"<td align='right'>" & NumFormat(SR_count) & "</td>"
	Writeln"<td align='right'>" & NumFormat(RM_enq_count) & "</td>"
	Writeln"<td align='right'>" & NumFormat(CC_count) & "</td>"
	Writeln"<td align='right'>" & NumFormat(RM_imp_count) & "</td>"
	Writeln"<td align='right'>0</td>"
	Writeln"<td align='right'>0</td>"
Writeln"</tr>"
%>
</table>
</body>
</html>
