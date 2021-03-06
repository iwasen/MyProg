<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:ログイン画面処理
'******************************************************

Const g_login_type = "H"

SetHeaderTitle "はいめーる・net 事務局インターフェース"
top_link = ""
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnLoad_body() {
	document.form1.login_id.focus();
}
function OnSubmit_form1(form) {
	if (form.login_id.value == "") {
		alert("ログインＩＤを入力してください。");
		form.login_id.focus();
		return false;
	}
	if (form.password.value == "") {
		alert("パスワードを入力してください。");
		form.password.focus();
		return false;
	}
	return true;
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" name="form1" action="login_check.asp" onSubmit="return OnSubmit_form1(this)">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">■ログインIDとパスワードを入力してください</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
					<tr>
						<td class="m3" width="25%">ログインID</td>
						<td class="n3"><input type="text" name="login_id"></td>
					</tr>
					<tr>
						<td class="m3" width="25%">パスワード</td>
						<td class="n3"><input type="password" name="password"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<font class="note">本システムは Internet Explorer 5.0 以上でご利用ください。（Netscapeでは動作しません）</font>
	<br><br>
	<input type="submit" value=" ログイン ">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
