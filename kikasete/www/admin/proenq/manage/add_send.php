<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:追加発信数表示処理
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

set_global('proenquete', 'Ｐｒｏアンケート管理', '追加発信数表示', BACK_TOP);
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
<table border=1 cellspacing=0 cellpadding=3 width="80%" frame="box">
	<tr class="m1">
		<td width="32%" colspan="<?=$cell->age_option == 2 ? 3 : 2?>"></td>
		<td width="16%" align="center">初回発信</td>
		<td width="16%" align="center">追加発信１</td>
		<td width="16%" align="center">追加発信２</td>
		<td width="16%" align="center">発信数合計</td>
	</tr>
<?
$send_cell = array();

$cell->rate_option = 1;
list($select, $join, $groupby) = $cell->get_sql($pro_enq->enquete->start_date);
$sql = "SELECT $select,COALESCE(el_add_send_no,0) AS add_send_no,COUNT(*) AS cnt"
		. " FROM t_enquete_list"
		. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
		. " $join"
		. " WHERE el_enquete_id=$pro_enq->enquete_id"
		. " GROUP BY $groupby,add_send_no";

$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$send_cell[$fetch->sex][$fetch->age][$fetch->add_send_no] = $fetch->cnt;
}

$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e, 2);

for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
	for ($age = $age_s; $age <= $age_e; $age++) {
?>
	<tr class="m1">
<?
		if ($age == $age_s) {
?>
		<td width="16%" nowrap align="center" rowspan="<?=$age_e - $age_s + 1?>"><?=decode_sex($sex, '性別指定なし')?></td>
<?
		}

		if ($cell->age_option == 2) {
			if ($age == 1) {
?>
		<td width="16%" nowrap align="center" colspan=2>10代未満</td>
<?
			} else if ($age == 14) {
?>
		<td nowrap align="center" colspan=2>70代以上</td>
<?
			} else {
				if ($age % 2 == 0) {
?>
		<td nowrap align="center" rowspan=2><?=(int)($age / 2) * 10?>代</td>
		<td align="center">前</td>
<?
				} else {
					if ($age == 3) {
?>
		<td nowrap align="center"><?=(int)($age / 2) * 10?>代</td>
<?
					}
?>
		<td align="center">後</td>
<?
				}
			}
		} elseif ($cell->age_option == 3) {
			if ($age == 1) {
?>
		<td width="16%" nowrap align="center">10代未満</td>
<?
			} else if ($age == 14) {
?>
		<td nowrap align="center">70代以上</td>
<?
			} else {
?>
		<td nowrap align="center"><?=($age - 1) * 10?>代</td>
<?
			}
		} else {
?>
		<td width="16%" nowrap align="center">年代指定なし</td>
<?
		}

		$sum_col = 0;
		for ($add_send_no = 0; $add_send_no <= 2; $add_send_no++) {
			$num = $send_cell[$sex][$age][$add_send_no];
			$sum_col += $num;
			$sum_row[$add_send_no] += $num;

			if ($add_send_no != 0 && $num != 0) {
?>
		<td align="right" bgcolor="#ffcc99"><font color="red"><b><?=number_format($num)?></b></font></td>
<?
			} else {
?>
		<td align="right" class="n1"><?=number_format($num)?></td>
<?
			}
		}
?>
		<td align="right" class="n1"><?=number_format($sum_col)?></td>
	</tr>
<?
	}
}
?>
	<tr class="m1">
		<td align="center" colspan="<?=$cell->age_option == 2 ? 3 : 2?>">合計</td>
<?
$sum_col = 0;
for ($add_send_no = 0; $add_send_no <= 2; $add_send_no++) {
	$num = $sum_row[$add_send_no];
	$sum_col += $num;
?>
		<td align="right" class="n1"><?=number_format($num)?></td>
<?
}
?>
		<td align="right" class="n1"><?=number_format($sum_col)?></td>
	</tr>
</table>
<br>
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
