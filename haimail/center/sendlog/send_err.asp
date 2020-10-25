<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:送信ログ管理／送信エラー表示
'******************************************************

'=== メイン処理 ===
Dim sql, ds, line, mail_id

mail_id = Request.QueryString("mail_id")
If IsEmpty(mail_id) Then
	SystemError "empty mail_id"
End If

SetHeaderTitle "送信エラー表示"

sql = "SELECT /*+ INDEX(T_SENDMAIL_RR) */ SR_member_id,SR_date,SR_send_error" & vbCrLf & _
			"FROM T_SENDMAIL_RR" & vbCrLf & _
			"WHERE SR_mail_id='" & mail_id & "' AND SR_status='2'" & vbCrLf & _
			"ORDER BY SR_date"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_resend() {
	if (confirm("これらのメールを再送します。よろしいですか？"))
		location.href = "resend.asp?mail_id=<%=mail_id%>";
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<form method="get" id=form1 name=form1>
	<tr>
		<td>
			<nobr>■送信エラー内容</nobr>
		</td>
		<td align="right">
			<input type="button" value=" 再送 " onclick="OnClick_resend()">
			<input type="button" value=" 戻る " onclick="history.back()">
		</td>
	</tr>
	</form>
</table>

<table border=1 cellpadding=0 cellspacing=1 width="100%">
	<tr bgcolor="#E0FFE0">
		<th>送信日時</th>
		<th>メンバーID</th>
		<th>エラーメッセージ</th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td align='center'>" & TimeFormat(ds("SR_date").Value) & "</td>"
	Writeln "<td align='center'>" & ds("SR_member_id").Value & "</td>"
	Writeln "<td>" & ReplaceTag(ds("SR_send_error").Value) & "</td>"
	Writeln "</tr>"

	ds.MoveNext
	line = line + 1
Loop
%>
</table>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
