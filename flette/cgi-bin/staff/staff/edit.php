#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteスタッフページ
' Content:スタッフ登録変更画面
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/select.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// 店舗スタッフは自身以外の変更不可
if ($s_staff_auth == 2 && $s_staff_id != $staff_id)
	redirect('../index.php');

$sql = "SELECT * FROM m_staff WHERE st_staff_id=$staff_id";
$result = db_exec($sql);
if (pg_numrows($result) != 0)
	$fetch = pg_fetch_object($result, 0);
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
	switch (f.next_action.value) {
	case "update":
		return update_check(f);
	case "delete":
		return delete_check(f);
	}
	return false;
}
function update_check(f) {
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
		alert("名前（姓）を入力してください。");
		f.name1.focus();
		return false;
	}
	if (f.name2.value == "") {
		alert("名前（名）を入力してください。");
		f.name2.focus();
		return false;
	}
	return confirm("スタッフ情報を更新します。よろしいですか？");
}
function delete_check(f) {
	return confirm("スタッフを削除します。よろしいですか？");
}
//-->
</script>
</head>
<body>

<? page_header('スタッフ情報変更', true, true) ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■スタッフ情報を入力してください</td>
	</tr>
	<tr>
		<td class="m1">メールアドレス<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=100 <?=value($fetch->st_mail_addr)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ログインパスワード<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="password" name="password" maxlength=50 <?=value(str_repeat('*', strlen($fetch->st_password)))?>>
			<span class="note">（半角英数字５０文字まで）</span>
		</td>
	</tr>
<?
if ($s_staff_auth == 1) {
?>
	<tr>
		<td class="m1">権限<?=MUST_ITEM?></td>
		<td class="n1"><select name="auth"><? select_staff_auth($fetch->st_auth) ?></select></td>
	</tr>
<?
}
?>
	<tr>
		<td class="m1">名前（漢字）<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="name1" size=25 maxlength=10 <?=value($fetch->st_name1)?>>姓
			<span class="note">（全角１０文字まで）</span>
			<br>
			<input class="kanji" type="text" name="name2" size=25 maxlength=10 <?=value($fetch->st_name2)?>>名
			<span class="note">（全角１０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">名前（カナ）</td>
		<td class="n1">
			<input class="kanji" type="text" name="name1_kana" size=25 maxlength=30 <?=value($fetch->st_name1_kana)?>>姓
			<span class="note">（全角カタカナ３０文字まで）</span>
			<br>
			<input class="kanji" type="text" name="name2_kana" size=25 maxlength=30 <?=value($fetch->st_name2_kana)?>>名
			<span class="note">（全角カタカナ３０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">所属</td>
		<td class="n1">
			<input class="kanji" type="text" name="section" size=50 maxlength=100 <?=value($fetch->st_section)?>>
			<span class="note">（１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ステータス</td>
		<td class="n1">
			<select name="status"><? select_staff_status($fetch->st_status) ?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">登録日時</td>
		<td class="n1"><?=format_datetime($fetch->st_regist_date)?></td>
	</tr>
	<tr>
		<td class="m1">最終更新日時</td>
		<td class="n1"><?=format_datetime($fetch->st_update_date)?></td>
	</tr>
</table>
<br>
<input type="hidden" name="staff_id" <?=value($staff_id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<?
if ($s_staff_auth == 1) {
?>
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<?
}
?>
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>

</body>
</html>
