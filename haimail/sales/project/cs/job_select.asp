<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:対象ジョブ選択画面処理
'******************************************************

'=== メイン処理 ===
Dim job_id, title, name, id, sub1, sql, ds, line, check, idarry, item, job_id2

job_id = Request.QueryString("job_id")
title = Request.QueryString("title")
name = Request.QueryString("name")
id = Request.QueryString("id")
idarry = Split(id, ",")

SetHeaderTitle title

sub1 = "SELECT PJ_tantousha_id FROM T_JOB,T_PROJECT WHERE JB_job_id=" & job_id & " AND PJ_project_id=JB_project_id"
sql = "SELECT JB_job_id,JB_job_name" & vbCrLf & _
			"FROM T_JOB,T_PROJECT" & vbCrLf & _
			"WHERE PJ_tantousha_id=(" & sub1 & ") AND JB_job_id<>" & job_id & " AND JB_project_id=PJ_project_id" & vbCrLf & _
			"ORDER BY JB_job_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_ok() {
	var f = document.form1;
	var id, n, i;
	id = "";
<%If ds.RecordCount = 1 Then%>
	if (f.job_id.checked)
		id = f.job_id.value;
<%ElseIf ds.RecordCount > 1 Then%>
	n = f.job_id.length;
	for (i = 0; i < n; i++) {
		if (f.job_id[i].checked) {
			if (id != "")
				id = id + ",";
			id = id + f.job_id[i].value;
		}
	}
<%End If%>
	opener.document.form1("<%=name%>").value = id;
	close();
}
//-->
</SCRIPT>
</head>
<body>
<form method="post" name="form1">
<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<tr>
		<td class="m0">■<%=title%></td>
		<td align="right">
			<input type="button" value="　OK　" onclick="OnClick_ok()">
			<input type="button" value="ｷｬﾝｾﾙ" onclick="window.close()">
		</td>
	</tr>
</table>

<table border=1 cellpadding=0 cellspacing=1 width="100%">
	<tr bgcolor="#E0FFE0">
		<th><nobr>選択</nobr></th>
		<th><nobr>ジョブID</nobr></th>
		<th><nobr>ジョブ名称</nobr></th>
	</tr>
<%
line = 1
Do Until ds.EOF
	job_id2 = ds("JB_job_id").Value
	check = ""
	For Each item In idarry
		If item = job_id2 Then
			check = " checked"
			Exit For
		End If
	Next

	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td width='10%' align='center'><input type='checkbox' name='job_id' value='" & job_id2 & "'" & check & "></td>"
	Writeln "<td width='10%' align='center'>" & job_id2 & "</td>"
	Writeln "<td width='80%'>" & ReplaceTag(ds("JB_job_name").Value) & "</td>"
	Writeln "</tr>"
	ds.MoveNext

	line = line + 1
Loop
%>
</table>
</form>

</body>
</html>
