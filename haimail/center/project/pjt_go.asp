<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:プロジェクト進行管理画面
'******************************************************

'=== 代理店選択 ===
Private Sub SelectDairiten(dairiten_id)
	Dim sql, ds

	sql = "SELECT DA_dairiten_id,DA_name FROM T_DAIRITEN ORDER BY DA_dairiten_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Writeln "<option value=''" & Selected("", dairiten_id) & ">- 全て -</option>"
	Do Until ds.EOF
		Writeln "<option value='" & ds(0).Value & "'" & Selected(ds(0).Value, dairiten_id) & ">" & ds(1).Value & "</option>"
		ds.MoveNext
	Loop
End Sub

'=== 企業選択 ===
Private Sub SelectCompany(company_id, dairiten_id)
	Dim sql, ds

	sql = "SELECT CM_company_id,CM_name FROM T_COMPANY"
	If dairiten_id <> "" Then
		sql = sql & " WHERE CM_dairiten_id='" & dairiten_id & "'"
	End If
	sql = sql & " ORDER BY CM_company_id"

	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Writeln "<option value=''" & Selected("", company_id) & ">- 全て -</option>"
	Do Until ds.EOF
		Writeln "<option value='" & ds(0).Value & "'" & Selected(ds(0).Value, company_id) & ">" & ds(1).Value & "</option>"
		ds.MoveNext
	Loop
End Sub

'=== 企業担当者選択 ===
Private Sub SelectTantousha(tantousha_id, company_id)
	Dim sql, ds

	sql = "SELECT TS_tantousha_id,TS_name1,TS_name2 FROM T_TANTOUSHA"
	If company_id <> "" Then
		sql = sql & " WHERE TS_company_id='" & company_id & "'"
	End If
	sql = sql & " ORDER BY TS_tantousha_id"

	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Writeln "<option value=''" & Selected("", company_id) & ">- 全て -</option>"
	Do Until ds.EOF
		Writeln "<option value='" & ds(0).Value & "'" & Selected(ds(0).Value, tantousha_id) & ">" & ds(1).Value & "</option>"
		ds.MoveNext
	Loop
End Sub

'=== 発信日選択肢 ===
Private Sub SelectSendDate(send_date)
	Dim y, m, ym, today

	today = Date
	y = Year(today)
	m = Month(today)

	Writeln "<option value=''" & Selected(send_date, "") & ">- 全て -</option>"
	Do
		ym = y & Right("0" & m, 2)
		Response.Write "<option value='"& ym & "'" & Selected(send_date, ym) & ">" & y & "/" & Right("0" & m, 2) & "</option>"
		m = m - 1
		If m = 0 Then
			m = 12
			y = y - 1
		End If
	Loop While ym >= "200102"
End Sub

'=== 発信状態表示 ===
Private Function SendStatus(status)
	If IsNull(status) Then
		SendStatus = "-"
	Else
		Select Case ds("SM_status").Value
		Case "0"
			SendStatus = "未送信"
		Case "1"
			SendStatus = "未送信"
		Case "2"
			SendStatus = "送信中"
		Case "3"
			SendStatus = "送信ｴﾗｰ"
		Case "4"
			SendStatus = "<nobr>送信<wbr>待機</nobr>"
		Case "9"
			SendStatus = "<nobr>送信<wbr>終了</nobr>"
		End Select
	End If
End Function

'=== 承認状態表示 ===
Private Function ApproveStatus(status)
	Select Case status
	Case "0"
		ApproveStatus = "<font color='red'>未</font>"
	Case "1"
		ApproveStatus = "<font color='green'>待</font>"
	Case "2"
		ApproveStatus = "<a href='status.asp?job_id=" & job_id & "' title='未承認状態に変更します'>済</a>"
	End Select
End Function

'=== メール種類名取得 ===
Function MailTypeName(mail_type)
	Select Case mail_type
		Case "LT"
			MailTypeName = "手紙"
		Case "HG"
			MailTypeName = "葉書"
		Case "CR"
			MailTypeName = "チラシ"
		Case "TG"
			MailTypeName = "電報"
		Case "OH"
			MailTypeName = "往復葉書"
		Case Else
			MailTypeName = "不明"
	End Select
End Function

'=== メイン処理 ===
Dim sub1, sql, ds, line, nline, dairiten_id, company_id, tantousha_id, from, where, same_project, row_count, count, send_date, project_id, job_id

dairiten_id = Request.QueryString("dairiten_id")
If IsEmpty(dairiten_id) Then
	dairiten_id = Session("dairiten_id")
Else
	Session("dairiten_id") = dairiten_id
End If

company_id = Request.QueryString("company_id")
If IsEmpty(company_id) Then
	company_id = Session("company_id")
Else
	Session("company_id") = company_id
End If

tantousha_id = Request.QueryString("tantousha_id")
If IsEmpty(tantousha_id) Then
	tantousha_id = Session("tantousha_id")
Else
	Session("tantousha_id") = tantousha_id
End If

nline = Request.QueryString("nline")
If IsEmpty(nline) Then
	nline = Session("pjt_nline")
Else
	Session("pjt_nline") = nline
End If
If Len(nline) > 0 Then
	nline = CInt(nline)
Else
	nline = 50
End If

send_date = Request.QueryString("send_date")
If IsEmpty(send_date) Then
	send_date = Session("send_date")
Else
	Session("send_date") = send_date
End If

SetHeaderTitle "プロジェクト進行管理"

sub1 = "SELECT CC_job_id,SUM(CC_click_num) AS CC_count FROM T_CLICK_COUNT GROUP BY CC_job_id"

from = ""
where = ""
If send_date <> "" Then
	where  = where & "PJ_project_id IN (SELECT DISTINCT JB_project_id FROM T_JOB,T_SEND_MAIL WHERE TO_CHAR(SM_start_date,'YYYYMM')='" & send_date & "' AND JB_mail_id=SM_mail_id) AND "
End If
If tantousha_id <> "" Then
	where = where & "PJ_tantousha_id='" & tantousha_id & "' AND "
End If
If company_id <> "" Then
	from = from & ",T_TANTOUSHA"
	where = where & "TS_company_id='" & company_id & "' AND PJ_tantousha_id=TS_tantousha_id AND "
End If
If dairiten_id <> "" Then
	If company_id = "" Then
		from = from & ",T_TANTOUSHA"
		where = where & "PJ_tantousha_id=TS_tantousha_id AND "
	End If
	from = from & ",T_COMPANY"
	where = where & "CM_dairiten_id='" & dairiten_id & "' AND TS_company_id=CM_company_id AND "
End If

sql = "SELECT PJ_project_id,PJ_project_name,JB_job_id,JB_job_name,JB_mail_type,JB_status,SM_start_date,SM_end_date,SM_limit_date,SM_send_num,SM_point_jushin,SM_point_enquete,SM_point_kansou,SM_status,SM_real_send,SM_enq_count,SM_imp_count,CC_count" & vbCrLf & _
			"FROM (" & sub1 & "),T_SEND_MAIL,T_JOB,T_PROJECT" & from & vbCrLf & _
			"WHERE " & where & "PJ_project_id=JB_project_id(+) AND JB_mail_id=SM_mail_id(+) AND JB_job_id=CC_job_id(+)" & vbCrLf & _
			"ORDER BY PJ_project_id DESC,JB_job_id"
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
	<form method="get" name="form1">
	<tr>
		<td>
			<nobr><font class="hc">代理店</font><select name="dairiten_id" onchange="OnChange_dairiten_id()"><%SelectDairiten dairiten_id%></select></nobr>
			<nobr><font class="hc">企業</font><select name="company_id" onchange="OnChange_company_id()"><%SelectCompany company_id, dairiten_id%></select></nobr>
			<nobr><font class="hc">担当者</font><select name="tantousha_id" onchange="submit()"><%SelectTantousha tantousha_id, company_id%></select></nobr>
			<nobr><font class="hc">発信日</font><select name="send_date" onchange="submit()"><%SelectSendDate send_date%></select></nobr>
			<nobr><font class="hc">表示行数</font><select name="nline" onchange="submit()"><%SelectDispLine nline%></select></nobr>
		</td>
		<td align="right">
			<input type="button" value="集計出力" onclick="location.href='shuukei/index.asp'">
			<input type="button" value=" 戻る " onclick="location.href='index.asp'">
		</td>
	</tr>
	</form>
</table>

<table <%=LIST_TABLE%> width="100%" class="small">
	<tr bgcolor="#E0FFE0">
		<th rowspan=2>ﾌﾟﾛｼﾞｪｸﾄID</th>
		<th rowspan=2>ﾌﾟﾛｼﾞｪｸﾄ名</th>
		<th rowspan=2>ｼﾞｮﾌﾞID</th>
		<th rowspan=2>ｼﾞｮﾌﾞ名</th>
		<th rowspan=2>種類</th>
		<th colspan=2>ポイント</th>
		<th rowspan=2>送信期間</th>
		<th rowspan=2>締切り</th>
		<th rowspan=2>最大発信数</th>
		<th rowspan=2>発信数</th>
		<th colspan=3>返信数</th>
		<th rowspan=2><nobr>発信<wbr>状態</nobr></th>
		<th rowspan=2>承認</th>
	</tr>
	<tr bgcolor="#E0FFE0">
		<th>受信</th>
		<th>返信</th>
		<th>ｱﾝｹｰﾄ</th>
		<th>感想文</th>
		<th>ｸﾘｯｸ数</th>
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
		Writeln "<td><br></td>"
		Writeln "<td><br></td>"
		Writeln "<td><br></td>"
		Writeln "<td><br></td>"
		Writeln "<td><br></td>"
		Writeln "<td><br></td>"
		Writeln "<td><br></td>"
	Else
		job_id = ds("JB_job_id").Value
		Writeln "<td align='center'><a href='cs/job_contents.asp?job_id=" & job_id & "&back=pjt_go.asp' title='ジョブ内容を表示・変更します'>" & job_id & "</a></td>"
		Writeln "<td>" & ReplaceTag(ds("JB_job_name").Value) & "</td>"
		Writeln "<td align='center'><font title='" & MailTypeName(ds("JB_mail_type").Value) & "'>" & ds("JB_mail_type").Value & "</font></td>"
		Writeln "<td align='right'>" & NumFormat(ds("SM_point_jushin").Value) & "</td>"
		Writeln "<td align='right'>" & NumFormat(Number(ds("SM_point_enquete").Value) + ds("SM_point_kansou").Value) & "</td>"
		Writeln "<td align='center'>" & DateFormat3(ds("SM_start_date").Value, ds("SM_end_date").Value) & "</td>"
		Writeln "<td align='center'>" & DateFormat2(ds("SM_limit_date").Value) & "</td>"
		Writeln "<td align='right'>" & NumFormat2(ds("SM_send_num").Value) & "</td>"

		count = ds("SM_real_send").Value
		If count > 0 Then
			Writeln "<td align='right'><a href='cs/send.asp?job_id=" & ds("JB_job_id").Value & "' title='発信者情報を表示します'>" & NumFormat(count) & "</a></td>"
		Else
			Writeln "<td align='right'>" & NumFormat(count) & "</td>"
		End If

		count = ds("SM_enq_count").Value
		If count > 0 Then
			Writeln "<td align='right'><a href='cs/enquete.asp?job_id=" & ds("JB_job_id").Value & "' title='アンケートレスポンスを表示します'>" & NumFormat(count) & "</a></td>"
		Else
			Writeln "<td align='right'>" & NumFormat(count) & "</td>"
		End If

		count = ds("SM_imp_count").Value
		If count > 0 Then
			Writeln "<td align='right'><a href='cs/impression.asp?job_id=" & ds("JB_job_id").Value & "' title='感想文レスポンスを表示します'>" & NumFormat(count) & "</a></td>"
		Else
			Writeln "<td align='right'>" & NumFormat(count) & "</td>"
		End If

		count = ds("CC_count").Value
		If count > 0 Then
			Writeln "<td align='right'><a href='cs/click_analyze.asp?job_id=" & ds("JB_job_id").Value & "' title='クリック解析を表示します'>" & NumFormat(count) & "</a></td>"
		Else
			Writeln "<td align='right'>" & NumFormat(count) & "</td>"
		End If

		Writeln "<td align='center'>" & SendStatus(ds("SM_status").Value) & "</td>"
		Writeln "<td align='center'>" & ApproveStatus(ds("JB_status").Value) & "</td>"
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
