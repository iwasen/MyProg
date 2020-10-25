<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<!--#INCLUDE FILE="../../inc/mail_check.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:感想文ログ画面処理
'******************************************************

'=== メイン処理 ===
Dim job_id, sql, ds, body, ary, s, flag, hr, limit

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

If CheckPermission(PM_IMP_LOG) Then
	limit = ""
ElseIf CheckPermission(PM_IMP_10LOG) Then
	limit = "ROWNUM<=10 AND "
Else
	NoPermission
End If

SetHeaderTitle "感想文返信ログ"

sql = "SELECT RM_body" & vbCrLf & _
			"FROM T_RECV_MAIL,T_JOB" & vbCrLf & _
			"WHERE " & limit & "JB_job_id=" & job_id & " AND RM_imp_valid='1' AND JB_mail_id=RM_mail_id" & vbCrLf & _
			"ORDER BY RM_seq_no"

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

<center>
<table border=1 cellspacing=0 cellpadding=3 bordercolor="navy" width="100%">
	<tr>
		<td>
			<font class="np">
<%
hr = False
Do Until ds.EOF
	If hr Then
		Writeln "<hr color='red'>"
	Else
		hr = True
	End If

	body = ReadCLOB(ds(0).Value)
	ary = Split(body, vbCrLf)

	flag = True
	For Each s In ary
		If SeparatorCheck(s, IMP_START) Or InStr(s, IMP_SEIRI) > 0 Then
			flag = False
			Exit For
		End If
	Next

	For Each s In ary
		If flag = True Then
			If SeparatorCheck(s, IMP_END) Then
				flag = False
			End If
		End If

		If flag Then
			Writeln ReplaceTag(DeleteQuote1(s)) & "<br>"
		End If

		If flag = False Then
			If SeparatorCheck(s, IMP_START) Or InStr(s, IMP_SEIRI) > 0 Then
				flag = True
			End If
		Else
			If SeparatorCheck(s, ENQ_START) Or InStr(s, ENQ_SEIRI) > 0 Then
				flag = False
			End If
		End If
	Next

	ds.MoveNext
Loop
%>
			</font>
		</td>
	</tr>
</table>
<br>
<input type="button" value=" 戻る " onclick="history.back()">
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
