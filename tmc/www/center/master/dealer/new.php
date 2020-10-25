<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:販売店新規登録画面
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/select.php");

// 取り扱い車種
function dealer_car() {
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
					echo "<input type='checkbox' name='dealer_car[]' value='$fetch->crm_car_model_cd'>$fetch->crm_car_model_name";
					$i++;
				}
			}
			echo "</td>\n";
		}

		echo "</tr>\n";
	}
	echo "</table>\n";
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.dealer_cd.value == "") {
		alert("販売店コードを入力してください。");
		f.dealer_cd.focus();
		return false;
	}
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
	return confirm("販売店を登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.dealer_cd.focus()">

<? center_header('マスタメンテナンス｜販売店マスタ｜新規登録') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■登録する販売店情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">販売店コード<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="dealer_cd" size=6 maxlength=5>
			<span class="note">（半角５文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">販売店正式名称<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="dealer_name" size=40 maxlength=20>
			<span class="note">（全角２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">略称<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="short_name" size=20 maxlength=10>
			<span class="note">（全角１０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">販売チャネル<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="sales_channel_cd"><? select_sales_channel(DEFAULT_SELECT, '') ?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">担当者<?=MUST_ITEM?></td>
		<td class="n1"><select name="tantousha_id"><? select_tantousha(DEFAULT_SELECT, '')?></select></td>
	</tr>
	<tr>
		<td class="m1">取り扱い車種</td>
		<td class="n1"><? dealer_car() ?></td>
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
