<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ɨp�y�[�W
' Content:�v���W�F�N�g�Ǘ��@��ƒS���ґI����ʏ���
'******************************************************

'=== ���C������ ===
Dim sql, ds, line, same_company, row_count, next_url

If CheckPermission(PM_REGIST_PROJECT) Then
	next_url = "pjt_name.asp"
Else
	next_url = "job_name.asp"
End If

If g_login_type = LOGIN_CLIENT Then
	Response.Redirect next_url & "?tantousha_id=" & g_login_id
End If

SetHeaderTitle "��ƒS���҃��X�g"

sql = "SELECT *" & vbCrLf & _
			"FROM T_COMPANY,T_TANTOUSHA,T_OPERATOR" & vbCrLf & _
			"WHERE OP_operator_id='" & g_login_id & "' AND CM_dairiten_id=OP_dairiten_id AND CM_company_id=TS_company_id" & vbCrLf & _
			"ORDER BY CM_company_id,TS_tantousha_id"
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
	<form method="post" name=form1>
	<tr>

			<td class="m0">
				<p>���v���W�F�N�g�o�^���s���܂��B</p>
				<p class="np">�o�^�\��v���W�F�N�g�̔��M��ƒS���Җ���I�����ĉ������B</p>
			</td>

			<td align="right" valign="bottom">
				<input type="button" value="�@�߂�@" onclick="location.href='pjt_go.asp'">
		</td>
	</tr>
	</form>
</table>

<table border=1 cellpadding=0 cellspacing=1 width="100%" frame="box">
	<tr bgcolor="#E0FFE0">
		<th>���ID</th>
		<th>��Ɩ�</th>
		<th>�S����ID</th>
		<th>�S���Җ�</th>
	</tr>
<%
Set same_company = New CJoinSameItem
same_company.Prepare ds, "CM_company_id"

line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"

	row_count = same_company.GetRowCount
	If row_count > 0 Then
		Writeln "<td align='center' rowspan=" & row_count & " class='tc2'>" & ds("CM_company_id").Value & "</td>"
		Writeln "<td rowspan=" & row_count & " class='tc2'>" & ds("CM_name").Value & "</td>"
	End If

	Writeln "<td align='center'>" & ds("TS_tantousha_id").Value & "</td>"
	Writeln "<td><a href='" & next_url & "?tantousha_id=" & ds("TS_tantousha_id") & "'>" & ds("TS_name1").Value & " " & ds("TS_name2").Value & "</a></td>"
	Writeln "</tr>"
	ds.MoveNext

	line = line + 1
Loop
%>
</table>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
