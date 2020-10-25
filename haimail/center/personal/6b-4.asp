<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:メンバー個人検索（送受信履歴）
'******************************************************

'=== メイン処理 ===
Dim member_id, sql, ds, sub1, sub2, sub3, sub4, sub5, sub6, sub7
Dim point_snd, point_enq, point_imp, point_man, point_etc, point_ttl, line

member_id = Request.QueryString("id")
If IsEmpty(member_id) Then
	SystemError "member_id is empty"
End If

'■タイトル設定
SetHeaderTitle "メンバー個人検索（送受信履歴）"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1(form) {
	if (form.job_id.value == "") {
		alert("ジョブIDを入力してください。");
		form.job_id.focus();
		return false;
	}
	return confirm("ポイント手動加算（ジョブID指定）を実行しますか？");
}
function OnSubmit_form2(form) {
	return confirm("ポイント手動加算（特別）を実行しますか？");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<%
point_snd = 0
point_enq = 0
point_imp = 0
point_man = 0
point_etc = 0
point_ttl = 0

sql = "select PT_point_type,PT_total_point from T_POINT_TOTAL where PT_member_id='" & member_id & "'"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
Do Until ds.EOF
	Select Case ds("PT_point_type").Value
	Case "10"
		point_snd = ds("PT_total_point").Value
	Case "11"
		point_enq = ds("PT_total_point").Value
	Case "12"
		point_imp = ds("PT_total_point").Value
	Case "13"
		point_man = ds("PT_total_point").Value
	Case Else
		point_etc = point_etc + ds("PT_total_point").Value
	End Select
	point_ttl = point_ttl + ds("PT_total_point").Value

	ds.MoveNext
Loop
%>
<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<tr>
		<td>
			<table border=1 cellpadding=0 cellspacing=1 class="small">
				<tr bgcolor="#E0FFE0">
					<th>送信加算ﾎﾟｲﾝﾄ</th>
					<th>ｱﾝｹｰﾄﾎﾟｲﾝﾄ</th>
					<th>感想文ﾎﾟｲﾝﾄ</th>
					<th>手動加算ﾎﾟｲﾝﾄ</th>
					<th>その他ﾎﾟｲﾝﾄ</th>
					<th>合計</th>
				</tr>
				<tr>
					<td align="right"><%=NumFormat(point_snd)%></td>
					<td align="right"><%=NumFormat(point_enq)%></td>
					<td align="right"><%=NumFormat(point_imp)%></td>
					<td align="right"><%=NumFormat(point_man)%></td>
					<td align="right"><%=NumFormat(point_etc)%></td>
					<td align="right"><%=NumFormat(point_ttl)%></td>
				</tr>
			</table>
		</td>
		<td align="right">
			<input type="button" value=" 戻る " onclick="location.href='6-3.asp?id=<%=member_id%>'">
		</td>
	</tr>
</table>
<br>
<table border=1 cellpadding=0 cellspacing=1 width="100%" class="small">
	<tr bgcolor="#E0FFE0">
		<th rowspan=2>ジョブID</th>
		<th rowspan=2>ジョブ名</th>
		<th rowspan=2>ﾒｰﾙ種類</th>
		<th colspan=3>予定加算ﾎﾟｲﾝﾄ</th>
		<th colspan=3>加算ﾎﾟｲﾝﾄ</th>
		<th rowspan=2>手動加算<br>ﾎﾟｲﾝﾄ</th>
		<th rowspan=2>返信ﾒｰﾙ受信日</th>
		<th rowspan=2>締切日</th>
	</tr>
	<tr bgcolor="#E0FFE0">
		<th>受信</th>
		<th>ｱﾝｹｰﾄ</th>
		<th>感想文</th>
		<th>受信</th>
		<th>ｱﾝｹｰﾄ</th>
		<th>感想文</th>
	</tr>
<%
sub1 = "select sum(PL_point) from T_POINT_LOG where PL_member_id='" & member_id & "' and PL_point_type='10' and PL_recv_mail=SM_mail_id"
sub2 = "select sum(PL_point) from T_POINT_LOG where PL_member_id='" & member_id & "' and PL_point_type='11' and PL_recv_mail=SM_mail_id"
sub3 = "select sum(PL_point) from T_POINT_LOG where PL_member_id='" & member_id & "' and PL_point_type='12' and PL_recv_mail=SM_mail_id"
sub4 = "select sum(PL_point) from T_POINT_LOG where PL_member_id='" & member_id & "' and PL_point_type='13' and PL_recv_mail=SM_mail_id"
sub5 = "select min(RM_seq_no) RM_seq_no_valid from T_RECV_MAIL" & vbCrLf & _
			 "where RM_member_id='" & member_id & "' and (RM_enq_valid='1' or RM_imp_valid='1') group by RM_mail_id"
sub6 = "select RM_mail_id,RM_recv_date from T_RECV_MAIL,(" & sub5  & ") where RM_seq_no=RM_seq_no_valid"
sub7 = "select JB_job_id,JB_job_name,JB_mail_type,JB_mail_id from T_JOB" & vbCrLf & _
			 "union all" & vbCrLf & _
			 "select ZH_job_id,ZH_job_name,'全配信',ZH_mail_id from T_ZENHAISHIN"
sql = "select JB_job_id,JB_job_name,JB_mail_type,SM_point_jushin,SM_point_enquete,SM_point_kansou," & vbCrLf & _
			" (" & sub1 & ") PL_point_jushin,(" & sub2 & ") PL_point_enquete,(" & sub3 & ") PL_point_kansou,(" & sub4 & ") PL_point_shudou,RM_recv_date,SM_limit_date" & vbCrLf & _
			"from T_SENDMAIL_RR,T_SEND_MAIL,(" & sub6 & "),(" & sub7 & ")" & vbCrLf & _
			"where SR_member_id='" & member_id & "' and SR_status='1' and SM_mail_id=SR_mail_id and JB_mail_id=SM_mail_id and SM_mail_id=RM_mail_id(+)" & vbCrLf & _
			"order by JB_job_id DESC"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td align='center'>" & ds("JB_job_id").Value & "</td>"
	Writeln "<td>" & ReplaceTag(ds("JB_job_name").Value) & "</td>"
	Writeln "<td align='center'>" & ds("JB_mail_type").Value & "</td>"
	Writeln "<td align='right'>" & NumFormat(ds("SM_point_jushin").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat(ds("SM_point_enquete").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat(ds("SM_point_kansou").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat(ds("PL_point_jushin").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat(ds("PL_point_enquete").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat(ds("PL_point_kansou").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat(ds("PL_point_shudou").Value) & "</td>"
	Writeln "<td align='center'>" & TimeFormat2(ds("RM_recv_date").Value) & "</td>"
	Writeln "<td align='center'>" & TimeFormat2(ds("SM_limit_date").Value) & "</td>"

	line = line + 1
	ds.MoveNext
Loop
%>
</table>
<br>
<table class="small">
	<tr>
		<form method="post" name="form1" action="6ba-4.asp" onsubmit="return OnSubmit_form1(this)">
		<input type="hidden" name="id" value="<%=member_id%>">
		<td>ポイント手動加算（ジョブID指定）</td>
		<td>ジョブID <input type="text" name="job_id" size=8></td>
		<td>加算ポイント <input type="text" name="point" size=5> <input type="submit" value="加算"></td>
		</form>
	</tr>
	<tr>
		<form method="post" name="form2" action="6ba-4.asp" onsubmit="return OnSubmit_form2(this)">
		<input type="hidden" name="id" value="<%=member_id%>">
		<td>ポイント手動加算（特別）</td>
		<td></td>
		<td>加算ポイント <input type="text" name="point" size=5> <input type="submit" value="加算"></td>
		</form>
	</tr>
</table>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
