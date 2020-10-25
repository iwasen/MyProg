<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:アンケートエラーメール処理画面
'******************************************************

SetHeaderTitle "アンケートエラーメール処理"

Dim ds, sql, seq_no

seq_no = Request.QueryString("seq_no")
If IsEmpty(seq_no) Then
	SystemError "empty seq_no"
End If

sql = "SELECT MM_member_id,MM_mail_adr,RM_subject,EE_seq_no,EE_body" & vbCrLf & _
			"FROM T_MEMBER,T_RECV_MAIL,T_ENQUETE_ERR" & vbCrLf & _
			"WHERE EE_seq_no=" & seq_no & " AND RM_seq_no=EE_seq_no AND MM_member_id=RM_member_id"
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
function OnClick_center() {
	if (!confirm("このメールを事務局処理します。よろしいですか？"))
		return;
	document.form1.process.value = "1";
	document.form1.submit();
}
function OnClick_reply() {
	if (!confirm("このメールを送信者に返信します。よろしいですか？"))
		return;
	document.form1.process.value = "2";
	document.form1.submit();
}
function OnClick_cancel() {
	if (!confirm("このメールを無処理とします。よろしいですか？"))
		return;
	document.form1.process.value = "3";
	document.form1.submit();
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="enq_error3.asp" name="form1" onSubmit="return false">
	<input type="hidden" name="seq_no" value="<%=ds("EE_seq_no").Value%>">
	<input type="hidden" name="process">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
		<tr>
			<td class="m0">■アンケートエラーメール <font size="-1">（エラーメールの内容を確認し、必要ならば修正してください）</font></td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="20%">メンバーID</td>
						<td class="n3"><%=ds("MM_member_id").Value%></td>
					</tr>
					<tr>
						<td class="m3" width="20%">メールアドレス</td>
						<td class="n3"><%=ReplaceTag(ds("MM_mail_adr").Value)%></td>
					</tr>
					<tr>
						<td class="m3" width="20%">件名</td>
						<td class="n3"><%=ReplaceTag(ds("RM_subject").Value)%></td>
					</tr>
					<tr>
						<td class="m3">本文</td>
						<td class="n3"><textarea cols=80 rows=30 name="body"><%=ReadCLOB(ds("EE_body").Value)%></textarea></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<br>
	<input type="button" name="center" value="事務局処理" onclick="OnClick_center()">
	<input type="button" name="sender" value="送信者に返信" onclick="OnClick_reply()">
	<input type="button" name="cancel" value=" 無処理 " onclick="OnClick_cancel()">
	<input type="reset" value="リセット">
	<input type="button" value=" 戻る " onclick="history.back()">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
