<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:送信ログ管理
'******************************************************

'=== メイン処理 ===
Dim sub1, sql, ds, line, nline

SetHeaderTitle "送信ログ管理"

nline = Request("nline")
If IsEmpty(nline) Then
	nline = Session("sl_nline")
Else
	Session("sl_nline") = nline
End If
If Len(nline) > 0 Then
	nline = CInt(nline)
Else
	nline = 50
End If

sub1 = "SELECT SR_mail_id,MIN(SR_date) start_date,MAX(SR_date) end_date,COUNT(DECODE(SR_status,'1',1)) send_count,COUNT(DECODE(SR_status,'2',1)) err_count" & vbCrLf & _
			 "FROM T_SENDMAIL_RR" & vbCrLf & _
			 "GROUP BY SR_mail_id"
sql = "SELECT start_date,end_date,JB_job_id,JB_job_name,send_count,err_count,SM_mail_id" & vbCrLf & _
			"FROM T_JOB,T_SEND_MAIL,(" & sub1 & ")" & vbCrLf & _
			"WHERE (send_count<>0 OR err_count<>0) AND start_date IS NOT NULL AND SM_mail_id=SR_mail_id AND JB_mail_id=SM_mail_id" & vbCrLf & _
			"ORDER BY start_date DESC"
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
	<form method="get" id=form1 name=form1>
	<tr>
		<td>
			<nobr><font class="hc">表示行数</font><select name="nline" onchange="submit()"><%SelectDispLine(nline)%></select></nobr>
		</td>
		<td align="right">
			<input type="button" value=" 戻る " onclick="location.href='../menu.asp'">
		</td>
	</tr>
	</form>
</table>

<table border=1 cellpadding=0 cellspacing=1 width="100%">
	<tr bgcolor="#E0FFE0">
		<th>送信開始日時</th>
		<th>送信終了日時</th>
		<th>ジョブID</th>
		<th>ジョブ名</th>
		<th>送信数</th>
		<th>エラー数</th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td align='center'>" & TimeFormat(ds("start_date").Value) & "</td>"
	Writeln "<td align='center'>" & TimeFormat(ds("end_date").Value) & "</td>"
	Writeln "<td align='center'><a href='../project/cs/job_contents.asp?job_id=" & ds("JB_job_id").Value & "&back=" & Server.URLEncode("../sendlog/index.asp") & "' title='ジョブ登録内容を表示します'>" & ds("JB_job_id").Value & "</a></td>"
	Writeln "<td>" & ReplaceTag(ds("JB_job_name").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat(ds("send_count").Value) & "</td>"
	If ds("err_count").Value > 0 Then
		Writeln "<td align='right'><a href='send_err.asp?mail_id=" & ds("SM_mail_id").Value & "' title='送信エラー内容の表示および再送処理を行います'>" & NumFormat(ds("err_count").Value) & "</a></td>"
	Else
		Writeln "<td align='right'>" & NumFormat(ds("err_count").Value) & "</td>"
	End If
	Writeln "</tr>"

	ds.MoveNext
	line = line + 1
Loop
%>
</table>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
