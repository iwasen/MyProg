<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:プロジェクト管理画面
'******************************************************

'=== 代理店情報取得 ===
Private Sub GetDairiten()
	Dim sql, ds

	sql = "SELECT DA_dairiten_id,DA_class" & vbCrLf & _
				"FROM T_OPERATOR,T_DAIRITEN" & vbCrLf & _
				"WHERE OP_operator_id='" & g_login_id & "' AND DA_dairiten_id=OP_dairiten_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		dairiten_id = ds(0).Value
		d_class = ds(1).Value
	End If
End Sub

'=== 代理店選択 ===
Private Sub SelectDairiten()
	Dim sql, ds

	sql = "SELECT DA_dairiten_id,DA_name" & vbCrLf & _
				"FROM T_DAIRITEN" & vbCrLf & _
				"WHERE DA_dairiten_id='" & dairiten_id & "' OR DA_rep_id='" & dairiten_id & "'" & vbCrLf & _
				"ORDER BY DA_dairiten_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Writeln "<option value=''" & Selected("", pjt_dairiten_id) & ">- 全て -</option>"
	Do Until ds.EOF
		Writeln "<option value='" & ds(0).Value & "'" & Selected(ds(0).Value, pjt_dairiten_id) & ">" & ds(1).Value & "</option>"
		ds.MoveNext
	Loop
End Sub

'=== 企業選択 ===
Private Sub SelectCompany()
	Dim sql, ds

	sql = "SELECT CM_company_id,CM_name FROM T_COMPANY"
	If pjt_dairiten_id <> "" Then
		sql = sql & " WHERE CM_dairiten_id='" & pjt_dairiten_id & "'"
	Else
		sql = sql & " WHERE CM_dairiten_id IN (SELECT DA_dairiten_id FROM T_DAIRITEN WHERE DA_dairiten_id='" & dairiten_id & "' OR DA_rep_id='" & dairiten_id & "')"
	End If
	sql = sql & " ORDER BY CM_company_id"

	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Writeln "<option value=''" & Selected("", pjt_company_id) & ">- 全て -</option>"
	Do Until ds.EOF
		Writeln "<option value='" & ds(0).Value & "'" & Selected(ds(0).Value, pjt_company_id) & ">" & ds(1).Value & "</option>"
		ds.MoveNext
	Loop
End Sub

'=== 企業担当者選択 ===
Private Sub SelectTantousha()
	Dim sql, ds

	Select Case g_login_type
	Case LOGIN_AGENT
		sql = "SELECT TS_tantousha_id,TS_name1,TS_name2 FROM T_TANTOUSHA"
		If pjt_company_id <> "" Then
			sql = sql & " WHERE TS_company_id='" & pjt_company_id & "'"
		Else
			sql = sql & " WHERE TS_company_id IN (SELECT CM_company_id FROM T_COMPANY WHERE CM_dairiten_id IN (SELECT DA_dairiten_id FROM T_DAIRITEN WHERE DA_dairiten_id='" & dairiten_id & "' OR DA_rep_id='" & dairiten_id & "'))"
		End If
	Case LOGIN_CLIENT
		sql = "SELECT TS_tantousha_id,TS_name1,TS_name2 FROM T_TANTOUSHA" & vbCrLf & _
					"WHERE TS_company_id=(SELECT TS_company_id FROM T_TANTOUSHA WHERE TS_tantousha_id='" & g_login_id & "')"
	Case Else
		Exit Sub
	End Select

	sql = sql & " ORDER BY TS_tantousha_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Writeln "<option value=''" & Selected("", pjt_company_id) & ">- 全て -</option>"
	Do Until ds.EOF
		Writeln "<option value='" & ds(0).Value & "'" & Selected(ds(0).Value, pjt_tantousha_id) & ">" & ds(1).Value & " " & ds(2).Value & "</option>"
		ds.MoveNext
	Loop
End Sub

'=== 率（％）表示 ===
Private Function Rate(n1, n2)
	If n2 = 0 Then
		Rate = "-"
	Else
		Rate = FormatPercent(n1 / n2, 2, True)
	End If
End Function

'=== メイン処理 ===
Dim sub1, sub2, sql, ds, line, dairiten_id, d_class, row_count, pjt_dairiten_id, pjt_company_id, pjt_tantousha_id, where, name
Dim same_dairiten, same_company, same_tantousha, same_project
Dim regist_project, regist_job, click_link, enq_link, imp_number, imp_link, send_count, count

pjt_dairiten_id = Request.QueryString("dairiten_id")
If IsEmpty(pjt_dairiten_id) Then
	pjt_dairiten_id = Session("pjt_dairiten_id")
Else
	Session("pjt_dairiten_id") = pjt_dairiten_id
End If

pjt_company_id = Request.QueryString("company_id")
If IsEmpty(pjt_company_id) Then
	pjt_company_id = Session("pjt_company_id")
Else
	Session("pjt_company_id") = pjt_company_id
End If

pjt_tantousha_id = Request.QueryString("tantousha_id")
If IsEmpty(pjt_tantousha_id) Then
	pjt_tantousha_id = Session("pjt_tantousha_id")
Else
	Session("pjt_tantousha_id") = pjt_tantousha_id
End If

GetDairiten

regist_project = CheckPermission(PM_REGIST_PROJECT)
regist_job = CheckPermission(PM_REGIST_JOB_A) Or CheckPermission(PM_REGIST_JOB_B)
click_link = CheckPermission(PM_CLICK_REPORT) Or CheckPermission(PM_CLICK_URL)
enq_link = CheckPermission(PM_ENQ_LOG) Or CheckPermission(PM_ENQ_CSV) Or CheckPermission(PM_ENQ_REPORT)
imp_link = CheckPermission(PM_IMP_10LOG) Or CheckPermission(PM_IMP_LOG) Or CheckPermission(PM_IMP_CSV) Or CheckPermission(PM_IMP_REPORT)
imp_number = CheckPermission(PM_IMP_NUMBER) Or imp_link

SetHeaderTitle "プロジェクト管理"
'Response.CacheControl = "no-cache"

where = ""
If pjt_dairiten_id <> "" Then
	where = where & "CM_dairiten_id='" & pjt_dairiten_id & "' AND "
End If
If pjt_company_id <> "" Then
	where = where & "CM_company_id='" & pjt_company_id & "' AND "
End If
If pjt_tantousha_id <> "" Then
	where = where & "TS_tantousha_id='" & pjt_tantousha_id & "' AND "
End If

sub1 = "SELECT CC_job_id,SUM(CC_click_num) AS CC_count FROM T_CLICK_COUNT GROUP BY CC_job_id"

Select Case g_login_type
Case LOGIN_AGENT
	sql = "SELECT DA_dairiten_id,DA_name,CM_company_id,CM_name,TS_tantousha_id,TS_name1,TS_name2,PJ_project_id,PJ_project_name,JB_job_id,JB_job_name,SM_start_date,SM_send_num,SM_real_send,SM_enq_count,SM_imp_count,CC_count" & vbCrLf & _
				"FROM (" & sub1 & "),T_SEND_MAIL,T_JOB,T_PROJECT,T_TANTOUSHA,T_COMPANY,T_DAIRITEN,T_OPERATOR" & vbCrLf & _
				"WHERE " & where & "OP_operator_id='" & g_login_id & "' AND (DA_dairiten_id=OP_dairiten_id OR DA_rep_id=OP_dairiten_id) AND CM_dairiten_id=DA_dairiten_id AND TS_company_id=CM_company_id AND PJ_tantousha_id=TS_tantousha_id AND PJ_project_id=JB_project_id(+) AND JB_mail_id=SM_mail_id(+) AND JB_job_id=CC_job_id(+)" & vbCrLf & _
				"ORDER BY DA_dairiten_id,CM_company_id,TS_tantousha_id,PJ_project_id,JB_job_id"
Case LOGIN_CLIENT
	sub2 = "SELECT TS_company_id MY_company_id FROM T_TANTOUSHA WHERE TS_tantousha_id='" & g_login_id & "'"
	sql = "SELECT TS_tantousha_id,TS_name1,TS_name2,PJ_project_id,PJ_project_name,JB_job_id,JB_job_name,SM_start_date,SM_send_num,SM_real_send,SM_enq_count,SM_imp_count,CC_count" & vbCrLf & _
				"FROM (" &sub2 & "),(" & sub1 & "),T_SEND_MAIL,T_JOB,T_PROJECT,T_TANTOUSHA" & vbCrLf & _
				"WHERE " & where & "TS_company_id=MY_company_id AND PJ_tantousha_id=TS_tantousha_id AND PJ_project_id=JB_project_id(+) AND JB_mail_id=SM_mail_id(+) AND JB_job_id=CC_job_id(+)" & vbCrLf & _
				"ORDER BY PJ_project_id,JB_job_id"
Case Else
	NoPermission
End Select
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
function OnChange_dairiten_id() {
	document.form1.company_id.value = '';
	document.form1.tantousha_id.value = '';
	document.form1.submit();
}
function OnChange_company_id() {
	document.form1.tantousha_id.value = '';
	document.form1.submit();
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="m0">現在のプロジェクトの登録・閲覧、過去のプロジェクトの閲覧まで一括管理が可能です。</td>
	</tr>
</table>
<br>
<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<form method="get" name="form1">
	<tr>
		<td>件数：<%=ds.RecordCount%></td>
		<td>
<%If g_login_type = LOGIN_AGENT Then%>
<%	If d_class = "A" Then%>
			<nobr><font class="hc">代理店</font><select name="dairiten_id" onchange="OnChange_dairiten_id()"><%SelectDairiten%></select></nobr>
<%	End If%>
			<nobr><font class="hc">企業</font><select name="company_id" onchange="OnChange_company_id()"><%SelectCompany%></select></nobr>
<%End If%>
			<nobr><font class="hc">担当者</font><select name="tantousha_id" onchange="submit()"><%SelectTantousha%></select></nobr>
		</td>
<%If regist_project Then%>
		<td align="right"><input type="button" value="新規ﾌﾟﾛｼﾞｪｸﾄ登録" onclick="location.href='sel_tantousha.asp'"></td>
<%ElseIf regist_job Then%>
		<td align="right"><input type="button" value="新規ジョブ登録" onclick="location.href='sel_tantousha.asp'"></td>
<%End If%>
	</tr>
	</form>
</table>

<br>
<table width="100%" border="0" cellspacing="2" cellpadding="0">
	<tr>
		<td class="info"><font color="slateblue">■</font>クリック率の詳細閲覧は、「クリック数」をクリックして下さい。</td>
	</tr>
	<tr>
		<td class="info"><font color="slateblue">■</font>感想文の詳細閲覧は、「感想文数」をクリックして下さい。</td>
	</tr>
</table>
<br>
<table <%=LIST_TABLE%> width="100%" class="small">
	<tr bgcolor="#E0FFE0">
<%If g_login_type = LOGIN_AGENT Then%>
<%	If d_class = "A" Then%>
		<th rowspan=2>代理店ID</th>
		<th rowspan=2>代理店名</th>
<%	End If%>
		<th rowspan=2>企業名</th>
<%End If%>
		<th rowspan=2>担当者</th>
<%If regist_project Then%>
		<th rowspan=2>ﾌﾟﾛｼﾞｪｸﾄID</th>
		<th rowspan=2>ﾌﾟﾛｼﾞｪｸﾄ名</th>
<%End If%>
		<th rowspan=2>ｼﾞｮﾌﾞID</th>
		<th rowspan=2>ｼﾞｮﾌﾞ名</th>
		<th rowspan=2>発信日</th>
		<th rowspan=2>発信数</th>
		<th colspan=2>クリック</th>
		<th colspan=2>アンケート</th>
		<th colspan=2>感想文</th>
	</tr>
	<tr bgcolor="#E0FFE0">
		<th>数</th>
		<th>率</th>
		<th>数</th>
		<th>率</th>
		<th>数</th>
		<th>率</th>
	</tr>
<%
If g_login_type = LOGIN_AGENT Then
	If d_class = "A" Then
		Set same_dairiten = New CJoinSameItem
		same_dairiten.Prepare ds, "DA_dairiten_id"
	End If

	Set same_company = New CJoinSameItem
	same_company.Prepare ds, "CM_company_id"
End If

Set same_tantousha = New CJoinSameItem
same_tantousha.Prepare ds, "TS_tantousha_id"

If regist_project Then
	Set same_project = New CJoinSameItem
	same_project.Prepare ds, "PJ_project_id"
End If

line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"

	If g_login_type = LOGIN_AGENT Then
		If d_class = "A" Then
			row_count = same_dairiten.GetRowCount()
			If row_count > 0 Then
				Writeln "<td align='center' valign='top' rowspan=" & row_count & " class='tc2'>" & ds("DA_dairiten_id").Value & "</td>"
				Writeln "<td valign='top' rowspan=" & row_count & " class='tc2'>" & ReplaceTag(ds("DA_name").Value) & "</td>"
			End If
		End If

		row_count = same_company.GetRowCount()
		If row_count > 0 Then
			Writeln "<td valign='top' rowspan=" & row_count & " class='tc2'>" & ReplaceTag(ds("CM_name").Value) & "</td>"
		End If
	End If

	row_count = same_tantousha.GetRowCount()
	If row_count > 0 Then
		If IsNull(ds("TS_name1").Value) Then
			name = ds("TS_tantousha_id").Value
		Else
			name = ds("TS_name1").Value & ds("TS_name2").Value
		End If
		Writeln "<td valign='top' rowspan=" & row_count & " class='tc2'>" & ReplaceTag(name) & "</td>"
	End If

	If regist_project Then
		row_count = same_project.GetRowCount()
		If row_count > 0 Then
			Writeln "<td align='center' rowspan=" & row_count & " class='tc2'><a href='pjt_name.asp?project_id=" & ds("PJ_project_id").Value & "'>" & ds("PJ_project_id").Value & "</a></td>"
			Writeln "<td rowspan=" & row_count & " class='tc2'><a href='job_name.asp?project_id=" & ds("PJ_project_id").Value & "'>" & ReplaceTag(ds("PJ_project_name").Value) & "</a></td>"
		End If
	End If

	If IsNull(ds("JB_job_id").Value) Then
		Writeln "<td><br></td>"
		Writeln "<td><br></td>"
		Writeln "<td><br></td>"
		Writeln "<td><br></td>"
		Writeln "<td><br></td>"
		Writeln "<td><br></td>"
		Writeln "<td><br></td>"
		Writeln "<td><br></td>"
		Writeln "<td><br></td>"
		Writeln "<td><br></td>"
	Else
		If regist_job Then
			Writeln "<td align='center'><a href='job_name.asp?project_id=" & ds("PJ_project_id").Value & "&job_id=" & ds("JB_job_id").Value & "'>" & ds("JB_job_id").Value & "</a></td>"
			Writeln "<td><a href='cs/job_contents.asp?job_id=" & ds("JB_job_id").Value & "&back=pjt_go.asp'>" & ReplaceTag(ds("JB_job_name").Value) & "</a></td>"
		Else
			Writeln "<td align='center'>" & ds("JB_job_id").Value & "</td>"
			Writeln "<td>" & ReplaceTag(ds("JB_job_name").Value) & "</td>"
		End If
		Writeln "<td align='center'>" & DateFormat2(ds("SM_start_date").Value) & "</td>"
		Writeln "<td align='right'>" & NumFormat2(ds("SM_send_num").Value) & "</td>"

		send_count = CDbl(NVL(ds("SM_real_send").Value, 0))

		count = ds("CC_count").Value
		If click_link And count > 0 Then
			Writeln "<td align='right'><a href='cs/click_analyze.asp?job_id=" & ds("JB_job_id").Value & "'>" & NumFormat(count) & "</a></td>"
		Else
			Writeln "<td align='right'>" & NumFormat(count) & "</td>"
		End If
		Writeln "<td align='right'>" & Rate(NVL(count, 0), send_count) & "</td>"

		count = ds("SM_enq_count").Value
		If enq_link And count > 0 Then
			Writeln "<td align='right'><a href='cs/enquete.asp?job_id=" & ds("JB_job_id").Value & "'>" & NumFormat(count) & "</a></td>"
		Else
			Writeln "<td align='right'>" & NumFormat(count) & "</td>"
		End If
		Writeln "<td align='right'>" & Rate(NVL(count, 0), send_count) & "</td>"

		If imp_number Then
			count = ds("SM_imp_count").Value
			If imp_link And count > 0 Then
				Writeln "<td align='right'><a href='cs/impression.asp?job_id=" & ds("JB_job_id").Value & "'>" & NumFormat(count) & "</a></td>"
			Else
				Writeln "<td align='right'>" & NumFormat(count) & "</td>"
			End If
			Writeln "<td align='right'>" & Rate(NVL(count, 0), send_count) & "</td>"
		Else
			Writeln "<td align='right'>-</td>"
			Writeln "<td align='right'>-</td>"
		End If
	End If
	Writeln "</tr>"
	ds.MoveNext

	line = line + 1
Loop
%>
</table>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
