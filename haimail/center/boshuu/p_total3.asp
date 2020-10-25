<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:メンバー募集管理　メンバー別週別トータル出力
'******************************************************

'=== メイン処理 ===
SetHeaderTitle "４．メンバー別週別トータル"
Response.ContentType = "application/vnd.ms-excel"

Dim sql, sub1, sub2, sub3, sub4, ds, i, click_total, kari_total, hon_total, where
Dim s_year, s_month, e_year, e_month
Dim start_ym, end_ym, start_date, end_date
Dim cur_date, cur_ym, cur_week, end_week, w, e_date
Dim count1, count2, count3, t_count1, t_count2, t_count3
Dim same_ymw, row_count
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
</head>
<body>
<table cellspacing=0 cellpadding=0 width="100%">
	<tr>
		<td align="left" width="40%"><%=title_text%></td>
	</tr>
</table>
<br>

<%
s_year = Request.Form("s_year")
s_month = Request.Form("s_month")
e_year = Request.Form("e_year")
e_month = Request.Form("e_month")

start_ym = s_year & Right("0" & s_month, 2)
end_ym = e_year & Right("0" & e_month, 2)

where = " AND PR_baitai_id='03'"

sub4 = "SELECT TRUNC(CL_date,'DD') AS CL_ymd,CL_pr_id,COUNT(*) AS CL_count1" & vbCrLf & _
			"FROM T_CLICK_LOG" & vbCrLf & _
			"WHERE TO_CHAR(CL_date,'YYYYMM') BETWEEN '" & start_ym & "' AND '" & end_ym & "'" & vbCrLf & _
			"GROUP BY TRUNC(CL_date,'DD'),CL_pr_id"
sub1 = "SELECT TO_YYYYMMW(CL_ymd) AS CL_ymw,CL_pr_id,SUM(CL_count1) AS CL_count" & vbCrLf & _
			"FROM (" & sub4 & ")" & vbCrLf & _
			"GROUP BY TO_YYYYMMW(CL_ymd),CL_pr_id"

sub4 = "SELECT TRUNC(KT_regist_date,'DD') AS KT_ymd,KT_pr_id,COUNT(*) AS KT_count1" & vbCrLf & _
			"FROM T_KARI_TOUROKU" & vbCrLf & _
			"WHERE TO_CHAR(KT_regist_date,'YYYYMM') BETWEEN '" & start_ym & "' AND '" & end_ym & "'" & vbCrLf & _
			"GROUP BY TRUNC(KT_regist_date,'DD'),KT_pr_id"
sub2 = "SELECT TO_YYYYMMW(KT_ymd) AS KT_ymw,KT_pr_id,SUM(KT_count1) AS KT_count" & vbCrLf & _
			"FROM (" & sub4 & ")" & vbCrLf & _
			"GROUP BY TO_YYYYMMW(KT_ymd),KT_pr_id"

sub4 = "SELECT TRUNC(MM_regist_date,'DD') AS MM_ymd,MM_pr_id,COUNT(*) AS MM_count1" & vbCrLf & _
			"FROM T_MEMBER" & vbCrLf & _
			"WHERE TO_CHAR(MM_regist_date,'YYYYMM') BETWEEN '" & start_ym & "' AND '" & end_ym & "'" & vbCrLf & _
			"GROUP BY TRUNC(MM_regist_date,'DD'),MM_pr_id"
sub3 = "SELECT TO_YYYYMMW(MM_ymd) AS MM_ymw,MM_pr_id,SUM(MM_count1) AS MM_count" & vbCrLf & _
			"FROM (" & sub4 & ")" & vbCrLf & _
			"GROUP BY TO_YYYYMMW(MM_ymd),MM_pr_id"

sql = "SELECT *" & vbCrLf & _
			"FROM (" & sub1 & "),(" & sub2 & "),(" & sub3 & "),T_PR,T_KOUKOKU_TYPE" & vbCrLf & _
			"WHERE CL_ymw=KT_ymw(+) AND CL_pr_id=KT_pr_id(+) AND CL_ymw=MM_ymw(+) AND CL_pr_id=MM_pr_id(+) AND CL_pr_id=PR_pr_id AND PR_koukoku_id=KT_koukoku_id" & where & vbCrLf & _
			"ORDER BY CL_ymw,PR_pr_id"

Set ds = CreateDynaset(sql, ORADYN_READONLY)

Set same_ymw = New CJoinSameItem
same_ymw.Prepare ds, "CL_ymw"

start_date = CDate(s_year & "/" & s_month & "/01")
end_date = DateAdd("d", -1, DateAdd("m", 1, CDate(e_year & "/" & e_month & "/01")))

cur_date = start_date
cur_week = start_date
Do While cur_date <= end_date And Not ds.EOF

	cur_ym = Year(cur_date) & Right("0" & Month(cur_date), 2)
	Writeln "<table border=1 cellpadding=1 cellspacing=0 width='100%' class='small'>"
	Writeln "■" & Year(cur_date) & "年" & Month(cur_date) & "月"
	Writeln "<tr bgcolor='#E0FFE0'>"
	Writeln "<th></th>"
	Writeln "<th>PRID</th>"
	Writeln "<th>メンバーID</th>"
	Writeln "<th>登録アドレス</th>"
	Writeln "<th>ｸﾘｯｸ数</th>"
	Writeln "<th>仮登録</th>"
	Writeln "<th>登録数</th>"
	Writeln "<th>CVR仮</th>"
	Writeln "<th>CVR本</th>"
	Writeln "</tr>"
	Writeln "<tr>"
	w = 1

	cur_week = CDate(Mid(cur_ym,1,4) & "/" & Mid(cur_ym,5,2) & "/01")
	end_week = DateAdd("d", -1, DateAdd("m", 1, cur_week))

	t_count1 = 0
	t_count2 = 0
	t_count3 = 0

	Do While Not ds.EOF
		If Left(ds("CL_ymw").Value, 6) <> cur_ym Then
			Exit Do
		End If

		row_count = same_ymw.GetRowCount
		If row_count > 0 Then
			If DateAdd("ww", 1, cur_week) > end_week Then
				Writeln "<td rowspan=" & row_count & " align='right' valign='top'>" & Day(cur_week) & "(" & WeekDayName(Weekday(cur_week),true) &") ～ " & Day(end_week) &  "(" & WeekDayName(Weekday(end_week),true) &")</td>"
			Else
				Writeln "<td rowspan=" & row_count & " align='right' valign='top'>" & Day(cur_week) & "(" & WeekDayName(Weekday(cur_week),true) &") ～ " & Day(DateAdd("d", 7-WeekDay(cur_week), cur_week)) &  "(" & WeekDayName(Weekday(DateAdd("d", 7-WeekDay(cur_week), cur_week)),true) &")</td>"
			End If
		End If

		count1 = Number(ds("CL_count").Value)
		count2 = Number(ds("KT_count").Value)
		count3 = Number(ds("MM_count").Value)

		Writeln "<td>" & ds("PR_pr_id").Value & "</td>"
		Writeln "<td>" & ds("PR_member_id").Value & "</td>"
		Writeln "<td>" & ReplaceTag(ds("PR_regist_adr").Value) & "</td>"
		Writeln "<td align='right'>" & NumFormat(count1) & "</td>"
		Writeln "<td align='right'>" & NumFormat(count2) & "</td>"
		Writeln "<td align='right'>" & NumFormat(count3) & "</td>"

		If Not count1 = 0 Then
			Writeln "<td align='right'>" & FormatPercent(count2/count1,2) & "</td>"
			Writeln "<td align='right'>" & FormatPercent(count3/count1,2) & "</td>"
		Else
			Writeln "<td align='right'>-</td>"
			Writeln "<td align='right'>-</td>"
		End If
		Writeln "</tr>"
		t_count1 = CLng(t_count1) + CLng(count1)
		t_count2 = CLng(t_count2) + CLng(count2)
		t_count3 = CLng(t_count3) + CLng(count3)
		w = w + 1
		cur_week = DateAdd("d", 8-WeekDay(cur_week), cur_week)

		ds.MoveNext
	Loop

	Writeln "<td align='center'>計</td>"
	Writeln "<td></td><td></td><td></td>"
	Writeln "<td align='right'>" & NumFormat(t_count1) & "</td>"
	Writeln "<td align='right'>" & NumFormat(t_count2) & "</td>"
	Writeln "<td align='right'>" & NumFormat(t_count3) & "</td>"

	If Not t_count1 = 0 Then
		Writeln "<td align='right'>" & FormatPercent(t_count2/t_count1,2) & "</td>"
		Writeln "<td align='right'>" & FormatPercent(t_count3/t_count1,2) & "</td>"
	Else
		Writeln "<td align='right'>-</td>"
		Writeln "<td align='right'>-</td>"
	End If
	Writeln "</tr>"
	Writeln "</table>"
	Writeln "<br>"
	cur_date = DateAdd("m", 1, cur_date)

Loop
%>
</body>
</html>
