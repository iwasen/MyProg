<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�A���P�[�g�G���[���[���ꗗ�\������
'******************************************************

Const ERR_NORMAL = "0"
Const ERR_NOANSWER = "1"
Const ERR_INVALID = "2"
Const ERR_INSUFF = "3"

'=== �G���[��ʕ\�� ===
Private Function ErrorKind(error_code)
	Select Case error_code
	Case ERR_NOANSWER
		ErrorKind = "����"
	Case ERR_INVALID
		ErrorKind = "�s����"
	Case ERR_INSUFF
		ErrorKind = "�񓚕s��"
	End Select
End Function

Dim sql, ds, line, from

SetHeaderTitle "�A���P�[�g�G���[���[���ꗗ"

sql = "SELECT JB_job_id,JB_job_name,MM_member_id,MM_mail_adr,RM_recv_date,EE_error_code,EE_seq_no" & vbCrLf & _
			"FROM T_JOB,T_MEMBER,T_RECV_MAIL,T_ENQUETE_ERR" & vbCrLf & _
			"WHERE EE_status='0' AND RM_seq_no=EE_seq_no AND JB_mail_id=RM_mail_id AND MM_member_id=RM_member_id" & vbCrLf & _
			"ORDER BY EE_seq_no"
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
	<tr>
		<td>�����F<%=ds.RecordCount%></td>
		<td align="right">
			<input type="button" value=" �߂� " onclick="location.href='index.asp'">
		</td>
	</tr>
</table>

<table <%=LIST_TABLE%> width="100%" class="small">
	<tr bgcolor="#E0FFE0">
		<th>�����o�[ID</th>
		<th>���[���A�h���X</th>
		<th>�W���uID</th>
		<th>�W���u��</th>
		<th>�ŐV��M��</th>
		<th>�G���[���</th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td align='center'>" & ds("MM_member_id").Value & "</td>"
	Writeln "<td><a href='enq_error2.asp?seq_no=" & ds("EE_seq_no").Value & "' title='�A���P�[�g�G���[���[���������s���܂�'>" & ReplaceTag(ds("MM_mail_adr").Value) & "</a></td>"
	Writeln "<td align='center'>" & ds("JB_job_id").Value & "</td>"
	Writeln "<td>" & ReplaceTag(ds("JB_job_name").Value) & "</td>"
	Writeln "<td align='center'>" & TimeFormat(ds("RM_recv_date").Value) & "</td>"
	Writeln "<td align='center'>" & ErrorKind(ds("EE_error_code").Value) & "</td>"
	Writeln "</tr>"
	ds.MoveNext
	line = line + 1
Loop
%>
</table>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
