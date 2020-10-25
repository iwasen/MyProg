<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:メンバー募集管理
'******************************************************


SetHeaderTitle "８．広告タイプ別累計"
Response.ContentType = "application/vnd.ms-excel"

Dim sql, ds, ds1, ds2, ds3, kt_id
Dim s_year, s_month, e_year, e_month
Dim start_ym, end_ym
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

start_ym = s_year & Right("0" & s_month, 2)
end_ym = e_year & Right("0" & e_month, 2)

sql = "SELECT PR_koukoku_id AS CL_koukoku,COUNT(*) AS CL_count FROM T_CLICK_LOG, T_PR" & vbCrLf & _
			"WHERE PR_baitai_id<>'03' AND PR_pr_id=CL_pr_id(+) AND TO_CHAR(CL_date,'YYYYMM') BETWEEN '" & start_ym & "' AND '" & end_ym & "'" & vbCrLf & _
			"GROUP BY PR_koukoku_id" & vbCrLf & _
			"ORDER BY CL_koukoku"
Set ds1 = CreateDynaset(sql, ORADYN_READONLY)

sql = "SELECT PR_koukoku_id AS KT_koukoku,COUNT(*) AS KT_count FROM T_KARI_TOUROKU, T_PR" & vbCrLf & _
			"WHERE PR_baitai_id<>'03' AND PR_pr_id=KT_pr_id(+) AND TO_CHAR(KT_regist_date,'YYYYMM') BETWEEN '" & start_ym & "' AND '" & end_ym & "'" & vbCrLf & _
			"GROUP BY PR_koukoku_id" & vbCrLf & _
			"ORDER BY KT_koukoku"
Set ds2 = CreateDynaset(sql, ORADYN_READONLY)

sql = "SELECT PR_koukoku_id AS MM_koukoku,COUNT(*) AS MM_count FROM T_MEMBER, T_PR" & vbCrLf & _
			"WHERE PR_baitai_id<>'03' AND PR_pr_id=MM_pr_id(+) AND TO_CHAR(MM_regist_date,'YYYYMM') BETWEEN '" & start_ym & "' AND '" & end_ym & "'" & vbCrLf & _
			"GROUP BY PR_koukoku_id" & vbCrLf & _
			"ORDER BY MM_koukoku"
Set ds3 = CreateDynaset(sql, ORADYN_READONLY)

sql = "SELECT * FROM T_KOUKOKU_TYPE ORDER BY KT_koukoku_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)

Writeln "<table border=1 cellpadding=1 cellspacing=0 width='100%' class='small'>"
Writeln "■" & s_year & "年" & s_month & "月　～　" & e_year & "年" & e_month & "月"
Writeln "<tr bgcolor='#E0FFE0'>"
Writeln "<th></th>"
Writeln "<th>ｸﾘｯｸ数</th>"
Writeln "<th>仮登録</th>"
Writeln "<th>登録数</th>"
Writeln "<th>CVR仮</th>"
Writeln "<th>CVR本</th>"
Writeln "</tr>"

Do Until ds.EOF
	kt_id = ds("KT_koukoku_id").value

	Writeln "<tr>"
	Writeln "<td align='center'>" & ds("KT_name").value & "</td>"

	count1 = 0
	If kt_id = ds1(0).Value Then
		count1 = Number(ds1(1).Value)
		ds1.MoveNext
	End If
	Writeln "<td align='right'>" & NumFormat(count1) & "</td>"

	count2 = 0
	If kt_id = ds2(0).Value Then
		count2 = Number(ds2(1).Value)
		ds2.MoveNext
	End If
	Writeln "<td align='right'>" & NumFormat(count2) & "</td>"

	count3 = 0
	If kt_id = ds3(0).Value Then
		count3 = Number(ds3(1).Value)
		ds3.MoveNext
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

	ds.MoveNext
Loop

	Writeln "<td align='center'>計</td>"
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
Writeln "</table>"
Writeln "<br>"
%>
</body>
</html>
