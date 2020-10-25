<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:発信数詳細表示処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/pro_enquete.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

$pro_enq = new pro_enquete_class;
$pro_enq->read_db($marketer_id, $pro_enq_no);
$cell = &$pro_enq->cell;

set_global('proenquete', 'Ｐｒｏアンケート管理', '発信数詳細表示', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<div align="center">
<form>
<table border=0 cellspacing=2 cellpadding=3 width="80%" frame="box">
<?
$send_cell = array();

$cell->rate_option = 1;
list($select, $join, $groupby) = $cell->get_sql($pro_enq->enquete->start_date);
$sql = "SELECT $select,COUNT(*) AS cnt"
		. " FROM t_enquete_list"
		. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
		. " $join"
		. " WHERE el_enquete_id=$pro_enq->enquete_id"
		. " GROUP BY $groupby";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$send_cell[$fetch->sex][$fetch->age] = $fetch->cnt;
}

$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e, 2);
if ($cell->age_option == 2) {
?>
	<tr align="center" class="m1">
		<td nowrap width="5%" rowspan=2>&nbsp;</td>
		<td nowrap width="5%">10代</td>
		<td nowrap width="10%" colspan=2>20代</td>
		<td nowrap width="10%" colspan=2>30代</td>
		<td nowrap width="10%" colspan=2>40代</td>
		<td nowrap width="10%" colspan=2>50代</td>
		<td nowrap width="10%" colspan=2>60代</td>
		<td nowrap width="5%" rowspan=2>70代<br>以上</td>
		<td nowrap width="5%" rowspan=2>合計</td>
	</tr>
	<tr align="center" class="m1">
		<td>後</td>
		<td>前</td>
		<td>後</td>
		<td>前</td>
		<td>後</td>
		<td>前</td>
		<td>後</td>
		<td>前</td>
		<td>後</td>
		<td>前</td>
		<td>後</td>
	</tr>
<?
} elseif ($cell->age_option == 3) {
?>
	<tr align="center" class="m1">
		<td nowrap width="10%">&nbsp;</td>
		<td nowrap width="10%">10代</td>
		<td nowrap width="10%">20代</td>
		<td nowrap width="10%">30代</td>
		<td nowrap width="10%">40代</td>
		<td nowrap width="10%">50代</td>
		<td nowrap width="10%">60代</td>
		<td nowrap width="10%">70代以上</td>
		<td nowrap width="10%">合計</td>
	</tr>
<?
} else {
?>
	<tr align="center" class="m1">
		<td nowrap width="10%">&nbsp;</td>
		<td nowrap width="10%">年代指定なし</td>
		<td nowrap width="10%">合計</td>
	</tr>
<?
}

$sum_row = array();
for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
?>
	<tr class="m1">
		<td nowrap align="center"><?=decode_sex($sex, '性別指定なし')?></td>
<?
	$sum_col = 0;
	for ($age = $age_s; $age <= $age_e; $age++) {
		$num = $send_cell[$sex][$age];
		$sum_col += $num;
		$sum_row[$age] += $num;
?>
		<td nowrap align="right"><?=number_format($num)?></td>
<?
	}
?>
		<td nowrap align="right"><?=number_format($sum_col)?></td>
	</tr>
<?
}
?>
	<tr class="m1">
		<td nowrap align="center">合計</td>
<?
$sum_col = 0;
for ($age = $age_s; $age <= $age_e; $age++) {
	$num = $sum_row[$age];
	$sum_col += $num;
?>
		<td nowrap align="right"><?=number_format($num)?></td>
<?
}
?>
		<td nowrap align="right"><?=number_format($sum_col)?></td>
	</tr>
</table>
<br>
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
