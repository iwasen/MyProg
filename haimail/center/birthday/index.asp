<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�a�������[���z�M��ʏ���
'******************************************************

Dim sql, ds, line, seibetsu, age, valid_flag

SetHeaderTitle "�a�������[���ꗗ"

sql = "SELECT BM_seq_no,BM_priority,BM_seibetsu,BM_age_from,BM_age_to,BM_subject,BM_valid_flag" & vbCrLf & _
			"FROM T_BIRTHDAY_MAIL" & vbCrLf & _
			"ORDER BY BM_priority"
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
			<input type="button" value="�V�K�o�^" onclick="location.href='birthday1.asp'">
			<input type="button" value=" �߂� " onclick="location.href='../project/index.asp'">
		</td>
	</tr>
</table>

<table border=1 cellpadding=0 cellspacing=1 width="100%">
	<tr bgcolor="#E0FFE0">
		<th width="10%">�D�揇��</th>
		<th>����</th>
		<th>�N��͈�</th>
		<th>����</th>
		<th>�L��</th>
	</tr>
<%
line = 1
Do Until ds.EOF
	If IsNull(ds("BM_seibetsu").Value) Then
		seibetsu = "�w�薳��"
	Else
		seibetsu = SeibetsuText(ds("BM_seibetsu").Value)
	End If

	If IsNull(ds("BM_age_from").Value) And IsNull(ds("BM_age_to").Value) Then
		age = "�w�薳��"
	Else
		age = NVL(ds("BM_age_from").Value, "") & "�`" & NVL(ds("BM_age_to").Value, "")
	End If

	If ds("BM_valid_flag").Value = "1" Then
		valid_flag = "��"
	Else
		valid_flag = "�~"
	End If

	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td align='center'><a href='birthday1.asp?id=" & ds("BM_seq_no") & "' title='�a�������[������\���E�ύX���܂�'>" & ds("BM_priority").Value & "</a></td>"
	Writeln "<td align='center'>" & seibetsu & "</td>"
	Writeln "<td align='center'>" & age & "</td>"
	Writeln "<td>" & ds("BM_subject").Value & "</td>"
	Writeln "<td align='center'>" & valid_flag & "</td>"
	Writeln "</tr>"
	ds.MoveNext
	line = line + 1
Loop
%>
</table>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
