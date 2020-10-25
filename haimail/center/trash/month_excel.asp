<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:プロジェクト進行管理画面
'******************************************************

'=== メイン処理 ===
Dim sub1, sub2, sub3, sub4,sql, ds, line, nline, dairiten_id, company_id, tantousha_id, count_tbl(), count, n, project_id, where
Dim s_year, s_month, s_ym, SR_count, RM_enq_count, CC_count, RM_imp_count
Dim SR_total, RM_enq_total, CC_total, RM_imp_total
Dim real_click, real_enquete, real_kansou, point_total
s_year = Request.Form("s_year")
s_month = Request.Form("s_month")

s_ym = s_year & Right("0" & s_month, 2)


SetHeaderTitle "月別トータル"

sub1 = "SELECT SR_mail_id,COUNT(*) AS SR_count FROM T_SENDMAIL_RR WHERE SR_status='1' GROUP BY SR_mail_id"

sub2 = "SELECT RM_mail_id,COUNT(RM_enq_valid) AS RM_enq_count,COUNT(RM_imp_valid) AS RM_imp_count FROM T_RECV_MAIL GROUP BY RM_mail_id"

sub3 = "SELECT CC_job_id,COUNT(*) AS CC_count FROM T_MAIL_CLICK,T_CLICK_COUNT WHERE MC_ct_id=CC_ct_id GROUP BY CC_job_id"

sub4 = "SELECT DA_A.DA_dairiten_id DA_dairiten_id,DA_A.DA_name DA_Aname,DA_A.DA_class DA_class,DA_A.DA_rep_id DA_rep_id,DA_B.DA_name DA_Bname FROM T_DAIRITEN DA_A,T_DAIRITEN DA_B WHERE DA_A.DA_rep_id=DA_B.DA_dairiten_id(+)"

sql = "SELECT PJ_project_id,PJ_project_name,JB_job_id,DA_Aname,DA_class,DA_Bname,CM_name,TS_name1,JB_job_name,JB_mail_type,SM_point_jushin,SM_point_enquete,SM_point_kansou,SM_status,SR_count,RM_enq_count,RM_imp_count,CC_count" & vbCrLf & _
			"FROM (" & sub1 & "),(" & sub2 & "),(" & sub3 & "),(" & sub4 & "),T_COMPANY,T_TANTOUSHA,T_SEND_MAIL,T_JOB,T_PROJECT" & vbCrLf & _
			"WHERE TO_CHAR(SM_start_date,'YYYYMM') = '" & s_ym & "' AND JB_mail_id=SM_mail_id(+) AND PJ_project_id=JB_project_id(+)" & vbCrLf & _
			"AND JB_mail_id=SR_mail_id(+) AND JB_mail_id=RM_mail_id(+) AND JB_job_id=CC_job_id(+)" & vbCrLf & _
			"AND PJ_tantousha_id=TS_tantousha_id AND TS_company_id=CM_company_id　AND (SR_count IS NOT NULL)" & vbCrLf & _
			"AND CM_dairiten_id=DA_dairiten_id" & vbCrLf & _
			"ORDER BY PJ_project_id,JB_job_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)

%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../../inc/header.asp"-->

<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<form method="post" name="form1">
	<tr>
		<td>件数：<%=ds.RecordCount%></td>
		<td align="right"><input type="button" value=" 戻る " onclick="location.href='index.asp'" id=button1 name=button1></td>
	</tr>
	<tr>
		<td>■<%=s_year%>年<%=s_month%>月</td>
	</tr>
	</form>
</table>

<table border=1 cellpadding=0 cellspacing=1 width="100%" class="small">
  <tr bgcolor="#E0FFE0">
		<th rowspan=2>SEQ</th>
		<th rowspan=2>ﾌﾟﾛｼﾞｪｸﾄ名</th>
		<th rowspan=2>代理店A名</th>
		<th rowspan=2>代理店B名</th>
		<th rowspan=2>企業名</th>
		<th rowspan=2>担当者名</th>
		<th rowspan=2>ｼﾞｮﾌﾞ名</th>
		<th rowspan=2>種類</th>
    <th rowspan=2>発信数</th>
		<th colspan=2>アンケート</th>
		<th colspan=2>クリック</th>
		<th colspan=2>感想文</th>
    <th rowspan=2>ポイント</th>
		<th rowspan=2>売上</th>
	</tr>
  <tr bgcolor="#E0FFE0">
		<th>返信数</th>
		<th>返信率</th>
		<th>ｸﾘｯｸ数</th>
		<th>ｽﾘｯｸ率</th>
		<th>返信数</th>
		<th>返信率</th>
	</tr>
<%
If Not ds.EOF Then
  Redim count_tbl(ds.RecordCount)
  project_id = ds("PJ_project_id").Value
  count = 0
  n = 0
  Do Until ds.EOF
    If project_id = ds("PJ_project_id").Value Then
      count = count + 1
    Else
      count_tbl(n) = count
      n = n + 1
      count = 1
      project_id = ds("PJ_project_id").Value
    End If
    ds.MoveNext
  Loop
  count_tbl(n) = count
  ds.MoveFirst
End If

n = 0: count = 0: line = 1
SR_total = 0:	RM_enq_total = 0:	CC_total = 0: RM_imp_total = 0
real_click = 0: real_enquete = 0: real_kansou = 0: point_total = 0
Do Until ds.EOF
	SR_count = ds("SR_count").Value
	RM_enq_count = ds("RM_enq_count").Value
	CC_count = ds("CC_count").Value
	RM_imp_count = ds("RM_imp_count").Value
  Writeln "<tr class='tc" & line Mod 2 & "'>"
		If count = 0 Then
		  count = count_tbl(n)
		  n = n + 1
		  Writeln "<td rowspan=" & count & " class='tc2' align='center'>" & ds("PJ_project_id").Value & "</td>"
		  Writeln "<td rowspan=" & count & " class='tc2'>" & ReplaceTag(ds("PJ_project_name").Value) & "</td>"
			If ds("DA_class").Value = "A" Then
			  Writeln "<td rowspan=" & count & " class='tc2'>" & ReplaceTag(ds("DA_Aname").Value) & "</td>"
			  Writeln "<td rowspan=" & count & " class='tc2'><br></td>"
		  Else
			  Writeln "<td rowspan=" & count & " class='tc2'>" & BlankToBR(ReplaceTag(ds("DA_Bname").Value)) & "</td>"
			  Writeln "<td rowspan=" & count & " class='tc2'>" & BlankToBR(ReplaceTag(ds("DA_Aname").Value)) & "</td>"
			End If
		  Writeln "<td rowspan=" & count & " class='tc2'>" & ReplaceTag(ds("CM_name").Value) & "</td>"
		  Writeln "<td rowspan=" & count & " class='tc2'>" & ReplaceTag(ds("TS_name1").Value) & "</td>"
		End If
		count = count - 1

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
		  Writeln "<td><br></td>"
		Else

		  Writeln "<td>" & ReplaceTag(ds("JB_job_name").Value) & "</td>"
		  Writeln "<td align='center'>" & ds("JB_mail_type").Value & "</td>"
		  Writeln "<td align='right'>" & NumFormat(SR_count) & "</td>"
		  Writeln "<td align='right'>" & NumFormat(RM_enq_count) & "</td>"
		  Writeln "<td align='right'>" & FormatPercent(Number(RM_enq_count) / SR_count, 1, -1, 0, -1) & "<br></td>"
		  Writeln "<td align='right'>" & NumFormat(CC_count) & "</td>"
		  Writeln "<td align='right'>" & FormatPercent(Number(CC_count) / SR_count, 1, -1, 0, -1) & "</td>"
		  Writeln "<td align='right'>" & NumFormat(RM_imp_count) & "</td>"
		  Writeln "<td align='right'>" & FormatPercent(Number(RM_imp_count) / SR_count, 1, -1, 0, -1) & "</td>"
		  Writeln "<td align='right'>" & NumFormat((Number(ds("SM_point_jushin").Value) * SR_count) + (Number(ds("SM_point_enquete").Value) * Number(ds("RM_enq_count").Value)) + (Number(ds("SM_point_kansou").Value) * Number(ds("RM_imp_count").Value))) & "</td>"
		  Writeln "<td align='right'><br></td>"

			If RM_enq_count > 0 Then
				real_enquete = real_enquete + Number(SR_count)
			End If
			If CC_count > 0 Then
				real_click = real_click + Number(SR_count)
			End If
			If CC_count > 0 Then
				real_kansou = real_kansou + Number(SR_count)
			End If				
			SR_total = SR_total + Number(SR_count)
			RM_enq_total = RM_enq_total + Number(RM_enq_count)
			CC_total = CC_total + Number(CC_count)
			RM_imp_total = RM_imp_total + Number(RM_imp_count)

			point_total = point_total + (Number(ds("SM_point_jushin").Value) * SR_count) + (Number(ds("SM_point_enquete").Value) * Number(ds("RM_enq_count").Value)) + (Number(ds("SM_point_kansou").Value) * Number(ds("RM_imp_count").Value))
		Writeln "</tr>"
	End If
	ds.MoveNext
  line = line + 1
Loop
If Not SR_total = 0 Then
	Writeln "<td align='center'>計</td>"
	Writeln "<td><br></td>"
	Writeln "<td><br></td>"
	Writeln "<td><br></td>"
	Writeln "<td><br></td>"
	Writeln "<td><br></td>"
	Writeln "<td><br></td>"
	Writeln "<td><br></td>"
	Writeln "<td align='right'>" & NumFormat(SR_total) & "</td>"
	Writeln "<td align='right'>" & NumFormat(RM_enq_total) & "</td>"
	Writeln "<td align='right'>" & FormatPercent(Number(RM_enq_total) / real_enquete, 1, -1, 0, -1) & "</td>"
	Writeln "<td align='right'>" & NumFormat(CC_total) & "</td>"
	Writeln "<td align='right'>" & FormatPercent(Number(CC_total) / real_click, 1, -1, 0, -1) & "</td>"
	Writeln "<td align='right'>" & NumFormat(RM_imp_total) & "</td>"
	Writeln "<td align='right'>" & FormatPercent(Number(RM_imp_total) / real_kansou, 1, -1, 0, -1) & "</td>"
	Writeln "<td align='right'>" & NumFormat(point_total) & "</td>"
	Writeln "<td><br></td>"
	Writeln "</tr>"
End If
%>
</table>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
