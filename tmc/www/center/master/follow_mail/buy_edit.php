<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:初期フォローメール購買動機変更画面
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

// 登録済みの購買動機取得
$sql = "SELECT fly_contents FROM t_follow_buy WHERE fly_vol_no=$vol_no AND fly_car_model_cd=$car_model_cd";
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
	if (f.next_action.value == "ccreg") {
		f.target = "_blank";
		f.action = "<?=$top?>/common/cc_reg.php";
		return true;
	} else {
		if (f.body.value == "") {
			alert("購買動機を入力してください。");
			f.body.focus();
			return false;
		}
		f.target = "";
		f.action = "buy_update.php";
		return confirm("購買動機を登録します。よろしいですか？");
	}
}
//-->
</script>
</head>
<body onload="document.form1.body.focus()">

<? center_header('マスタメンテナンス｜初期フォローメール｜購買動機') ?>

<div align="center">
<form method="post" name="form1" action="buy_update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■初期フォローメールの購買動機を入力してください。</td>
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
		<td class="m1">購買動機</td>
		<td class="n1"><textarea class="kanji" name="body" cols=78 rows=30><?=htmlspecialchars($fetch->fly_contents)?></textarea></td>
	</tr>
</table>
<br>
<input type="submit" value="ｸﾘｯｸｶｳﾝﾀ登録"onclick="document.form1.next_action.value='ccreg'">
<input type="submit" value="　登録　" onclick="document.form1.next_action.value='update'">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="cc_start_date">
<input type="hidden" name="cc_end_date">
<input type="hidden" name="cc_no">
<input type="hidden" name="cc_url_name">
<input type="hidden" name="cc_jump_url">
<input type="hidden" name="cc_count" value=0>
</form>
</div>

<? center_footer() ?>

</body>
</html>
