<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:スケジュール管理画面
'******************************************************

Dim s_year, s_month, d, st, ed, week, color, sql, ds, job, sum, today

SetHeaderTitle "スケジュール管理"

today = Date

s_year = Request.QueryString("year")
If IsEmpty(s_year) Then
	s_year = Year(today)
Else
	s_year = CInt(s_year)
End If

s_month = Request.QueryString("month")
If IsEmpty(s_month) Then
	s_month = Month(today)
Else
	s_month = CInt(s_month)
End If

d = CDate(s_year & "/" & s_month & "/1")
st = DateAdd("d", -(WeekDay(d) - 1), d)

d = DateAdd("m", 1, d)
ed = DateAdd("d", 7 - WeekDay(d), d)

sql = "SELECT JB_job_id,JB_status,TRUNC(SM_start_date,'DD') SM_date,TO_CHAR(SM_start_date,'HH24') SM_hour,TO_CHAR(SM_start_date,'MI') SM_minute,SM_send_num" & vbCrLf & _
			"FROM T_SEND_MAIL,T_JOB" & vbCrLf & _
			"WHERE SM_start_date BETWEEN '" & st & "' AND '" & ed & "' AND JB_mail_id=SM_mail_id" & vbCrLf & _
			"ORDER BY SM_start_date,JB_job_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<form method="get" name="form1">
	<tr>
		<td>
			<select name="year" onchange="submit()"><%SelectYear1 s_year%></select>年
			<select name="month" onchange="submit()"><%SelectMonth s_month%></select>月
		</td>
		<td align="right">
			<input type="button" value=" 戻る " onclick="location.href='../menu.asp'">
		</td>
	</tr>
	</form>
</table>

<table <%=LIST_TABLE%> width="100%">
	<tr bgcolor="#E0FFE0">
		<th width="10%"><font color="red">日</font></th>
		<th width="10%">月</th>
		<th width="10%">火</th>
		<th width="10%">水</th>
		<th width="10%">木</th>
		<th width="10%">金</th>
		<th width="10%"><font color="blue">土</font></th>
	</tr>
<%
d = st
Do While d <= ed
	week = WeekDay(d)
	If week = 1 Then
		Writeln "<tr>"
	End If

	job = ""
	sum = 0
	If Not ds.EOF Then
		Do Until ds.EOF
			If ds("SM_date").Value <> d Then
				Exit Do
			End If

			Select Case ds("JB_status").Value
			Case "0"
				color = "red"
			Case "1"
				color = "green"
			Case "2"
				color = "blue"
			End Select

			job = job & "<tr>"
			job = job & "<td align='center'><a href='../project/cs/job_contents.asp?job_id=" & ds("JB_job_id").Value & "&back=" & Server.URLEncode("../schedule/index.asp?" & Request.QueryString.Item) & "' title='ジョブ登録内容を表示します'><font color='" & color & "'>" & ds("JB_job_id").Value & "</font></a></td>"
			job = job & "<td align='center'>" & ds("SM_hour").Value & ":" & ds("SM_minute").Value & "</td>"
			job = job & "<td align='right'>" & NumFormat(ds("SM_send_num").Value) & "</td>"
			job = job & "</tr>"
			sum = sum + CLng(NVL(ds("SM_send_num").Value, "0"))
			ds.MoveNext
		Loop
	End If

	If job = "" Then
		job = "<tr><td colspan=3><br></td></tr>"
	End If

	If sum = 0 Then
		sum = ""
	Else
		sum = NumFormat(sum)
	End If

	If Month(d) = s_month Then
		Select Case week
		Case 1
			color = "red"
		Case 7
			color = "blue"
		Case Else
			color = "green"
		End Select
	Else
		color = "gray"
	End If

	Writeln "<td width='10%' valign='top'>"
	Writeln "<table border=0 cellpadding=1 cellspacing=0 width='100%'>"
	If d = today And Month(d) = s_month Then
		Writeln "<tr bgcolor='Aquamarine'>"
	Else
		Writeln "<tr class='tc1'>"
	End If
	Writeln "<td align='center' width='10%'><font size='+1' color='" & color & "'><b>" & Day(d) & "</b></font></td>"
	Writeln "<td align='right' colspan=2><b>" & sum & "</b></td></tr>"
	Writeln job
	Writeln "</table>"
	Writeln "</td>"

	If week = 7 Then
		Writeln "</tr>"
	End If

	d = DateAdd("d", 1, d)
Loop
%>
</table>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
