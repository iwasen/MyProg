<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:誕生日メール配信
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('monitor', 'モニター管理', '誕生日メール配信', BACK_TOP);

$sql = "SELECT * FROM t_birthday_mail WHERE bm_seq_no=$seq_no";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	redirect('list.php');
$fetch = pg_fetch_object($result, 0);


?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onLoad_body() {
	var f = document.form1;
	f.priority.focus();
}
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "update":
		return input_check(f, "誕生日メールを更新します。よろしいですか？");
	case "delete":
		return confirm("誕生日メールを削除します。よろしいですか？");
	case "test":
		return input_check(f, "誕生日メールをテスト送信します。よろしいですか？");
	}
	return false;
}
function input_check(f, msg) {
	if (f.priority.value == "") {
		alert("優先順位を入力してください。");
		f.priority.focus();
		return false;
	}
	if (!check_num(f.priority.value)) {
		alert("優先順位は半角数字で入力してください。");
		f.priority.focus();
		return false;
	}
	if (!check_num(f.age_from.value)) {
		alert("年齢は半角数字で入力してください。");
		f.age_from.focus();
		return false;
	}
	if (!check_num(f.age_to.value)) {
		alert("年齢は半角数字で入力してください。");
		f.age_to.focus();
		return false;
	}
	if (f.point.value == "") {
		alert("付与ポイントを入力してください。");
		f.point.focus();
		return false;
	}
	if (!check_num(f.point.value)) {
		alert("付与ポイントは半角数字で入力してください。");
		f.point.focus();
		return false;
	}
	if (f.from_adr.value == "") {
		alert("送信元アドレスを入力してください。");
		f.from_adr.focus();
		return false;
	}
	if (f.subject.value == "") {
		alert("件名を入力してください。");
		f.subject.focus();
		return false;
	}
	if (f.body.value == "") {
		alert("本文を入力してください。");
		f.body.focus();
		return false;
	}
	return confirm(msg);
}
function check_num(s) {
	for (var i = 0; i < s.length; i++) {
		if (s.charAt(i) < "0" || s.charAt(i) > "9")
			return false;
	}
	return true;
}

//-->
</script>
</head>
<body onload="onLoad_body()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■誕生日メールを入力してください</td>
	</tr>
	<tr>
		<td class="m1" width="20%">優先順位<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input class="number" type="text" name="priority" size=4 maxlength=6 <?=value($fetch->bm_priority)?>>
			<span class="note">（1から99までの数値を入力してください）</span>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">性別<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input type="radio" name="sex" <?=value_checked(0, $fetch->bm_sex)?>>設定なし
			<input type="radio" name="sex" <?=value_checked(1, $fetch->bm_sex)?>>男性
			<input type="radio" name="sex" <?=value_checked(2, $fetch->bm_sex)?>>女性
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">年齢</td>
		<td class="n1" width="80%">
			<input class="number" type="text" name="age_from" size=4 maxlength=3 <?=value($fetch->bm_age_from)?>>才〜
			<input class="number" type="text" name="age_to" size=4 maxlength=3 <?=value($fetch->bm_age_to)?>>才まで
			<span class="note">（年齢を設定しない場合はブランクにしてください）</span>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">登録更新時の付与ポイント<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input class="number" type="text" name="point" size=6 maxlength=6 <?=value($fetch->bm_point)?>>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">送信元アドレス<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input class="alpha" type="text" name="from_adr" size=50 maxlength=50 <?=value($fetch->bm_from_adr)?>>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">件名<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input class="kanji" type="text" name="subject" size=80 maxlength=100 <?=value($fetch->bm_subject)?>>
			<span class="note">（全角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">本文<?=MUST_ITEM?></td>
		<td class="n1">
			<span class="note">※差込み文字が使えます。（モニター名= %MONITOR_NAME%、メールアドレス=%MAIL_ADDR%、ログインURL=%LOGIN_URL% ）</span>
			<textarea name="body" cols=78 rows=20><?=$fetch->bm_body?></textarea><br>
		</td>
	</tr>
	<tr>
		<td class="m1">有効フラグ<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="void_flag" <?=value_checked(1, $fetch->bm_void_flag)?>>有効
			<input type="radio" name="void_flag" <?=value_checked(2, $fetch->bm_void_flag)?>>無効
		</td>
	</tr>
</table>
<br>
<input type="submit" value="テスト送信" onclick="document.form1.next_action.value='test'">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="seq_no" <?=value($seq_no)?>>
</form>
</div>

<? page_footer() ?>
</body>
</html>
