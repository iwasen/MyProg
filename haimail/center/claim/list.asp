<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�N���[���Ǘ��^�ꗗ�\��
'******************************************************

'=== ������ԕ\�� ===
Private Function StatusText(status)
	Select Case status
	Case "0"
		StatusText = "������"
	Case "1"
		StatusText = "���[�����M"
	Case "2"
		StatusText = "���Ȃ�"
	Case "3"
		StatusText = "�j��"
	End Select
End Function

'=== ���C������ ===
Dim ds, sql, line, cond, st, status(3), nline, i

SetHeaderTitle "�N���[�����[���ꗗ"
Response.CacheControl = "no-cache"

If Not IsEmpty(Request.QueryString("st")) Then
	For Each st In Request.QueryString("st")
		status(CInt(st)) = "1"
	Next
End If

nline = Request.QueryString("nline")
If IsEmpty(nline) Then
	nline = Session("claim_nline")
Else
	Session("claim_nline") = nline
End If
If Len(nline) > 0 Then
	nline = CInt(nline)
Else
	nline = 50
End If

cond = ""
For i = 0 To 3
	If status(i) = "1" Then
		cond = cond & "'" & i & "',"
	End If
Next
cond = "CA_status IN (" & cond & "' ')"
SQL = "SELECT JB_job_id,JB_job_name,RM_member_id,RM_subject,RM_recv_date,RM_date,RM_seq_no,CA_status" & vbCrLf & _
			"FROM T_JOB,T_RECV_MAIL,T_CLAIM_MAIL" & vbCrLf & _
			"WHERE " & cond & " AND RM_seq_no=CA_seq_no AND JB_mail_id=RM_mail_id" & vbCrLf & _
			"ORDER BY CA_seq_no DESC"
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

<center>
<table align="center" width="100%">
	<form method="get" name="form1">
	<tr>
		<td>
			<nobr><font class="hc"><input type="checkbox" name="st" value="0" <%=Checked(status(0))%> onclick="submit()">������</font></nobr>
			<nobr><font class="hc"><input type="checkbox" name="st" value="1" <%=Checked(status(1))%> onclick="submit()">Ұّ��M</font></nobr>
			<nobr><font class="hc"><input type="checkbox" name="st" value="2" <%=Checked(status(2))%> onclick="submit()">���Ȃ�</font></nobr>
			<nobr><font class="hc"><input type="checkbox" name="st" value="3" <%=Checked(status(3))%> onclick="submit()">�j��</font></nobr>
			<nobr><font class="hc">�\���s��</font><select name="nline" onchange="submit()"><%SelectDispLine(nline)%></select></nobr>
		</td>
		<td align="right">
			<input type="button" value=" �߂� " onclick="location.href='index.asp'">
		</td>
	</tr>
	</form>
</table>
<table <%=LIST_TABLE%> width="100%" align="center" class="small">
	<tr bgcolor="#E0FFE0">
		<th><nobr>�W���uID</nobr></th>
		<th><nobr>�W���u��</nobr></th>
		<th><nobr>�����o�[ID</nobr></th>
		<th><nobr>����</nobr></th>
		<th><nobr>���M����</nobr></th>
		<th><nobr>��M����</nobr></th>
		<th><nobr>���</nobr></th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td align='center'><a href='../project/cs/job_contents.asp?job_id=" & ds("JB_job_id").Value & "&back=" & Server.URLEncode("../claim/list.asp?" & Request.QueryString.Item) & "' title='�W���u�o�^���e��\�����܂�'>" & ds("JB_job_id").Value & "</a></td>"
	Writeln "<td>" & ReplaceTag(ds("JB_job_name").Value) & "</td>"
	Writeln "<td align='center'>" & BlankToBR(ds("RM_member_id").Value) & "</td>"
	Writeln "<td>" & ReplaceTag(ds("RM_subject").Value) & "</td>"
	Writeln "<td align='center'>" & TimeFormat(ds("RM_recv_date").Value) & "</td>"
	Writeln "<td align='center'>" & TimeFormat(ds("RM_date").Value) & "</td>"
	Writeln "<td align='center'><nobr><a href='claim_info.asp?seq=" & ds("RM_seq_no").Value & "' title='�N���[���Ή��������s���܂�'>" & StatusText(ds("CA_status").Value) & "</a></nobr></td>"
	Writeln "</tr>"
	ds.MoveNext
	line = line + 1
Loop
%>
</table>
<!--#INCLUDE FILE="../inc/footer.asp"-->
</center>
</body>
</html>
