<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :ÇÕÇ¢ÇﬂÅ[ÇÈÅEnetéññ±ã«ópÉyÅ[ÉW
' Content:ÉÅÉìÉoÅ[ïÂèWä«óù
'******************************************************

'=== î}ëÃñºéÊìæ ===
Private Function GetBaitaiName(baitai_id)
	Dim sql, ds

	If baitai_id <> "" Then
		sql = "SELECT BT_name FROM T_BAITAI WHERE BT_baitai_id='" & baitai_id & "'"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds.EOF Then
			GetBaitaiName = ds(0).Value
		End If
	Else
		GetBaitaiName = "ëSÇƒ"
	End If
End Function

'=== ÉÅÉCÉìèàóù ===
SetHeaderTitle "ÇUÅDèTï î}ëÃï "
Response.ContentType = "application/vnd.ms-excel"

Dim sub1, sql, ds, ds1, ds2, ds3, i, click_total, kari_total, hon_total, baitai_id, where
Dim s_year, s_month, e_year, e_month
Dim start_ym, end_ym, start_date, end_date
Dim cur_date, cur_ym, cur_week, end_week, w, cur_ymw, e_date
Dim count1, count2, count3, t_count1, t_count2, t_count3
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
baitai_id = Request.Form("baitai_id")

start_ym = s_year & Right("0" & s_month, 2)
end_ym = e_year & Right("0" & e_month, 2)

If baitai_id <> "" Then
	where = " AND PR_baitai_id='" & baitai_id & "'"
Else
	where = " AND PR_baitai_id<>'03'"
End If

sub1 = "SELECT TRUNC(CL_date,'DD') AS CL_ymd,COUNT(*) AS CL_count1 FROM T_CLICK_LOG, T_PR" & vbCrLf & _
			"WHERE PR_pr_id=CL_pr_id(+)" & where & " AND TO_CHAR(CL_date,'YYYYMM') BETWEEN '" & start_ym & "' AND '" & end_ym & "'" & vbCrLf & _
			"GROUP BY TRUNC(CL_date,'DD')"
sql = "SELECT TO_YYYYMMW(CL_ymd) AS CL_week,SUM(CL_count1) AS CL_count FROM (" & sub1 & ")" & vbCrLf & _
			"GROUP BY TO_YYYYMMW(CL_ymd)" & vbCrLf & _
			"ORDER BY CL_week"
Set ds1 = CreateDynaset(sql, ORADYN_READONLY)

sub1 = "SELECT TRUNC(KT_regist_date,'DD') AS KT_ymd,COUNT(*) AS KT_count1 FROM T_KARI_TOUROKU, T_PR" & vbCrLf & _
			"WHERE PR_pr_id=KT_pr_id(+)" & where & " AND TO_CHAR(KT_regist_date,'YYYYMM') BETWEEN '" & start_ym & "' AND '" & end_ym & "'" & vbCrLf & _
			"GROUP BY TRUNC(KT_regist_date,'DD')"
sql = "SELECT TO_YYYYMMW(KT_ymd) AS KT_week,SUM(KT_count1) AS KT_count FROM (" & sub1 & ")" & vbCrLf & _
			"GROUP BY TO_YYYYMMW(KT_ymd)" & vbCrLf & _
			"ORDER BY KT_week"
Set ds2 = CreateDynaset(sql, ORADYN_READONLY)

sub1 = "SELECT TRUNC(MM_regist_date,'DD') AS MM_ymd,COUNT(*) AS MM_count1 FROM T_MEMBER, T_PR" & vbCrLf & _
			"WHERE PR_pr_id=MM_pr_id(+)" & where & " AND TO_CHAR(MM_regist_date,'YYYYMM') BETWEEN '" & start_ym & "' AND '" & end_ym & "'" & vbCrLf & _
			"GROUP BY TRUNC(MM_regist_date,'DD')"
sql = "SELECT TO_YYYYMMW(MM_ymd) AS MM_week,SUM(MM_count1) AS MM_count FROM (" & sub1 & ")" & vbCrLf & _
			"GROUP BY TO_YYYYMMW(MM_ymd)" & vbCrLf & _
			"ORDER BY MM_week"
Set ds3 = CreateDynaset(sql, ORADYN_READONLY)

start_date = CDate(s_year & "/" & s_month & "/01")
end_date = DateAdd("d", -1, DateAdd("m", 1, CDate(e_year & "/" & e_month & "/01")))

cur_date = start_date
cur_week = start_date
Do While cur_date <= end_date

	cur_ym = Year(cur_date) & Right("0" & Month(cur_date), 2)
	Writeln "<table border=1 cellpadding=1 cellspacing=0 width='100%' class='small'>"
	Writeln "Å°" & Year(cur_date) & "îN" & Month(cur_date) & "åéÅ@Å@î}ëÃÅÅ" & GetBaitaiName(baitai_id) & ""
	Writeln "<tr bgcolor='#E0FFE0'>"
	Writeln "<th></th>"
	Writeln "<th>∏ÿØ∏êî</th>"
	Writeln "<th>âºìoò^</th>"
	Writeln "<th>ìoò^êî</th>"
	Writeln "<th>CVRâº</th>"
	Writeln "<th>CVRñ{</th>"
	Writeln "</tr>"
	Writeln "<tr>"
	w = 1

	cur_week = CDate(Mid(cur_ym,1,4) & "/" & Mid(cur_ym,5,2) & "/01")
	end_week = DateAdd("d", -1, DateAdd("m", 1, cur_week))

	t_count1 = 0
	t_count2 = 0
	t_count3 = 0

	Do While cur_week <= end_week
		If DateAdd("ww", 1, cur_week) > end_week Then
			Writeln "<td align='right'>" & Day(cur_week) & "(" & WeekDayName(Weekday(cur_week),true) &") Å` " & Day(end_week) &  "(" & WeekDayName(Weekday(end_week),true) &")</td>"
		Else
			Writeln "<td align='right'>" & Day(cur_week) & "(" & WeekDayName(Weekday(cur_week),true) &") Å` " & Day(DateAdd("d", 7-WeekDay(cur_week), cur_week)) &  "(" & WeekDayName(Weekday(DateAdd("d", 7-WeekDay(cur_week), cur_week)),true) &")</td>"
		End If

		cur_ymw = Year(cur_date) & Right("0" & Month(cur_date), 2) & w

		count1 = 0
		Do While cur_ymw = ds1(0).Value
			count1 = Number(ds1(1).Value)
			ds1.MoveNext
		Loop
		Writeln "<td align='right'>" & NumFormat(count1) & "</td>"

		count2 = 0
		Do While cur_ymw = ds2(0).Value
			count2 = Number(ds2(1).Value)
			ds2.MoveNext
		Loop
		Writeln "<td align='right'>" & NumFormat(count2) & "</td>"

		count3 = 0
		Do While cur_ymw = ds3(0).Value
			count3 = Number(ds3(1).Value)
			ds3.MoveNext
		Loop
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
	Loop
	Writeln "<td align='center'>åv</td>"
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
