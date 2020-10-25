#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteスタッフページ
' Content:店舗別月次予約可能設定状況一覧表示
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");

function display_year_month() {
	global $year, $month, $shop_id;
?>
<table border=2 cellpadding=2 cellspacing=1 width="100%">
	<tr>
		<td>
			<input type="button" value="前年" onclick="set_year(<?=$year - 1?>)">
			<font size="+1"><b><?=$year?>年</b></font>
			<input type="button" value="翌年" onclick="set_year(<?=$year + 1?>)">
			　：　
<?
	for ($i = 1; $i <= 12; $i++) {
		if ($i == $month) {
?>
			<font size="+1"><b><?=$i?>月</b></font>
<?
		} else {
?>
			<a href="javascript:set_month(<?=$i?>)"><font size="-1"><?=$i?>月</font></a>
<?
		}
	}
?>
		</td>
	</tr>
</table>
<?
}

define('DAY_SEC', 24*60*60);

$today = getdate();

if (!isset($year))
	$year = $today['year'];

if (!isset($month))
	$month = $today['mon'];

$d = strtotime("$year/$month/1");
$st = $d - date('w', $d) * DAY_SEC;
$ed = strtotime("$year/" . ($month + 1) . "/1");

// 部屋情報取得
$sql = "SELECT rm_room_id,rm_room_name FROM m_room WHERE rm_shop_id=$shop_id AND rm_status=1 ORDER BY rm_display_order";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$room_ary[$i] = pg_fetch_object($result, $i);
}

// 枠情報取得
$sql = "SELECT wk_room_id,to_char(wk_date,'DD') AS wk_day,wk_start_time,wk_end_time,sum(wk_sex_cd) AS wk_sex_cd"
		. " FROM t_waku"
		. " JOIN m_room ON rm_room_id=wk_room_id"
		. " WHERE rm_shop_id=$shop_id AND rm_status=1 AND to_char(wk_date,'YYYYMM')=" . sql_char(sprintf('%04d%02d', $year, $month))
		. " GROUP BY wk_room_id,wk_date,wk_start_time,wk_end_time"
		. " ORDER BY wk_date,wk_start_time";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$start_hm = substr($fetch->wk_start_time, 0, 5);
	$end_hm = substr($fetch->wk_end_time, 0, 5);
	if ($fetch->wk_sex_cd == 1)
		$sex = '（男）';
	elseif ($fetch->wk_sex_cd == 2)
		$sex = '（女）';
	else
		$sex = '（男・女）';

	$waku_ary[(int)$fetch->wk_day][$fetch->wk_room_id][] = "$start_hm 〜 $end_hm $sex";
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
<script type="text/javascript">
<!--
function set_year(y) {
	var f = document.form1;
	f.year.value = y;
	f.submit();
}
function set_month(m) {
	var f = document.form1;
	f.month.value = m;
	f.submit();
}
//-->
</script>
</head>
<body>

<? page_header('店舗別月次予約可能設定状況一覧', true, true) ?>

<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<tr>
		<td>■<?=decode_shop($shop_id)?></td>
		<td align="right">
<?
if ($s_staff_auth == 2) {
?>

			<input type="button" value="スタッフ情報変更" onclick="location.href='../staff/edit.php?staff_id=<?=$s_staff_id?>'">
<?
}
?>
			<input type="button" value="　戻る　" onclick="location.href='../index.php'">
		</td>
	</tr>
</table>
<br>
<form method="get" name="form1" action="monthly.php">
	<input type="hidden" name="shop_id" <?=value($shop_id)?>>
	<input type="hidden" name="year" <?=value($year)?>>
	<input type="hidden" name="month" <?=value($month)?>>
</form>
<? display_year_month() ?>
<br>
<table <?=LIST_TABLE?> width="100%">
<?
for ($i = 0, $dw = $st; $dw < $ed; $i++, $dw += DAY_SEC * 7) {
	if ($i % 2 == 0) {
?>
	<tr class="tch">
		<th width="10%"></th>
		<th width="10%">日</th>
		<th width="10%">月</th>
		<th width="10%">火</th>
		<th width="10%">水</th>
		<th width="10%">木</th>
		<th width="10%">金</th>
		<th width="10%">土</th>
	</tr>
<?
	}
?>
	<tr bgcolor="#aaffcc">
		<td></td>
<?
	for ($j = 0, $dd = $dw; $j < 7; $j++, $dd += DAY_SEC) {
		if (date('m', $dd) == $month) {
?>
		<td align='center'><font size='+1'><b><a href="edit.php?shop_id=<?=$shop_id?>&date=<?=date('Y/m/d', $dd)?>"><?=(int)date('d', $dd)?></a></b></font></td>
<?
		} else {
?>
		<td></td>
<?
		}
	}
?>
	</tr>
<?
	for ($j = 0; $j < count($room_ary); $j++) {
		$room_id = $room_ary[$j]->rm_room_id;
?>
	<tr>
		<td align="center"><?=htmlspecialchars($room_ary[$j]->rm_room_name)?></td>
<?
		for ($k = 0, $dd = $dw; $k < 7; $k++, $dd += DAY_SEC) {
			if (date('m', $dd) == $month) {
?>
		<td>
		<font size="-1">
<?
				$day = (int)date('d', $dd);
				if (is_array($waku_ary[$day][$room_id])) {
					foreach ($waku_ary[$day][$room_id] as $waku)
						echo $waku, "<br>\n";
				}
?>
		</font>
		</td>
<?
			} else {
?>
		<td></td>
<?
			}
	}
?>
	</tr>
<?
	}
}
?>
</table>
<br>
<? display_year_month() ?>

<? page_footer() ?>

</body>
</html>
