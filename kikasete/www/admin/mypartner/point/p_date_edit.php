<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Myパートナー・ポイント付与カレンダー修正
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global('mypartner', 'Myパートナー管理', 'ポイント付与カレンダー修正', BACK_CLOSE);

sscanf($ym, '%4d%2d', $y, $m);

sscanf($calc_ymd, '%4d%2d%2d', $calc_y, $calc_m, $calc_d);
$calc_md = "$calc_m/$calc_d";

sscanf($give_ymd, '%4d%2d%2d', $give_y, $give_m, $give_d);
$give_md = "$give_m/$give_d";

$sql = "SELECT ppl_calc_flag FROM t_mp_point_log WHERE ppl_pjt_id=$pjt_id AND ppl_year_month='$ym'";
$calc_flag = db_fetch1($sql);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	var d = Array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	calc_date = f.calc_md;
	give_date = f.give_md;
	if (calc_date.value == "") {
		alert("計算日を入力してください。");
		calc_date.focus();
		return false;
	}
	if (give_date.value == "") {
		alert("付与日を入力してください。");
		give_date.focus();
		return false;
	}
	if (calc_date.value.match(/^(\d+)\/(\d+)$/) == null
			|| RegExp.$1 < 1
			|| RegExp.$1 > 12
			|| RegExp.$2 < 1
			|| RegExp.$2 > d[RegExp.$1]) {
		alert("計算日が正しくありません。");
		calc_date.focus();
		return false;
	}
	if (give_date.value.match(/^(\d+)\/(\d+)$/) == null
			|| RegExp.$1 < 1
			|| RegExp.$1 > 12
			|| RegExp.$2 < 1
			|| RegExp.$2 > d[RegExp.$1]) {
		alert("付与日が正しくありません。");
		give_date.focus();
		return false;
	}
	return true;
}
//-->
</script>
</head>
<body>
<form method="post" name="form1" action="p_date_update.php" onsubmit="return onsubmit_form1(this)">
<table border=1 cellspacing=0 cellpadding=0 width="100%">
	<tr>
		<td class="m1"></td>
		<td class="m1"><?=$m != 99 ? "{$m}月" : '一括'?></td>
	</tr>
	<tr>
		<td class="m1">計算日</td>
		<td class="n1">
<?
if ($calc_flag == 't') {
?>
			<?=$calc_date?>（計算済み）<input type="hidden" name="calc_md" <?=value($calc_md)?>>

<?
} else {
?>
			<input type="text" name="calc_md" <?=value($calc_md)?> size="5">
<?
}
?>
		</td>
	</tr>
	<tr>
		<td class="m1">付与日</td>
		<td class="n1"><input type="text" name="give_md" <?=value($give_md)?> size="5"></td>
	</tr>
</table>
<table border=0 cellspacing=0 cellpadding=0 width="100%">
	<tr>
		<td align="center">
			<input type="submit" value="更新">
			<input type="button" value="戻る" onclick="window.close()">
		</td>
	</tr>
</table>
<input type="hidden" name="pjt_id" <?=value($pjt_id)?>>
<input type="hidden" name="ym" <?=value($ym)?>>
<input type="hidden" name="calc_y" <?=value($calc_y)?>>
<input type="hidden" name="give_y" <?=value($give_y)?>>
</form>
</body>
</html>
