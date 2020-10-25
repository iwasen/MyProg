<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:メンバー募集管理　メンバー別月別トータル出力
'******************************************************

'=== メイン処理 ===
SetHeaderTitle "４．メンバー別月別トータル"
Response.ContentType = "application/vnd.ms-excel"

Dim sql, sub1, sub2, sub3, ds, i, click_total, kari_total, hon_total, where
Dim s_year, s_month, e_year, e_month
Dim start_ym, end_ym
Dim cur_date
Dim count1, count2, count3, t_count1, t_count2, t_count3
Dim same_ym, row_count
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
<table border=1 cellpadding=1 cellspacing=0 width="100%" class="small">
	<tr bgcolor="#E0FFE0">
		<th></th>
		<th>PRID</th>
		<th>メンバーID</th>
		<th>登録アドレス</th>
		<th>ｸﾘｯｸ数</th>
		<th>仮登録</th>
		<th>登録数</th>
		<th>CVR仮</th>
		<th>CVR本</th>
	</tr>
<%
s_year = Request.Form("s_year")
s_month = Request.Form("s_month")
e_year = Request.Form("e_year")
e_month = Request.Form("e_month")

start_ym = s_year & Right("0" & s_month, 2)
end_ym = e_year & Right("0" & e_month, 2)

where = " AND PR_baitai_id='03'"

sub1 = "SELECT TO_CHAR(CL_date, 'YYYYMM') AS CL_ym,CL_pr_id,COUNT(*) AS CL_count" & vbCrLf & _
			"FROM T_CLICK_LOG" & vbCrLf & _
			"WHERE TO_CHAR(CL_date,'YYYYMM') BETWEEN '" & start_ym & "' AND '" & end_ym & "'" & vbCrLf & _
			"GROUP BY TO_CHAR(CL_date, 'YYYYMM'),CL_pr_id"

sub2 = "SELECT TO_CHAR(KT_regist_date, 'YYYYMM') AS KT_ym,KT_pr_id,COUNT(*) AS KT_count" & vbCrLf & _
			"FROM T_KARI_TOUROKU" & vbCrLf & _
			"WHERE TO_CHAR(KT_regist_date,'YYYYMM') BETWEEN '" & start_ym & "' AND '" & end_ym & "'" & vbCrLf & _
			"GROUP BY TO_CHAR(KT_regist_date, 'YYYYMM'),KT_pr_id"

sub3 = "SELECT TO_CHAR(MM_regist_date, 'YYYYMM') AS MM_ym,MM_pr_id,COUNT(*) AS MM_count" & vbCrLf & _
			"FROM T_MEMBER" & vbCrLf & _
			"WHERE TO_CHAR(MM_regist_date,'YYYYMM') BETWEEN '" & start_ym & "' AND '" & end_ym & "'" & vbCrLf & _
			"GROUP BY TO_CHAR(MM_regist_date, 'YYYYMM'),MM_pr_id"

sql = "SELECT *" & vbCrLf & _
			"FROM (" & sub1 & "),(" & sub2 & "),(" & sub3 & "),T_PR" & vbCrLf & _
			"WHERE CL_ym=KT_ym(+) AND CL_pr_id=KT_pr_id(+) AND CL_ym=MM_ym(+) AND CL_pr_id=MM_pr_id(+) AND CL_pr_id=PR_pr_id" & where & vbCrLf & _
			"ORDER BY CL_ym,PR_pr_id"

Set ds = CreateDynaset(sql, ORADYN_READONLY)

Set same_ym = New CJoinSameItem
same_ym.Prepare ds, "CL_ym"

Do Until ds.EOF
	cur_date = ds("CL_ym").Value

	row_count = same_ym.GetRowCount
	If row_count > 0 Then
		Writeln "<td align='right' rowspan=" & row_count & ">" & Left(cur_date, 4) & "年" & Mid(cur_date, 5) & "月</td>"
	End If
	Writeln "<td align='center'>" & ds("PR_pr_id").Value & "</td>"
	Writeln "<td align='center'>" & ds("PR_member_id").Value & "</td>"
	Writeln "<td>" & ds("PR_regist_adr").Value & "</td>"

	count1 = Number(ds("CL_count").Value)
	Writeln "<td align='right'>" & NumFormat(count1) & "</td>"

	count2 = Number(ds("KT_count").Value)
	Writeln "<td align='right'>" & NumFormat(count2) & "</td>"

	count3 = Number(ds("MM_count").Value)
	Writeln "<td align='right'>" & NumFormat(count3) & "</td>"

	If Not count1 = 0 Then
		Writeln "<td align='right'>" & FormatPercent(count2 / count1, 2) & "</td>"
		Writeln "<td align='right'>" & FormatPercent(count3 / count1, 2) & "</td>"
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
%>
</table>
</body>
</html>
