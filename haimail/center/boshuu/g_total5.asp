<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�����o�[��W�Ǘ�
'******************************************************

'=== �L���^�C�v�I���� ===
Private Function GetKoukokuTypeName(koukoku_id)
	Dim sql, ds

	If koukoku_id <> "" Then
		sql = "SELECT KT_name FROM T_KOUKOKU_TYPE WHERE KT_koukoku_id='" & koukoku_id & "'"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds.EOF Then
			GetKoukokuTypeName = ds(0).Value
		End If
	Else
		GetKoukokuTypeName = "�S��"
	End If
End Function

'=== ���C������ ===
SetHeaderTitle "�T�D���ʍL���^�C�v��"
Response.ContentType = "application/vnd.ms-excel"

Dim sql, ds, ds1, ds2, ds3, i, click_total, kari_total, hon_total, koukoku_id, where
Dim s_year, s_month, e_year, e_month
Dim start_ym, end_ym, start_date, end_date
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
<form id=form1 name=form1>
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
koukoku_id = Request.Form("koukoku_id")

start_ym = s_year & Right("0" & s_month, 2)
end_ym = e_year & Right("0" & e_month, 2)

If koukoku_id <> "" Then
	where = " AND PR_koukoku_id='" & koukoku_id & "'"
Else
	where = ""
End If

sql = "SELECT TO_CHAR(CL_date, 'YYYYMM') AS CL_month,COUNT(*) AS CL_count FROM T_CLICK_LOG, T_PR" & vbCrLf & _
			"WHERE PR_baitai_id<>'03' AND PR_pr_id=CL_pr_id(+)" & where & " AND TO_CHAR(CL_date,'YYYYMM') BETWEEN '" & start_ym & "' AND '" & end_ym & "'" & vbCrLf & _
			"GROUP BY TO_CHAR(CL_date, 'YYYYMM')" & vbCrLf & _
			"ORDER BY CL_month"
Set ds1 = CreateDynaset(sql, ORADYN_READONLY)

sql = "SELECT TO_CHAR(KT_regist_date, 'YYYYMM') AS KT_month,COUNT(*) AS KT_count FROM T_KARI_TOUROKU, T_PR" & vbCrLf & _
			"WHERE PR_baitai_id<>'03' AND PR_pr_id=KT_pr_id(+)" & where & " AND TO_CHAR(KT_regist_date,'YYYYMM') BETWEEN '" & start_ym & "' AND '" & end_ym & "'" & vbCrLf & _
			"GROUP BY TO_CHAR(KT_regist_date, 'YYYYMM')" & vbCrLf & _
			"ORDER BY KT_month"
Set ds2 = CreateDynaset(sql, ORADYN_READONLY)

sql = "SELECT TO_CHAR(MM_regist_date, 'YYYYMM') AS MM_month,COUNT(*) AS MM_count FROM T_MEMBER, T_PR" & vbCrLf & _
			"WHERE PR_baitai_id<>'03' AND PR_pr_id=MM_pr_id(+)" & where & " AND TO_CHAR(MM_regist_date,'YYYYMM') BETWEEN '" & start_ym & "' AND '" & end_ym & "'" & vbCrLf & _
			"GROUP BY TO_CHAR(MM_regist_date, 'YYYYMM')" & vbCrLf & _
			"ORDER BY MM_month"
Set ds3 = CreateDynaset(sql, ORADYN_READONLY)
%>
<table border=1 cellpadding=1 cellspacing=0 width="100%" class="small">
	�L���^�C�v�� <%=GetKoukokuTypeName(koukoku_id)%>
	<tr bgcolor="#E0FFE0">
		<th></th>
		<th>�د���</th>
		<th>���o�^</th>
		<th>�o�^��</th>
		<th>CVR��</th>
		<th>CVR�{</th>
	</tr>
<%


start_date = CDate(s_year & "/" & s_month & "/01")
end_date = CDate(e_year & "/" & e_month & "/01")

cur_date = start_date
Do While cur_date <= end_date

	cur_ym = Year(cur_date) & Right("0" & Month(cur_date), 2)

	Writeln "<td align='right'>" & Year(cur_date) & "�N" & Month(cur_date) & "��</td>"

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

Writeln "<td align='center'>�v</td>"
Writeln "<td align='right'>" & NumFormat(t_count1) & "</td>"
Writeln "<td align='right'>" & NumFormat(t_count2) & "</td>"
Writeln "<td align='right'>" & NumFormat(t_count3) & "</td>"

If Not t_count3 = 0 Then
	Writeln "<td align='right'>" & FormatPercent(t_count2/t_count1,2) & "</td>"
	Writeln "<td  align='right'>" & FormatPercent(t_count3/t_count1,2) & "</td>"
Else
	Writeln "<td align='right'>-</td>"
	Writeln "<td align='right'>-</td>"
End If
Writeln "</tr>"
%>
</table>
</body>
</html>
