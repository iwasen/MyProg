<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:登録エラーメール処理画面
'******************************************************

SetHeaderTitle "登録エラーメール処理"
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
	if (!confirm("このメールを事務局処理とします。よろしいですか？"))
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
	if (!confirm("このメールを破棄します。よろしいですか？"))
		return;
	document.form1.process.value = "3";
	document.form1.submit();
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<%
Dim ds, sql, id

id = Request.QueryString("id")
If IsEmpty(id) Then
	SystemError "empty id"
End If

sql = "SELECT *" & vbCrLf & _
			"FROM T_TOUROKU_MAIL,T_TOUROKU_ERR" & vbCrLf & _
			"WHERE TE_seq_no=" & id & " AND TM_seq_no=TE_seq_no"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
%>
<center>
<form method="post" action="error_mail3.asp" name="form1" onSubmit="return false">
	<input type="hidden" name="seq_no" value="<%=ds("TE_seq_no").Value%>">
	<input type="hidden" name="process">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
		<tr>
			<td class="m0">■登録エラーメール <font size="-1">（エラーメールの内容を確認し、必要ならば修正してください）</font></td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="20%">件名</td>
						<td class="n3"><%=ReplaceTag(ds("TM_subject").Value)%></td>
					</tr>
					<tr>
						<td class="m3">送信者アドレス</td>
						<td class="n3"><%=ReplaceTag(ds("TM_from").Value)%></td>
					</tr>
					<tr>
						<td class="m3">本文</td>
						<td class="n3"><textarea cols=80 rows=30 name="body"><%=ReadCLOB(ds("TE_reply").Value)%></textarea></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<br>
	<input type="button" name="center" value="事務局処理" onclick="OnClick_center()">
	<input type="button" name="sender" value="送信者に返信" onclick="OnClick_reply()">
	<input type="button" name="cancel" value=" 破棄 " onclick="OnClick_cancel()">
	<input type="button" name="original" value="原文表示" onclick="window.open('error_mail4.asp?no=<%=ds("TE_seq_no").Value%>','error_mail4')">
	<input type="reset" value="リセット">
	<input type="button" value=" 戻る " onclick="history.back()">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
