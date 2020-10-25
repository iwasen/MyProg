<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����ǁE�c�Ƌ��ʃy�[�W
' Content:�W���u�ꗗ��ʏ���
'******************************************************

'=== ���C������ ===
Dim sql, ds, line, project_id, job_id, rec_count

project_id = Request.QueryString("id")
If IsEmpty(project_id) Then
	SystemError "project_id is empty"
End If

SetHeaderTitle "�v���W�F�N�g���W���u�ꗗ"

sql = "SELECT JB_job_id,JB_job_name,JB_search_id" & vbCrLf & _
			"FROM T_JOB" & vbCrLf & _
			"WHERE JB_project_id=" & project_id & vbCrLf & _
			"ORDER BY JB_job_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
rec_count = ds.RecordCount
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1(f) {
	var i, count;
	count = 0;
	for (i = 0; i < <%=rec_count%>; i++) {
		if (f.job_id[i].checked)
			count++;
	}
	if (count < 2) {
		alert("�����\�����邽�߂ɂ̓W���u���Q�ȏ�I�����Ă��������B");
		return false;
	}
	return true;
}
function OnClick_allsel() {
	for (i = 0; i < <%=rec_count%>; i++) {
		if (!document.form1.job_id[i].disabled)
			document.form1.job_id[i].checked = true;
	}
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../../inc/header.asp"-->

<center>
<form method="post" action="job_diff.asp" name="form1" style="margin:0" onsubmit="return OnSubmit_form1(this)">
<table border=0 cellpadding=0 cellspacing=1 width="80%">
	<tr>
		<td>�����F<%=rec_count%></td>
		<td align="right">
<%If rec_count >= 2 Then%>
			<input type="button" value="�S�I��" onclick="OnClick_allsel()">
			<input type="submit" value="�����\��">
<%End If%>
			<input type="button" value=" �߂� " onclick="history.back()">
		</td>
	</tr>
</table>

<table border=1 cellpadding=0 cellspacing=1 width="80%">
	<tr bgcolor="#E0FFE0">
		<th>�W���uID</th>
		<th>�W���u��</th>
<%If rec_count >= 2 Then%>
		<th>�I��</th>
<%End If%>
	</tr>
<%
line = 1
Do Until ds.EOF
	job_id = ds("JB_job_id").Value
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	If IsNull(ds("JB_search_id").Value) Then
		Writeln "<td align='center'>" & job_id & "</a></td>"
	Else
		Writeln "<td align='center'><a href='job_show.asp?job_id=" & job_id & "'>" & job_id & "</a></td>"
	End If
	Writeln "<td>" & ReplaceTag(ds("JB_job_name").Value) & "</td>"
	If rec_count >= 2 Then
		If IsNull(ds("JB_search_id").Value) Then
			Writeln "<td align='center'><input type='checkbox' name='job_id' value='" & job_id & "' disabled></td>"
		Else
			Writeln "<td align='center'><input type='checkbox' name='job_id' value='" & job_id & "'></td>"
		End If
	End If
	Writeln "</tr>"
	ds.MoveNext

	line = line + 1
Loop
%>
</table>
</form>
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
