<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:車種新規登録画面
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/select.php");
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.car_model_name.value == "") {
		alert("車種名を入力してください。");
		f.car_model_name.focus();
		return false;
	}
	if (f.car_category_cd.selectedIndex == 0) {
		alert("車種カテゴリを選択してください。");
		f.car_category_cd.focus();
		return false;
	}
	return confirm("車種を登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.car_model_name.focus()">

<? center_header('マスタメンテナンス｜車種マスタ｜新規登録') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■登録する車種情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">車種名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="car_model_name" size=40 maxlength=20>
			<span class="note">（全角２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">車種カテゴリ<?=MUST_ITEM?></td>
		<td class="n1"><select name="car_category_cd"><? select_car_category(DEFAULT_SELECT, '')?></select></td>
	</tr>
</table>
<br>
<input type="submit" value="　登録　">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="next_action" value="new">
</form>
</div>

<? center_footer() ?>

</body>
</html>
