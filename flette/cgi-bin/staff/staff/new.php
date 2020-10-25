#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteスタッフページ
' Content:スタッフ新規登録画面
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/select.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
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
	if (f.auth.value == "") {
		alert("権限を選択してください。");
		f.auth.focus();
		return false;
	}
	if (f.name1.value == "") {
		alert("名前（姓）を入力してください。");
		f.name1.focus();
		return false;
	}
	if (f.name2.value == "") {
		alert("名前（名）を入力してください。");
		f.name2.focus();
		return false;
	}
	return confirm("スタッフを登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.mail_addr.focus()">

<? page_header('スタッフ新規登録', true, true) ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■登録するスタッフ情報を入力してください</td>
	</tr>
	<tr>
		<td class="m1">メールアドレス<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=100>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ログインパスワード<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="password" name="password" maxlength=50>
			<span class="note">（半角英数字５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">権限<?=MUST_ITEM?></td>
		<td class="n1"><select name="auth"><? select_staff_auth('') ?></select></td>
	</tr>
	<tr>
		<td class="m1">名前（漢字）<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="name1" size=25 maxlength=10>姓
			<span class="note">（全角１０文字まで）</span>
			<br>
			<input class="kanji" type="text" name="name2" size=25 maxlength=10>名
			<span class="note">（全角１０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">名前（カナ）</td>
		<td class="n1">
			<input class="kanji" type="text" name="name1_kana" size=25 maxlength=30>姓
			<span class="note">（全角カタカナ３０文字まで）</span>
			<br>
			<input class="kanji" type="text" name="name2_kana" size=25 maxlength=30>名
			<span class="note">（全角カタカナ３０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">所属</td>
		<td class="n1">
			<input class="kanji" type="text" name="section" size=50 maxlength=100>
			<span class="note">（１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ステータス</td>
		<td class="n1">
			<select name="status"><? select_staff_status('1') ?></select>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="　登録　">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="next_action" value="new">
</form>
</div>

<? page_footer() ?>

</body>
</html>
