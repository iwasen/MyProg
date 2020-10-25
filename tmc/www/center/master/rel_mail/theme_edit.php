<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:継続リレーションメール投げかけテーマ部変更画面
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/decode.php");

// 入力パラメータ
$vol_no = $_GET['vol_no'];
$car_category_cd = $_GET['car_category_cd'];

// 登録済みの投げかけテーマ部取得
$sql = "SELECT rlt_theme,rlt_contents FROM t_relation_theme WHERE rlt_vol_no=$vol_no AND rlt_car_category_cd=$car_category_cd";
$result = db_exec($sql);
if (pg_num_rows($result))
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
	var f = document.form1;
	if (f.theme.value == "") {
		alert("テーマを入力してください。");
		f.theme.focus();
		return false;
	}
	if (f.body.value == "") {
		alert("投げかけテーマ部を入力してください。");
		f.body.focus();
		return false;
	}
	return confirm("投げかけテーマ部を登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.theme.focus()">

<? center_header('マスタメンテナンス｜継続リレーションメール｜投げかけテーマ部') ?>

<div align="center">
<form method="post" name="form1" action="theme_update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■継続リレーションメールの投げかけテーマ部を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">VOL</td>
		<td class="n1"><input type="hidden" name="vol_no" <?=value($vol_no)?>><?=$vol_no?></td>
	</tr>
	<tr>
		<td class="m1">車種カテゴリ</td>
		<td class="n1"><input type="hidden" name="car_category_cd" <?=value($car_category_cd)?>><?=decode_car_category($car_category_cd)?></td>
	</tr>
	<tr>
		<td class="m1">テーマ</td>
		<td class="n1"><input type="text" class="kanji" name="theme" size=80 <?=value($fetch->rlt_theme)?>></td>
	</tr>
	<tr>
		<td class="m1">投げかけテーマ部</td>
		<td class="n1"><textarea class="kanji" name="body" cols=78 rows=30><?=htmlspecialchars($fetch->rlt_contents)?></textarea></td>
	</tr>
</table>
<br>
<input type="submit" value="　登録　">
<input type="button" value="キャンセル" onclick="history.back()">
</form>
</div>

<? center_footer() ?>

</body>
</html>
