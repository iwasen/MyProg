<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:不達メールアドレス修正処理
'******************************************************

SetHeaderTitle "不達メールアドレス修正"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1(f) {
	if (f.mail_adr.value == "") {
		alert("メールアドレスを入力してください。");
		f.mail_adr.focus();
		return false;
	}
	if (f.mail_adr.value == f.mail_adr_org.value) {
		alert("メールアドレスを変更してください。");
		f.mail_adr.focus();
		return false;
	}
	return confirm("メールアドレスを変更します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body>
<!--#INCLUDE FILE="../inc/header.asp"-->
<%
Dim sql, ds, member_id

member_id = Request.QueryString("id")
sql = "SELECT MM_mail_adr FROM T_MEMBER WHERE MM_member_id='" & member_id & "'"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
%>
<center>
<form method="post" action="cl_update.asp" name="form1" onSubmit="return OnSubmit_form1(this);">
	<input type="hidden" name="member_id" value=<%=member_id%>>
	<input type="hidden" name="mail_adr_org" value="<%=HTMLEncode(ds("MM_mail_adr").Value)%>">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">■メールアドレスを修正してください</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3">メールアドレス</td>
						<td class="n3"><input type="text" name="mail_adr" size=60 value="<%=HTMLEncode(ds("MM_mail_adr").Value)%>"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" 変更 ">
	<input type="reset" value="リセット">
	<input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>
<!--#INCLUDE FILE="../inc/footer.asp"-->
</body>
</html>
