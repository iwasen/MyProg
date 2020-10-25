<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:ビジネス配信処理
'******************************************************

'=== メイン処理 ===
Dim sql, ds, line, nline, status, start_end

SetHeaderTitle "ビジネス配信JOB一覧"

nline = Request("nline")
If IsEmpty(nline) Then
	nline = Session("BH_nline")
Else
	Session("BH_nline") = nline
End If
If Len(nline) > 0 Then
	nline = CInt(nline)
Else
	nline = 50
End If

sql = "SELECT BH_job_id,BH_job_name,SM_limit_date,SM_point_enquete,SM_point_kansou,SM_start_date,SM_end_date,SM_status,SM_real_send" & vbCrLf & _
			"FROM T_BSHAISHIN,T_SEND_MAIL" & vbCrLf & _
			"WHERE BH_mail_id=SM_mail_id(+)" & vbCrLf & _
			"ORDER BY BH_job_id DESC"
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
		<td align="right"><input type="button" value="新規登録" onclick="location.href='bh_job.asp'"></td>
	</tr>
	</form>
</table>

<table <%=LIST_TABLE%> width="100%" class="small">
	<tr bgcolor="#E0FFE0">
		<th>JOBID</th>
		<th>JOB名</th>
		<th>送信期間</th>
		<th>締切り</th>
		<th>発信数</th>
		<th>発信状態</th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"

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

	Writeln "<td align='center'><a href='BH_job.asp?id=" & ds("BH_job_id").Value & "' title='ジョブ登録情報を表示・変更します'>" & ds("BH_job_id").Value & "</a></td>"
	Writeln "<td><a href='BH_mail.asp?id=" & ds("BH_job_id").Value & "' title='原稿＆発信条件を登録します'>" & ReplaceTag(ds("BH_job_name").Value) & "</a></td>"
	Writeln "<td align='center'>" & start_end & "</td>"
	Writeln "<td align='center'>" & DateFormat2(ds("SM_limit_date").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat(ds("SM_real_send").Value) & "</td>"
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
