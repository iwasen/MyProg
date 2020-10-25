<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:ジョブ承認画面処理
'******************************************************

'=== 承認状態表示 ===
Private Function ApproveStatus(status)
	Select Case status
	Case "0"
		ApproveStatus = "未要求"
	Case "1"
		ApproveStatus = "<input type='button' value='承認' onclick='OnClick_shounin(" & ds("JB_job_id").Value & ")'><input type='button' value='解除' onclick='OnClick_kaijo(" & ds("JB_job_id").Value & ")'> "
	Case "2"
		ApproveStatus = "承認済"
	End Select
End Function

'=== メイン処理 ===
Dim sql, ds, line, same_project, row_count, project_id

SetHeaderTitle "ジョブ承認処理"

sql = "SELECT PJ_project_id,PJ_project_name,JB_job_id,JB_job_name,JB_mail_type,JB_status,SM_start_date,SM_end_date,SM_limit_date,SM_send_num" & vbCrLf & _
			"FROM T_PROJECT,T_JOB,T_SEND_MAIL" & vbCrLf & _
			"WHERE PJ_project_id IN (SELECT DISTINCT JB_project_id FROM T_JOB WHERE JB_status='1')" & vbCrLf & _
			" AND PJ_project_id=JB_project_id AND JB_mail_id=SM_mail_id(+)" & vbCrLf & _
			"ORDER BY PJ_project_id,JB_job_id"
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
function OnClick_shounin(job_id) {
	location.href = "shounin2.asp?job_id=" + job_id;
}
function OnClick_kaijo(job_id) {
	location.href = "shounin4.asp?job_id=" + job_id;
}
function OnClick_project(project_id) {
	var win;
	win = window.open("info_project.asp?id=" + project_id, "info_project", "resizable=yes,width=550,height=400");
	win.focus();
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<form method="post" name="form1">
	<tr>
		<td class="m0">■未承認のジョブ一覧</td>
		<td align="right"><input type="button" value=" 戻る " onclick="location.href='index.asp'"></td>
	</tr>
	</form>
</table>

<table <%=LIST_TABLE%> width="100%" class="small">
	<tr bgcolor="#E0FFE0">
		<th>ﾌﾟﾛｼﾞｪｸﾄID</th>
		<th>ﾌﾟﾛｼﾞｪｸﾄ名</th>
		<th>ｼﾞｮﾌﾞID</th>
		<th>ｼﾞｮﾌﾞ名</th>
		<th>種類</th>
		<th>送信期間</th>
		<th>締切り</th>
		<th>最大発信数</th>
		<th>承認</th>
	</tr>
<%
Set same_project = New CJoinSameItem
same_project.Prepare ds, "PJ_project_id"

line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"

	row_count = same_project.GetRowCount
	If row_count > 0 Then
		project_id = ds("PJ_project_id").Value
		Writeln "<td rowspan=" & row_count & " class='tc2' align='center'><a href='JavaScript:OnClick_project(""" & project_id & """)' title='プロジェクト情報を表示します'>" & project_id & "</a></td>"
		Writeln "<td rowspan=" & row_count & " class='tc2'><a href='cs/job_list.asp?id=" & project_id & "' title='プロジェクト内ジョブ一覧を表示します'>" & ReplaceTag(ds("PJ_project_name").Value) & "</a></td>"
	End If

	If IsNull(ds("JB_job_id").Value) Then
		Writeln "<td><br></td>"
		Writeln "<td><br></td>"
		Writeln "<td><br></td>"
		Writeln "<td><br></td>"
		Writeln "<td><br></td>"
		Writeln "<td><br></td>"
		Writeln "<td><br></td>"
	Else
		Writeln "<td align='center'><a href='cs/job_contents.asp?job_id=" & ds("JB_job_id").Value & "&back=shounin.asp' title='ジョブ内容を表示・変更します'>" & ds("JB_job_id").Value & "</a></td>"
		Writeln "<td>" & ReplaceTag(ds("JB_job_name").Value) & "</td>"
		Writeln "<td align='center'>" & ds("JB_mail_type").Value & "</td>"
		Writeln "<td align='center'>" & DateFormat3(ds("SM_start_date").Value, ds("SM_end_date").Value) & "</td>"
		Writeln "<td align='center'>" & DateFormat2(ds("SM_limit_date").Value) & "</td>"
		Writeln "<td align='right'>" & NumFormat2(ds("SM_send_num").Value) & "</td>"
		Writeln "<td align='center'>" & ApproveStatus(ds("JB_status").Value) & "</td>"
	End If
	Writeln "</tr>"

	line = line + 1
	ds.MoveNext
Loop
%>
</table>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
