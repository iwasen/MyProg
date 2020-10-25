<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:クレーム管理／メール送信内容入力
'******************************************************

'=== メイン処理 ===
Dim sql, ds, seq_no, subject, from, body

SetHeaderTitle "クレーム対応メール送信"
Response.CacheControl = "no-cache"

seq_no = Request.QueryString("seq")
If IsEmpty(seq_no) Then
	SystemError "empty seq_no"
End If

sql = "SELECT RM_member_id,RM_from,RM_body" & vbCrLf & _
			"FROM T_RECV_MAIL" & vbCrLf & _
			"WHERE RM_seq_no=" & seq_no
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	SystemError "EOF"
End If

GetSendMailInfo "CLAIM", subject, from, body
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1(frm) {
	if (frm.subject.value == "") {
		alert("件名を入力してください。");
		frm.subject.focus();
		return false;
	}
	if (frm.to.value == "") {
		alert("送信先アドレスを入力してください。");
		frm.to.focus();
		return false;
	}
	if (frm.body.value == "") {
		alert("送信内容を入力してください。");
		frm.body.focus();
		return false;
	}
	return confirm("このメールを送信します。よろしいですか？");
}
function OnClick_recv_mail(chk) {
	document.all.show.style.display = (chk ? "" : "none");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" name="form1" action="send_mail2.asp" onsubmit="return OnSubmit_form1(this)">
	<input type="hidden" name="seq_no" value="<%=seq_no%>">
	<input type="hidden" name="from" value="<%=HTMLEncode(from)%>">
	<table border=0 cellspacing=0 cellpadding=0 width='800'>
		<tr>
			<td class="m0">■クレーム対応メール内容</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3">送信者メンバーID</td>
						<td class="n3"><%=ds("RM_member_id").Value%></td>
					</tr>
					<tr>
						<td class="m3" width="22%">件名</td>
						<td class="n3">
							<input type="text" name="subject" size=70 value="<%=HTMLEncode(subject)%>">
							<font class="note">（全角１００文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">送信先アドレス</td>
						<td class="n3">
							<input type="text" name="to" size=70 value="<%=HTMLEncode(ds("RM_from").Value)%>">
							<font class="note">（半角１００文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">送信内容</td>
						<td class="n3"><textarea name="body" cols=78 rows=25><%=body%></textarea></td>
					</tr>
					<tr>
						<td class="m3">受信内容</td>
						<td class="n3">
							<input type="checkbox" onclick="OnClick_recv_mail(checked)">表示する
							<div id="show" style="display:none">
								<textarea cols=78 rows=25 readonly><%=ReadCLOB(ds("RM_body").Value)%></textarea>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" 送信 ">
	<input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
