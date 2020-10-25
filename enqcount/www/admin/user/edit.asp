<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/header.asp"-->
<%
'******************************************************
' System :アンケート集計サービス
' Content:ユーザ登録情報変更
'******************************************************

'=== メイン処理 ===
Dim user_id, sql, rs

set_global "ユーザ登録情報変更", BACK_TOP

user_id = Request.QueryString("user_id")

sql = "SELECT * FROM t_user WHERE us_user_id=" & sql_number(user_id)
Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
%>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Pragma" content="no-cache">
<title><%=g_title%></title>
<link rel="stylesheet" type="text/css" href="../../css/main.css">
<script type="text/javascript">
<!--
function onLoad_body() {
	var f = document.form1;
	f.mail_addr.focus();
}
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "update":
		return input_check(f);
	case "delete":
		return confirm("このユーザを削除します。よろしいですか？");
	}
}
function input_check(f) {
	if (f.mail_addr.value == "") {
		alert("メールアドレスを入力してください。");
		f.mail_addr.focus();
		return false;
	}
	if (f.password.value == "") {
		alert("パスワードを入力してください。");
		f.password.focus();
		return false;
	}
	if (f.name1.value == "") {
		alert("名前を入力してください。");
		f.name1.focus();
		return false;
	}
	if (f.name2.value == "") {
		alert("名前を入力してください。");
		f.name2.focus();
		return false;
	}
	return true;
}
//-->
</script>
</head>
<body onload="document.form1.mail_addr.focus()">
<% page_header() %>

<div align="center">
<form method="post" name="form1" action="update.asp" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■ユーザ情報を入力してください</td>
	</tr>
	<tr>
		<td class="m1" width="20%">メールアドレス<%=MUST_ITEM%></td>
		<td class="n1" width="80%">
			<input class="alpha" type="text" name="mail_addr" size=80 <%=value(rs("us_mail_addr"))%>>
		</td>
	</tr>
	<tr>
		<td class="m1">パスワード<%=MUST_ITEM%></td>
		<td class="n1">
			<input type="password" name="password" size=20 maxlength=20 <%=value(rs("us_password"))%>>
			<span class="note">（半角２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">名前<%=MUST_ITEM%></td>
		<td class="n1">
			<input class="kanji" type="text" name="name1" size="40" <%=value(rs("us_name1"))%>> 姓<br>
			<input class="kanji" type="text" name="name2" size="40" <%=value(rs("us_name2"))%>> 名
		</td>
	</tr>
</table>
<br>
<input type="submit" value="　登録　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="user_id" <%=value(user_id)%>>
</form>
</div>

<% page_footer() %>
</body>
</html>
