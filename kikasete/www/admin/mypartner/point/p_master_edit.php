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
set_global('mypartner', 'Myパートナー管理', 'ポイント付与カレンダー修正', BACK_TOP);

$sql = "SELECT * FROM m_mp_point_date";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	sscanf($fetch->ppd_calc_date, '%2d%2d', $m, $d);
	$calc_date[$fetch->ppd_month] = "$m/$d";

	sscanf($fetch->ppd_give_date, '%2d%2d', $m, $d);
	$give_date[$fetch->ppd_month] = "$m/$d";
}
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
	for (var i = 1; i <= 12; i++) {
		calc_date = f["calc_date[" + i + "]"];
		give_date = f["give_date[" + i + "]"];
		if (calc_date.value == "") {
			alert(i + "月の計算日を入力してください。");
			calc_date.focus();
			return false;
		}
		if (give_date.value == "") {
			alert(i + "月の付与日を入力してください。");
			give_date.focus();
			return false;
		}
		if (calc_date.value.match(/^(\d+)\/(\d+)$/) == null
				|| RegExp.$1 < 1
				|| RegExp.$1 > 12
				|| RegExp.$2 < 1
				|| RegExp.$2 > d[RegExp.$1]) {
			alert(i + "月の計算日が正しくありません。");
			calc_date.focus();
			return false;
		}
		if (give_date.value.match(/^(\d+)\/(\d+)$/) == null
				|| RegExp.$1 < 1
				|| RegExp.$1 > 12
				|| RegExp.$2 < 1
				|| RegExp.$2 > d[RegExp.$1]) {
			alert(i + "月の付与日が正しくありません。");
			give_date.focus();
			return false;
		}
	}
	return true;
}
//-->
</script>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="p_master_update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=0 cellpadding=0 width="100%">
	<tr class="lc">
		<td valign="top">
			■ポイント付与時期マスタ
		</td>
	</tr>
</table>
<table border=1 cellspacing=0 cellpadding=0 width="100%">
	<tr align="center">
		<td class="m1"></td>
		<td class="m1">1月</td>
		<td class="m1">2月</td>
		<td class="m1">3月</td>
		<td class="m1">4月</td>
		<td class="m1">5月</td>
		<td class="m1">6月</td>
		<td class="m1">7月</td>
		<td class="m1">8月</td>
		<td class="m1">9月</td>
		<td class="m1">10月</td>
		<td class="m1">11月</td>
		<td class="m1">12月</td>
	</tr>
	<tr align="center">
		<td class="m1">計算日</td>
<?
for ($i = 1; $i <= 12; $i++) {
?>
		<td class="n1"><input type="text" name="calc_date[<?=$i?>]" value="<?=$calc_date[$i]?>" size="5"></td>
<?
}
?>
	</tr>
	<tr align="center">
		<td class="m1">付与日</td>
<?
for ($i = 1; $i <= 12; $i++) {
?>
		<td class="n1"><input type="text" name="give_date[<?=$i?>]" value="<?=$give_date[$i]?>" size="5"></td>
<?
}
?>
	</tr>
</table>
<br>
<table border=0 cellspacing=0 cellpadding=0 width="100%">
	<tr>
		<td align="center">
			<input type="submit" value="　更新　">
			<input type="button" value="　戻る　" onclick="history.back()">
		</td>
	</tr>
</table>
<input type="hidden" name="pjt_id" <?=value($pjt_id)?>>
</form>

<? menu_end() ?>
<? page_footer() ?>

</html>
