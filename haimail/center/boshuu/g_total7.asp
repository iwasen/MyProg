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
SetHeaderTitle "ÇVÅDåéï î}ëÃï "
Response.ContentType = "application/vnd.ms-excel"

Dim sql, ds, ds1, ds2, ds3, i, click_total, kari_total, hon_total, baitai_id, where
Dim s_year, s_month, e_year, e_month
Dim start_ym, end_ym
Dim start_date, end_date
Dim cur_date, cur_ym
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

sql = "SELECT TO_CHAR(CL_date, 'YYYYMM') AS CL_month,COUNT(*) AS CL_count FROM T_CLICK_LOG, T_PR" & vbCrLf & _
			"WHERE PR_pr_id=CL_pr_id(+)" & where & " AND TO_CHAR(CL_date,'YYYYMM') BETWEEN '" & start_ym & "' AND '" & end_ym & "'" & vbCrLf & _
			"GROUP BY TO_CHAR(CL_date, 'YYYYMM')" & vbCrLf & _
			"ORDER BY CL_month"
Set ds1 = CreateDynaset(sql, ORADYN_READONLY)

sql = "SELECT TO_CHAR(KT_regist_date, 'YYYYMM') AS KT_month,COUNT(*) AS KT_count FROM T_KARI_TOUROKU, T_PR" & vbCrLf & _
			"WHERE PR_pr_id=KT_pr_id(+)" & where & " AND TO_CHAR(KT_regist_date,'YYYYMM') BETWEEN '" & start_ym & "' AND '" & end_ym & "'" & vbCrLf & _
			"GROUP BY TO_CHAR(KT_regist_date, 'YYYYMM')" & vbCrLf & _
			"ORDER BY KT_month"
Set ds2 = CreateDynaset(sql, ORADYN_READONLY)

sql = "SELECT TO_CHAR(MM_regist_date, 'YYYYMM') AS MM_month,COUNT(*) AS MM_count FROM T_MEMBER, T_PR" & vbCrLf & _
			"WHERE PR_pr_id=MM_pr_id(+)" & where & " AND TO_CHAR(MM_regist_date,'YYYYMM') BETWEEN '" & start_ym & "' AND '" & end_ym & "'" & vbCrLf & _
			"GROUP BY TO_CHAR(MM_regist_date, 'YYYYMM')" & vbCrLf & _
			"ORDER BY MM_month"
Set ds3 = CreateDynaset(sql, ORADYN_READONLY)
%>
<table border=1 cellpadding=1 cellspacing=0 width="100%" class="small">
	î}ëÃÅÅ <%=GetBaitaiName(baitai_id)%>
	<tr bgcolor="#E0FFE0">
		<th></th>
		<th>∏ÿØ∏êî</th>
		<th>âºìoò^</th>
		<th>ìoò^êî</th>
		<th>CVRâº</th>
		<th>CVRñ{</th>
	</tr>
<%


start_date = CDate(s_year & "/" & s_month & "/01")
end_date = CDate(e_year & "/" & e_month & "/01")

cur_date = start_date
Do While cur_date <= end_date

	cur_ym = Year(cur_date) & Right("0" & Month(cur_date), 2)

	Writeln "<td align='right'>" & Year(cur_date) & "îN" & Month(cur_date) & "åé</td>"

	count1 = 0
	If Not ds1.EOF Then
		If cur_ym = ds1(0).Value Then
			count1 = Number(ds1(1).Value)
			ds1.MoveNext
		End If
	End If
	Writeln "<td align='right'>" & NumFormat(count1) & "</td>"

	count2 = 0
	If Not ds2.EOF Then
		If cur_ym = ds2(0).Value Then
			count2 = Number(ds2(1).Value)
			ds2.MoveNext
		End If
	End If
	Writeln "<td align='right'>" & NumFormat(count2) & "</td>"

	count3 = 0
	If Not ds3.EOF Then
		If cur_ym = ds3(0).Value Then
			count3 = Number(ds3(1).Value)
			ds3.MoveNext
		End If
	End If
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

	cur_date = DateAdd("m", 1, cur_date)
Loop

Writeln "<td align='center'>åv</td>"
Writeln "<td align='right'>" & NumFormat(t_count1) & "</td>"
Writeln "<td align='right'>" & NumFormat(t_count2) & "</td>"
Writeln "<td align='right'>" & NumFormat(t_count3) & "</td>"

If Not t_count3 = 0 Then
	Writeln "<td align='right'>" & FormatPercent(t_count2/t_count1,2) & "</td>"
	Writeln "<td align='right'>" & FormatPercent(t_count3/t_count1,2) & "</td>"
Else
	Writeln "<td align='right'>-</td>"
	Writeln "<td align='right'>-</td>"
End If
Writeln "</tr>"
%>
</table>
</body>
</html>
