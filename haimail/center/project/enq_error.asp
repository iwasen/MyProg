<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:アンケートエラーメール一覧表示処理
'******************************************************

Const ERR_NORMAL = "0"
Const ERR_NOANSWER = "1"
Const ERR_INVALID = "2"
Const ERR_INSUFF = "3"

'=== エラー種別表示 ===
Private Function ErrorKind(error_code)
	Select Case error_code
	Case ERR_NOANSWER
		ErrorKind = "無回答"
	Case ERR_INVALID
		ErrorKind = "不正回答"
	Case ERR_INSUFF
		ErrorKind = "回答不足"
	End Select
End Function

Dim sql, ds, line, from

SetHeaderTitle "アンケートエラーメール一覧"

sql = "SELECT JB_job_id,JB_job_name,MM_member_id,MM_mail_adr,RM_recv_date,EE_error_code,EE_seq_no" & vbCrLf & _
			"FROM T_JOB,T_MEMBER,T_RECV_MAIL,T_ENQUETE_ERR" & vbCrLf & _
			"WHERE EE_status='0' AND RM_seq_no=EE_seq_no AND JB_mail_id=RM_mail_id AND MM_member_id=RM_member_id" & vbCrLf & _
			"ORDER BY EE_seq_no"
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
	<tr>
		<td>件数：<%=ds.RecordCount%></td>
		<td align="right">
			<input type="button" value=" 戻る " onclick="location.href='index.asp'">
		</td>
	</tr>
</table>

<table <%=LIST_TABLE%> width="100%" class="small">
	<tr bgcolor="#E0FFE0">
		<th>メンバーID</th>
		<th>メールアドレス</th>
		<th>ジョブID</th>
		<th>ジョブ名</th>
		<th>最新受信日</th>
		<th>エラー種別</th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td align='center'>" & ds("MM_member_id").Value & "</td>"
	Writeln "<td><a href='enq_error2.asp?seq_no=" & ds("EE_seq_no").Value & "' title='アンケートエラーメール処理を行います'>" & ReplaceTag(ds("MM_mail_adr").Value) & "</a></td>"
	Writeln "<td align='center'>" & ds("JB_job_id").Value & "</td>"
	Writeln "<td>" & ReplaceTag(ds("JB_job_name").Value) & "</td>"
	Writeln "<td align='center'>" & TimeFormat(ds("RM_recv_date").Value) & "</td>"
	Writeln "<td align='center'>" & ErrorKind(ds("EE_error_code").Value) & "</td>"
	Writeln "</tr>"
	ds.MoveNext
	line = line + 1
Loop
%>
</table>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
