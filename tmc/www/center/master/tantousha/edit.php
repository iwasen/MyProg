<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:担当者変更画面
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// 入力パラメータ
$tantousha_id = $_GET['tantousha_id'];

// 登録済み担当者情報取得
$sql = "SELECT * FROM t_tantousha WHERE tts_tantousha_id=" . sql_number($tantousha_id);
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
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
	if (f.tantousha_name.value == "") {
		alert("担当者名を入力してください。");
		f.tantousha_name.focus();
		return false;
	}
	if (f.from_name.value == "") {
		alert("メールFrom名を入力してください。");
		f.from_name.focus();
		return false;
	}
	if (f.from_addr.value == "") {
		alert("メールFromアドレスを入力してください。");
		f.from_addr.focus();
		return false;
	}
	if (f.mail_account.value == "") {
		alert("アカウント／パスワードを入力してください。");
		f.mail_account.focus();
		return false;
	}
	return confirm("担当者を更新します。よろしいですか？");
}
function delete_check(f) {
	return confirm("担当者を削除します。よろしいですか？");
}
//-->
</script>
</head>
<body>

<? center_header('マスタメンテナンス｜担当者マスタ｜登録変更') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■担当者情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1">担当者名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="tantousha_name" size=50 maxlength=50 <?=value($fetch->tts_tantousha_name)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">メールFrom名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="from_name" size=50 maxlength=50 <?=value($fetch->tts_from_name)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">メールFromアドレス<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="from_addr" size=50 maxlength=50 <?=value($fetch->tts_from_addr)?>>
			<span class="note">（半角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">アカウント／パスワード<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_account" size=50 maxlength=50 <?=value($fetch->tts_mail_account)?>>
			<span class="note">（半角５０文字まで）</span>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="tantousha_id" <?=value($tantousha_id)?>>
</form>
</div>

<? center_footer() ?>

</body>
</html>
