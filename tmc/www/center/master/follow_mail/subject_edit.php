<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:初期フォローメール件名変更画面
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
$car_model_cd = $_GET['car_model_cd'];

// 登録済み件名取得
$sql = "SELECT fls_subject FROM t_follow_subject WHERE fls_vol_no=$vol_no AND fls_car_model_cd=$car_model_cd";
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
	if (f.subject.value == "") {
		alert("件名を入力してください。");
		f.subject.focus();
		return false;
	}
	return confirm("件名を登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.subject.focus()">

<? center_header('マスタメンテナンス｜初期フォローメール｜件名') ?>

<div align="center">
<form method="post" name="form1" action="subject_update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■初期フォローメールの件名を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">VOL</td>
		<td class="n1"><input type="hidden" name="vol_no" <?=value($vol_no)?>><?=$vol_no?></td>
	</tr>
	<tr>
		<td class="m1">車種</td>
		<td class="n1"><input type="hidden" name="car_model_cd" <?=value($car_model_cd)?>><?=decode_car_model($car_model_cd)?></td>
	</tr>
	<tr>
		<td class="m1">件名</td>
		<td class="n1"><input type="text" class="kanji" name="subject" size=80 <?=value($fetch->fls_subject)?>></td>
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
