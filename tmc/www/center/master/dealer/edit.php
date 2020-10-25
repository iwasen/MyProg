<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:販売店変更画面
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/select.php");

// 取り扱い車種
function dealer_car($dealer_cd) {
	// 登録済みの取り扱い車種取得
	$sql = "SELECT dlc_car_model_cd FROM t_dealer_car WHERE dlc_dealer_cd='$dealer_cd'";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	$dealer_car = array();
	for ($i = 0; $i < $nrow; $i++)
		$dealer_car[] = pg_fetch_result($result, $i, 0);

	$sql = "SELECT crm_car_category_cd,crc_car_category_name,crm_car_model_cd,crm_car_model_name"
			. " FROM t_car_model"
			. " JOIN t_car_category ON crc_car_category_cd=crm_car_category_cd"
			. " WHERE crm_car_model_cd<>0"
			. " ORDER BY crc_car_category_cd,crm_car_model_cd";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);

	echo "<table border=0 cellspacing=0 cellpadding=1 width='100%' class='small'>\n";

	for ($i = 0; $i < $nrow; ) {
		$fetch = pg_fetch_object($result, $i);

		if ($car_category_cd != $fetch->crm_car_category_cd) {
			echo "<tr class='subhead'>\n";
			echo "<td colspan=4>$fetch->crc_car_category_name</td>\n";
			echo "</tr>\n";
		}

		$car_category_cd = $fetch->crm_car_category_cd;

		echo "<tr>\n";

		for ($j = 0; $j < 4; $j++) {
			echo "<td width='25%'>";
			if ($i < $nrow) {
				$fetch = pg_fetch_object($result, $i);
				if ($fetch->crm_car_category_cd == $car_category_cd) {
					echo "<input type='checkbox' name='dealer_car[]' value='$fetch->crm_car_model_cd'" . checked(in_array($fetch->crm_car_model_cd, $dealer_car)) . ">$fetch->crm_car_model_name";
					$i++;
				}
			}
			echo "</td>\n";
		}

		echo "</tr>\n";
	}
	echo "</table>\n";
}

// 入力パラメータ
$dealer_cd = $_GET['dealer_cd'];

// 登録済み販売店情報取得
$sql = "SELECT * FROM t_dealer WHERE dlr_dealer_cd=" . sql_char($dealer_cd);
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
	if (f.dealer_name.value == "") {
		alert("販売店正式名称を入力してください。");
		f.dealer_name.focus();
		return false;
	}
	if (f.short_name.value == "") {
		alert("略称を入力してください。");
		f.short_name.focus();
		return false;
	}
	if (f.sales_channel_cd.selectedIndex == 0) {
		alert("販売チャネルを選択してください。");
		f.sales_channel_cd.focus();
		return false;
	}
	if (f.tantousha_id.selectedIndex == 0) {
		alert("担当者を選択してください。");
		f.tantousha_id.focus();
		return false;
	}
	return confirm("販売店を更新します。よろしいですか？");
}
function delete_check(f) {
	return confirm("販売店を削除します。よろしいですか？");
}
//-->
</script>
</head>
<body>

<? center_header('マスタメンテナンス｜販売店マスタ｜登録変更') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■販売店情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">販売店コード</td>
		<td class="n1">
			<?=htmlspecialchars($fetch->dlr_dealer_cd)?>
			<input type="hidden" name="dealer_cd" <?=value($dealer_cd)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">販売店正式名称<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="dealer_name" size=40 maxlength=20 <?=value($fetch->dlr_dealer_name)?>>
			<span class="note">（全角２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">略称<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="short_name" size=20 maxlength=10 <?=value($fetch->dlr_short_name)?>>
			<span class="note">（全角１０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">販売チャネル<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="sales_channel_cd"><? select_sales_channel(DEFAULT_SELECT, $fetch->dlr_sales_channel_cd) ?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">担当者<?=MUST_ITEM?></td>
		<td class="n1"><select name="tantousha_id"><?=select_tantousha(DEFAULT_SELECT, $fetch->dlr_tantousha_id)?></select></td>
	</tr>
	<tr>
		<td class="m1">取り扱い車種</td>
		<td class="n1"><? dealer_car($dealer_cd) ?></td>
	</tr>
</table>
<br>
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="next_action">
</form>
</div>

<? center_footer() ?>

</body>
</html>
