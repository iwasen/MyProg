<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:全配信処理
'******************************************************

'=== メイン処理 ===
Dim sql, ds, line, nline, status, start_end, job_id, count

SetHeaderTitle "全配信JOB一覧"

nline = Request("nline")
If IsEmpty(nline) Then
	nline = Session("zh_nline")
Else
	Session("zh_nline") = nline
End If
If Len(nline) > 0 Then
	nline = CInt(nline)
Else
	nline = 50
End If

sql = "SELECT ZH_job_id,ZH_job_name,SM_limit_date,SM_point_enquete,SM_point_kansou,SM_start_date,SM_end_date,SM_status,SM_real_send,SM_enq_count,SM_imp_count" & vbCrLf & _
			"FROM T_SEND_MAIL,T_ZENHAISHIN" & vbCrLf & _
			"WHERE ZH_mail_id=SM_mail_id(+)" & vbCrLf & _
			"ORDER BY ZH_job_id DESC"
If nline > 0 Then
	sql = "SELECT * FROM (" & sql & ") WHERE ROWNUM<=" & nline
End If
Set ds = CreateDynaset(sql, ORADYN_READONLY)
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<form method="post">
	<tr>
		<td>件数：<%=ds.RecordCount%></td>
		<td>
			<font class="hc">表示行数</font><select name="nline" onchange="submit()"><%SelectDispLine(nline)%></select>
		</td>
		<td align="right"><input type="button" value="新規登録" onclick="location.href='zh_job.asp'"></td>
	</tr>
	</form>
</table>

<table <%=LIST_TABLE%> width="100%" class="small">
	<tr bgcolor="#E0FFE0">
		<th rowspan=2>JOBID</th>
		<th rowspan=2>JOB名</th>
		<th rowspan=2>送信期間</th>
		<th rowspan=2>締切り</th>
		<th colspan=2>加算ポイント</th>
		<th rowspan=2>発信数</th>
		<th colspan=2>返信数</th>
		<th rowspan=2>発信状態</th>
	</tr>
	<tr bgcolor="#E0FFE0">
		<th>ｱﾝｹｰﾄ</th>
		<th>感想文</th>
		<th>ｱﾝｹｰﾄ</th>
		<th>感想文</th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"

	job_id = ds("ZH_job_id").Value

	If IsNull(ds("SM_start_date").Value) Then
		start_end = "-"
	Else
		start_end = DateFormat2(ds("SM_start_date").Value) & "～" & DateFormat2(ds("SM_end_date").Value)
	End If

	If IsNull(ds("SM_status").Value) Then
		status = "-"
	Else
		Select Case ds("SM_status").Value
		Case "0"
			status = "未送信"
		Case "1"
			status = "未送信"
		Case "2"
			status = "送信中"
		Case "3"
			status = "送信ｴﾗｰ"
		Case "4"
			status = "送信待機"
		Case "9"
			status = "送信終了"
		End Select
	End If

	Writeln "<td align='center'><a href='zh_job.asp?id=" & job_id & "' title='ジョブ登録情報を表示・変更します'>" & job_id & "</a></td>"
	Writeln "<td><a href='zh_mail.asp?id=" & job_id & "' title='原稿＆発信条件を登録します'>" & ReplaceTag(ds("ZH_job_name").Value) & "</a></td>"
	Writeln "<td align='center'>" & start_end & "</td>"
	Writeln "<td align='center'>" & DateFormat2(ds("SM_limit_date").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat2(ds("SM_point_enquete").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat2(ds("SM_point_kansou").Value) & "</td>"

	count = ds("SM_real_send").Value
	If count > 0 Then
		Writeln "<td align='right'><a href='send_csv.asp?job_id=" & job_id & "' title='送信者情報をCSV出力します'>" & NumFormat(count) & "</a></td>"
	Else
		Writeln "<td align='right'>" & NumFormat(count) & "</td>"
	End If

	count = ds("SM_enq_count").Value
	If count > 0 Then
		Writeln "<td align='right'><a href='enquete.asp?job_id=" & job_id & "' title='アンケートレスポンスを表示します'>" & NumFormat(count) & "</a></td>"
	Else
		Writeln "<td align='right'>" & NumFormat(count) & "</td>"
	End If

	count = ds("SM_imp_count").Value
	If count > 0 Then
		Writeln "<td align='right'><a href='impression.asp?job_id=" & job_id & "' title='感想文レスポンスを表示します'>" & NumFormat(count) & "</a></td>"
	Else
		Writeln "<td align='right'>" & NumFormat(count) & "</td>"
	End If

	Writeln "<td align='center'>" & status & "</td>"
	Writeln "</tr>"
	ds.MoveNext

	line = line + 1
	If nline <> 0 And line > nline Then
		Exit Do
	End If
Loop
%>
</table>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
