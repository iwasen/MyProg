<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:車種カテゴリ変更画面
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// 入力パラメータ
$car_category_cd = $_GET['car_category_cd'];

// 登録済み車種カテゴリ情報取得
$sql = "SELECT * FROM t_car_category WHERE crc_car_category_cd=" . sql_char($car_category_cd);
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
	if (f.car_category_name.value == "") {
		alert("車種カテゴリ名を入力してください。");
		f.car_category_name.focus();
		return false;
	}
	return confirm("車種カテゴリを更新します。よろしいですか？");
}
function delete_check(f) {
	return confirm("車種カテゴリを削除します。よろしいですか？");
}
//-->
</script>
</head>
<body>

<? center_header('マスタメンテナンス｜車種カテゴリマスタ｜登録変更') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■車種カテゴリ情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">車種カテゴリ名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="car_category_name" size=50 maxlength=50 <?=value($fetch->crc_car_category_name)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="car_category_cd" <?=value($car_category_cd)?>>
</form>
</div>

<? center_footer() ?>

</body>
</html>
