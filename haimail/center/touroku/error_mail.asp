<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�o�^�G���[���[���ꗗ�\������
'******************************************************

Dim sql, ds, line, from

SetHeaderTitle "�o�^�G���[���[���ꗗ"

sql = "SELECT TM_from,TM_date,TM_recv_date,TE_err_count,TE_seq_no" & vbCrLf & _
			"FROM T_TOUROKU_MAIL,T_TOUROKU_ERR" & vbCrLf & _
			"WHERE TM_seq_no=TE_seq_no AND TE_status='0'" & vbCrLf & _
			"ORDER BY TE_seq_no"
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

<table border=1 cellpadding=0 cellspacing=1 width="100%">
	<tr bgcolor="#E0FFE0">
		<th>���[���A�h���X</th>
		<th>���M���t</th>
		<th>��M���t</th>
		<th>��</th>
	</tr>
<%
line = 1
Do Until ds.EOF
	from = NVL(ds("TM_from").Value, "")
	If from = "" Then
		from = "���M�҃A�h���X����"
	End If
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td><a href='error_mail2.asp?id=" & ds("TE_seq_no") & "' title='�o�^�G���[���[�����������܂�'>" & ReplaceTag(from) & "</a></td>"
	Writeln "<td>" & TimeFormat(ds("TM_date").Value) & "</td>"
	Writeln "<td align='center'>" & TimeFormat(ds("TM_recv_date").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat(ds("TE_err_count").Value) & "</td>"
	Writeln "</tr>"
	ds.MoveNext
	line = line + 1
Loop
%>
</table>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
