<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ɨp�y�[�W
' Content:�ΏۃW���u�I����ʏ���
'******************************************************

'=== ���C������ ===
Dim sql, ds, line

SetHeaderTitle "�R�s�[���W���u�I��"

sql = "SELECT JB_job_id,JB_job_name" & vbCrLf & _
			"FROM T_JOB,T_PROJECT,T_TANTOUSHA,T_COMPANY,T_OPERATOR" & vbCrLf & _
			"WHERE OP_operator_id='" & g_login_id & "' AND CM_dairiten_id=OP_dairiten_id AND TS_company_id=CM_company_id AND PJ_tantousha_id=TS_tantousha_id AND JB_project_id=PJ_project_id" & vbCrLf & _
			"ORDER BY JB_job_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_job_id(job_id) {
	opener.CopyJob(job_id);
	close();
}
//-->
</SCRIPT>
</head>
<body>
<form method="post" id=form1 name=form1>
<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<tr>
		<td class="m0">���R�s�[���̃W���uID���N���b�N���Ă�������</td>
		<td align="right">
			<input type="button" name="close" value="��ݾ�" onclick="window.close()">
		</td>
	</tr>
</table>

<table border=1 cellpadding=0 cellspacing=1 width="100%">
	<tr bgcolor="#E0FFE0">
		<th>�W���uID</th>
		<th>�W���u����</th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td width='15%' align='center'><a href='javascript:OnClick_job_id(""" & ds("JB_job_id").Value & """)'>" & ds("JB_job_id").Value & "</a></td>"
	Writeln "<td width='85%'>" & ReplaceTag(ds("JB_job_name").Value) & "</td>"
	Writeln "</tr>"
	ds.MoveNext

	line = line + 1
Loop
%>
</table>
</form>

</body>
</html>
