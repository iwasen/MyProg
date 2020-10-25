<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/mail_check.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:全配信アンケート内容画面処理
'******************************************************

'=== メイン処理 ===
Dim id, sql, ds, ds2, body, ary, s, flag

SetHeaderTitle "全配信 アンケートレスポンス表示"

id = Request.QueryString("id")
If IsEmpty(id) Then
	SystemError "empty recv_mail_seq_no"
End If

sql = "SELECT RM_mail_id,RM_member_id,RM_subject,RM_date,RM_recv_date,RM_body" & vbCrLf & _
			"FROM T_RECV_MAIL" & vbCrLf & _
			"WHERE RM_seq_no=" & id
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	SystemError "not found recv_mail"
End If

sql = "SELECT * FROM T_MAIL_READ" & vbCrLf & _
			"WHERE MR_login_type='" & g_login_type & "' AND MR_login_id='" & g_login_id & "' AND MR_enq_imp='E' AND MR_recv_mail=" & id
Set ds2 = CreateDynaset(sql, ORADYN_DEFAULT)
If ds2.EOF Then
	ds2.AddNew
	ds2("MR_login_type").Value = g_login_type
	ds2("MR_login_id").Value = g_login_id
	ds2("MR_enq_imp").Value = "E"
	ds2("MR_recv_mail").Value = id
	ds2("MR_mail_id").Value = ds("RM_mail_id").Value
	ds2.Update
End If
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
<form method="post" name="form1">
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td class="m0">■アンケートレスポンス内容</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="20%">SEQ</td>
						<td class="n3"><%=Request.QueryString("seq")%></td>
					</tr>
					<tr>
						<td class="m3">会員ID</td>
						<td class="n3"><%=ds("RM_member_id").Value%></td>
					</tr>
					<tr>
						<td class="m3">送信日</td>
						<td class="n3"><%=TimeFormat(ds("RM_date").Value)%></td>
					</tr>
					<tr>
						<td class="m3">受信日</td>
						<td class="n3"><%=TimeFormat(ds("RM_recv_date").Value)%></td>
					</tr>
					<tr>
						<td class="m3">サブジェクト</td>
						<td class="n3"><%=ReplaceTag(ds("RM_subject").Value)%></td>
					</tr>
					<tr>
						<td class="m3">返信内容</td>
						<td class="n3">
							<textarea cols=80 rows=25 class="np" readonly>
<%
	body = ReadCLOB(ds("RM_body").Value)
	ary = Split(body, vbCrLf)
	flag = False
	For Each s In ary
		If SeparatorCheck(s, ENQ_START) Then
			flag = True
		End If

		If flag Then
			Writeln DeleteQuote1(s)
		End If

		If SeparatorCheck(s, ENQ_END) Then
			flag = False
		End If
	Next
%>
							</textarea>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<br>
	<input type="button" value=" 戻る " onclick="history.back()">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
