<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:プロジェクト進行管理画面
'******************************************************

'=== PercentForm_0 ===
Function PercentForm_0(elem,denom)
	If denom =< 0 Or elem = 0 Or IsNull(elem) Then
		PercentForm_0 = "0.0%"
	Else
		PercentForm_0 = FormatPercent(Number(elem) / Number(denom), 1, -1, 0, -1)
	End If
End Function

'=== メイン処理 ===
Dim sub1, sub2, sql, ds, line, n
Dim s_year, s_month, s_ym, SR_count, RM_enq_count, CC_count, RM_imp_count, mail_type
Dim SR_total, RM_enq_total, CC_total, RM_imp_total
Dim real_click, real_enquete, real_kansou, PT_project_total, PT_kansou_total,PJ_total

s_year = Request.Form("s_year")
s_month = Request.Form("s_month")

s_ym = s_year & Right("0" & s_month, 2)

SetHeaderTitle "種別月別トータル"

sub1 = "SELECT RM_mail_id,COUNT(RM_enq_valid) AS RM_enq_count,COUNT(RM_imp_valid) AS RM_imp_count FROM T_RECV_MAIL GROUP BY RM_mail_id"
sub2 = "SELECT CC_job_id,COUNT(*) AS CC_count FROM T_MAIL_CLICK,T_CLICK_COUNT WHERE MC_ct_id=CC_ct_id GROUP BY CC_job_id"
sql = "SELECT DECODE(JB_mail_type,'LT','1手紙','HG','2葉書','CR','3チラシ','TG','4電報','OH','5往復葉書') JB_mail_type,COUNT(DISTINCT PJ_project_id) PJ_count,SUM(SM_real_send) SR_count,SUM(RM_enq_count) RM_enq_count,SUM(CC_count) CC_count,SUM(RM_imp_count) RM_imp_count,SUM((NVL(RM_enq_count,0)*NVL(SM_point_enquete,0))+(NVL(SM_real_send,0)*NVL(SM_point_jushin,0))) PT_project,SUM(NVL(RM_imp_count,0)*NVL(SM_point_kansou,0)) PT_kansou" & vbCrLf & _
			"FROM (" & sub1 & "),(" & sub2 & "),T_SEND_MAIL,T_JOB,T_PROJECT" & vbCrLf & _
			"WHERE TO_CHAR(SM_start_date,'YYYYMM') = '" & s_ym & "' AND PJ_project_id=JB_project_id(+) AND JB_mail_id=SM_mail_id(+)" & vbCrLf & _
			"AND JB_mail_id=RM_mail_id(+) AND JB_job_id=CC_job_id(+) AND (SM_real_send IS NOT NULL)" & vbCrLf & _
			"GROUP BY DECODE(JB_mail_type,'LT','1手紙','HG','2葉書','CR','3チラシ','TG','4電報','OH','5往復葉書')" & vbCrLf & _
			"ORDER BY JB_mail_type"
Set ds = CreateDynaset(sql, ORADYN_READONLY)

%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../../inc/header.asp"-->

<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<form method="post" name="form1">
	<tr>
		<td>■<%=s_year%>年<%=s_month%>月</td>
		<td align="right"><input type="button" value=" 戻る " onclick="location.href='index.asp'"></td>
	</tr>
	</form>
</table>
<table border=1 cellpadding=0 cellspacing=1 width="100%" class="small">
	<tr bgcolor="#E0FFE0">
		<th rowspan=2>種別</th>
		<th rowspan=2>ﾌﾟﾛｼﾞｪｸﾄ数</th>
		<th rowspan=2>発信数</th>
		<th colspan=2>アンケート</th>
		<th colspan=2>クリック</th>
		<th colspan=2>感想文</th>
		<th colspan=2>ポイント</th>
		<th rowspan=2>売上</th>
	</tr>
	<tr bgcolor="#E0FFE0">
		<th>返信数</th>
		<th>返信率</th>
		<th>ｸﾘｯｸ数</th>
		<th>ｸﾘｯｸ率</th>
		<th>返信数</th>
		<th>返信率</th>
		<th>ﾌﾟﾛｼﾞｪｸﾄ</th>
		<th>感想文</th>
	</tr>
<%
If Not ds.EOF Then
	line = 1
	SR_total = 0:	RM_enq_total = 0:	CC_total = 0: RM_imp_total = 0
	real_click = 0: real_enquete = 0: real_kansou = 0: PJ_total = 0
	For n = 1 To 5
		SR_count = ds("SR_count").Value
		RM_enq_count = ds("RM_enq_count").Value
		CC_count = ds("CC_count").Value
		RM_imp_count = ds("RM_imp_count").Value
		Writeln "<tr class='tc" & line Mod 2 & "'>"

		If Number(Mid(ds("JB_mail_type").Value,1,1)) = n Then
			mail_type = Mid(ds("JB_mail_type").Value,2)
			Writeln "<td align='left'>" & mail_type & "</td>"
			Writeln "<td align='right'>" & NumFormat(ds("PJ_count").Value) & "</td>"
			Writeln "<td align='right'>" & NumFormat(SR_count) & "</td>"
			Writeln "<td align='right'>" & NumFormat(RM_enq_count) & "</td>"
			Writeln "<td align='right'>" & PercentForm_0(RM_enq_count, SR_count) & "</td>"
			Writeln "<td align='right'>" & NumFormat(CC_count) & "</td>"
			Writeln "<td align='right'>" & PercentForm_0(CC_count, SR_count) & "</td>"
			Writeln "<td align='right'>" & NumFormat(RM_imp_count) & "<br></td>"
			Writeln "<td align='right'>" & PercentForm_0(RM_imp_count, SR_count) & "</td>"
			Writeln "<td align='right'>" & NumFormat(ds("PT_project").Value) & "</td>"
			Writeln "<td align='right'>" & NumFormat(ds("PT_kansou").Value) & "</td>"
			Writeln "<td align='right'><br></td>"
			If RM_enq_count > 0 Then
				real_enquete = real_enquete + Number(SR_count)
			End If
			If CC_count > 0 Then
				real_click = real_click + Number(SR_count)
			End If
			If RM_imp_count > 0 Then
				real_kansou = real_kansou + Number(SR_count)
			End If
			PJ_total = PJ_total + NumFormat(ds("PJ_count").Value)
			SR_total = SR_total + Number(SR_count)
			CC_total = CC_total + Number(CC_count)
			RM_enq_total = RM_enq_total + Number(RM_enq_count)
			RM_imp_total = RM_imp_total + Number(RM_imp_count)
			PT_project_total = PT_project_total + Number(ds("PT_project").Value)
			PT_kansou_total = PT_kansou_total + Number(ds("PT_kansou").Value)
			ds.MoveNext
		Else
			SELECT CASE n
				CASE 1
					mail_type = "手紙"
				CASE 2
					mail_type = "葉書"
				CASE 3
					mail_type = "チラシ"
				CASE 4
					mail_type = "電報"
				CASE 5
					mail_type = "往復葉書"
			End SELECT
			Writeln "<td align='left'>" & mail_type & "</td>"
			Writeln "<td align='right'>0</td>"
			Writeln "<td align='right'>0</td>"
			Writeln "<td align='right'>0</td>"
			Writeln "<td align='right'>0.0%</td>"
			Writeln "<td align='right'>0</td>"
			Writeln "<td align='right'>0.0%</td>"
			Writeln "<td align='right'>0</td>"
			Writeln "<td align='right'>0.0%</td>"
			Writeln "<td align='right'>0</td>"
			Writeln "<td align='right'>0</td>"
			Writeln "<td align='right'><br></td>"
		End If
		Writeln "</tr>"
		line = line + 1
	Next
	Writeln "<td align='center'>計</td>"
	Writeln "<td align='right'>" & NumFormat(PJ_total) & "</td>"
	Writeln "<td align='right'>" & NumFormat(SR_total) & "</td>"
	Writeln "<td align='right'>" & NumFormat(RM_enq_total) & "</td>"
	Writeln "<td align='right'>" & PercentForm_0(RM_enq_total,real_enquete) & "</td>"
	Writeln "<td align='right'>" & NumFormat(CC_total) & "</td>"
	Writeln "<td align='right'>" & PercentForm_0(CC_total, real_click) & "</td>"
	Writeln "<td align='right'>" & NumFormat(RM_imp_total) & "</td>"
	Writeln "<td align='right'>" & PercentForm_0(RM_imp_total, real_kansou) & "</td>"
	Writeln "<td align='right'>" & NumFormat(PT_project_total) & "</td>"
	Writeln "<td align='right'>" & NumFormat(PT_kansou_total) & "</td>"
	Writeln "<td><br></td>"
	Writeln "</tr>"
End If
%>
</table>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
