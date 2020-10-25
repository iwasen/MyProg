<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:クレーム管理／一覧表示
'******************************************************

'=== 処理状態表示 ===
Private Function StatusText(status)
	Select Case status
	Case "0"
		StatusText = "未処理"
	Case "1"
		StatusText = "メール送信"
	Case "2"
		StatusText = "問題なし"
	Case "3"
		StatusText = "破棄"
	End Select
End Function

'=== メイン処理 ===
Dim ds, sql, line, cond, st, status(3), nline, i

SetHeaderTitle "クレームメール一覧"
Response.CacheControl = "no-cache"

If Not IsEmpty(Request.QueryString("st")) Then
	For Each st In Request.QueryString("st")
		status(CInt(st)) = "1"
	Next
End If

nline = Request.QueryString("nline")
If IsEmpty(nline) Then
	nline = Session("claim_nline")
Else
	Session("claim_nline") = nline
End If
If Len(nline) > 0 Then
	nline = CInt(nline)
Else
	nline = 50
End If

cond = ""
For i = 0 To 3
	If status(i) = "1" Then
		cond = cond & "'" & i & "',"
	End If
Next
cond = "CA_status IN (" & cond & "' ')"
SQL = "SELECT JB_job_id,JB_job_name,RM_member_id,RM_subject,RM_recv_date,RM_date,RM_seq_no,CA_status" & vbCrLf & _
			"FROM T_JOB,T_RECV_MAIL,T_CLAIM_MAIL" & vbCrLf & _
			"WHERE " & cond & " AND RM_seq_no=CA_seq_no AND JB_mail_id=RM_mail_id" & vbCrLf & _
			"ORDER BY CA_seq_no DESC"
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

<center>
<table align="center" width="100%">
	<form method="get" name="form1">
	<tr>
		<td>
			<nobr><font class="hc"><input type="checkbox" name="st" value="0" <%=Checked(status(0))%> onclick="submit()">未処理</font></nobr>
			<nobr><font class="hc"><input type="checkbox" name="st" value="1" <%=Checked(status(1))%> onclick="submit()">ﾒｰﾙ送信</font></nobr>
			<nobr><font class="hc"><input type="checkbox" name="st" value="2" <%=Checked(status(2))%> onclick="submit()">問題なし</font></nobr>
			<nobr><font class="hc"><input type="checkbox" name="st" value="3" <%=Checked(status(3))%> onclick="submit()">破棄</font></nobr>
			<nobr><font class="hc">表示行数</font><select name="nline" onchange="submit()"><%SelectDispLine(nline)%></select></nobr>
		</td>
		<td align="right">
			<input type="button" value=" 戻る " onclick="location.href='index.asp'">
		</td>
	</tr>
	</form>
</table>
<table <%=LIST_TABLE%> width="100%" align="center" class="small">
	<tr bgcolor="#E0FFE0">
		<th><nobr>ジョブID</nobr></th>
		<th><nobr>ジョブ名</nobr></th>
		<th><nobr>メンバーID</nobr></th>
		<th><nobr>件名</nobr></th>
		<th><nobr>送信日時</nobr></th>
		<th><nobr>受信日時</nobr></th>
		<th><nobr>状態</nobr></th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td align='center'><a href='../project/cs/job_contents.asp?job_id=" & ds("JB_job_id").Value & "&back=" & Server.URLEncode("../claim/list.asp?" & Request.QueryString.Item) & "' title='ジョブ登録内容を表示します'>" & ds("JB_job_id").Value & "</a></td>"
	Writeln "<td>" & ReplaceTag(ds("JB_job_name").Value) & "</td>"
	Writeln "<td align='center'>" & BlankToBR(ds("RM_member_id").Value) & "</td>"
	Writeln "<td>" & ReplaceTag(ds("RM_subject").Value) & "</td>"
	Writeln "<td align='center'>" & TimeFormat(ds("RM_recv_date").Value) & "</td>"
	Writeln "<td align='center'>" & TimeFormat(ds("RM_date").Value) & "</td>"
	Writeln "<td align='center'><nobr><a href='claim_info.asp?seq=" & ds("RM_seq_no").Value & "' title='クレーム対応処理を行います'>" & StatusText(ds("CA_status").Value) & "</a></nobr></td>"
	Writeln "</tr>"
	ds.MoveNext
	line = line + 1
Loop
%>
</table>
<!--#INCLUDE FILE="../inc/footer.asp"-->
</center>
</body>
</html>
