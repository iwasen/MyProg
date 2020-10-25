<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:販売チャネル変更画面
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// 車種ラインナップ
function car_lineup($sales_channel_cd) {
	$sql = "SELECT crc_car_category_cd,crc_car_category_name,clu_lineup"
			. " FROM t_car_category"
			. " LEFT JOIN t_car_lineup ON clu_sales_channel_cd=$sales_channel_cd AND clu_car_category_cd=crc_car_category_cd"
			. " WHERE crc_car_category_cd<>0"
			. " ORDER BY crc_car_category_cd";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);

	echo "<table border=0 cellspacing=0 cellpadding=1 width='100%' class='small'>\n";

	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		echo "<tr class='subhead'>\n";
		echo "<td colspan=4>$fetch->crc_car_category_name</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td>";
		echo "<textarea name='car_category_cd[$fetch->crc_car_category_cd]' cols=80 rows=5>$fetch->clu_lineup</textarea>\n";
		echo "</td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";
}

// 入力パラメータ
$sales_channel_cd = $_GET['sales_channel_cd'];

// 登録済み販売チャネル情報取得
$sql = "SELECT * FROM t_sales_channel WHERE sch_sales_channel_cd=" . sql_char($sales_channel_cd);
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
	if (f.sales_channel_name.value == "") {
		alert("販売チャネル名を入力してください。");
		f.sales_channel_name.focus();
		return false;
	}
	if (f.sales_channel_mark.value == "") {
		alert("省略記号を入力してください。");
		f.sales_channel_mark.focus();
		return false;
	}
	return confirm("販売チャネルを更新します。よろしいですか？");
}
function delete_check(f) {
	return confirm("販売チャネルを削除します。よろしいですか？");
}
//-->
</script>
</head>
<body>

<? center_header('マスタメンテナンス｜販売チャネルマスタ｜登録変更') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■販売チャネル情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">販売チャネル名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="sales_channel_name" size=50 maxlength=50 <?=value($fetch->sch_sales_channel_name)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">省略記号<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sales_channel_mark" size=1 maxlength=1 <?=value($fetch->sch_sales_channel_mark)?>>
			<span class="note">（半角１文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">車種ラインナップ</td>
		<td class="n1"><? car_lineup($sales_channel_cd) ?></td>
	</tr>
</table>
<br>
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="sales_channel_cd" <?=value($sales_channel_cd)?>>
</form>
</div>

<? center_footer() ?>

</body>
</html>
