<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:継続リレーションメール車種情報／販売店情報変更画面
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
$dealer_cd = $_GET['dealer_cd'];

// 登録済みの車種情報／販売店情報取得
$sql = "SELECT rlc_contents FROM t_relation_car_dealer WHERE rlc_vol_no=$vol_no AND rlc_dealer_cd='$dealer_cd'";
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
	if (f.body.value == "") {
		alert("車種情報／販売店情報を入力してください。");
		f.body.focus();
		return false;
	}
	return confirm("車種情報／販売店情報を登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.body.focus()">

<? center_header('マスタメンテナンス｜継続リレーションメール｜車種情報／販売店情報') ?>

<div align="center">
<form method="post" name="form1" action="car_dealer_update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■継続リレーションメールの車種情報／販売店情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">VOL</td>
		<td class="n1"><input type="hidden" name="vol_no" <?=value($vol_no)?>><?=$vol_no?></td>
	</tr>
	<tr>
		<td class="m1">販売店</td>
		<td class="n1"><input type="hidden" name="dealer_cd" <?=value($dealer_cd)?>><?=decode_dealer($dealer_cd)?></td>
	</tr>
	<tr>
		<td class="m1">車種情報／販売店情報</td>
		<td class="n1"><textarea class="kanji" name="body" cols=78 rows=30><?=htmlspecialchars($fetch->rlc_contents)?></textarea></td>
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
