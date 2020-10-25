<?php
/**
 * ひとびと･net 事務局ページ
 *
 * メンバー推移（日別）ページ
 *
 *
 * @package
 * @author
 * @version
 */


$top = '../..';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/graph.php");
include("$inc/csv.php");

// ページヘッダ情報設定
$header = new header_class('メンバー入退会者集計（日毎）', BACK_TOP);

// 入力パラメータ
$year = $_REQUEST['year'];
$month = $_REQUEST['month'];
$room_id = $_REQUEST['room_id'];

// 前月
if (isset($_REQUEST['prev_month'])) {
	if (--$month < 1) {
		$year--;
		$month = 12;
	}
}

// 翌月
if (isset($_REQUEST['next_month'])) {
	if (++$month > 12) {
		$year++;
		$month = 1;
	}
}

// メンバー数／便り登録者数グラフ
$graph1 = new daily_graph_class($year, $month);
$graph1->set_row(0, 'メンバー数', 1);
$graph1->set_row(1, '便り登録者数', 2);

// 入会者数／退会者数グラフ
$graph2 = new daily_graph_class($year, $month);
$graph2->set_row(0, '入会者数', 1);
$graph2->set_row(1, '退会者数', 2);

// 指定月の日別メンバー数と便り登録者数を取得
$year2 = $year;
$month2 = $month + 1;
if ($month2 > 12) {
	$year2++;
	$month2 = 1;
}

// 指定年月のメンバー数と便り登録者数を取得
$where = $room_id ? "AND day_navi_page_id=(SELECT nvp_navi_page_id FROM t_navi_page WHERE nvp_room_id=$room_id)" : 'AND day_navi_page_id=0';
$sql = "SELECT day_member_num AS member_num, day_letter_num AS letter_num, date_part('day', day_date) AS day"
	. " FROM t_daily_count"
	. " WHERE day_date >= '$year-$month-01' AND day_date < '$year2-$month2-01' $where";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->day]->member_num = $fetch->member_num;
	$ary[$fetch->day]->letter_num = $fetch->letter_num;
}

// 指定年月の入会者数を取得
$where = $room_id ? "AND rmm_room_id=" . sql_number($room_id) : '';
$sql = "SELECT COUNT(*) as member_num, date_part('day', rmm_regist_date) as day"
		. " FROM t_room_member"
		. " JOIN t_member ON mbr_member_id=rmm_member_id"
		. " WHERE rmm_regist_date>='$year-$month-01' AND rmm_regist_date<'$year2-$month2-01' $where"
		. " GROUP BY day";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$ary[$fetch->day]->regist_member_num = $fetch->member_num;
}

// 指定年月の退会者数を取得
$sql = "SELECT COUNT(*) as member_num, date_part('day', rmm_resign_date) as day"
		. " FROM t_room_member"
		. " JOIN t_member ON mbr_member_id=rmm_member_id"
		. " WHERE rmm_resign_date>='$year-$month-01' AND rmm_resign_date<'$year2-$month2-01' $where"
		. " GROUP BY day";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$ary[$fetch->day]->resign_member_num = $fetch->member_num;
}

// メンバー数と便り登録者数を月毎に積算
for ($day = 1; $day <= 31; $day++) {
	$graph1->set_data(0, $day, $ary[$day]->member_num);
	$graph1->set_data(1, $day, $ary[$day]->letter_num);

	$graph2->set_data(0, $day, $ary[$day]->regist_member_num);
	$graph2->set_data(1, $day, $ary[$day]->resign_member_num);
}

// エクスポート処理
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("member_day_{$year}_{$month}.csv");
	$csv->export_data($graph1, $graph2);
	exit;
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body>

<?php $header->page_header(); ?>

<form name="graph">

<table width="100%" border=0 cellspacing=0 cellpadding=0>
  <tr>
    <td></td>
    <td>
      <table width="100%" border=0 cellspacing=0 cellpadding=0>
        <tr>
          <td>
<?php $graph1->disp_legend(); ?>
          </td>
          <td align="right">
            <select name="year" onChange="document.graph.submit()">
<?=select_year(min(2005, $year), '', $year)?>
            </select>
            <input type="submit" name="prev_month" value="≪">
            <select name="month" onChange="document.graph.submit()">
<?=select_month('', $month)?>
            </select>
            <input type="submit" name="next_month" value="≫">
            <select name="room_id" onchange="submit()"><?=select_room('★ひとびと・net全体', $room_id)?></select>
            <input type="submit" name="export" value="エクスポート">
          </td>
        </tr>
      </table>
    </td>
  </tr>
<?php $graph1->disp_graph(); ?>
</table>
<hr class="headfood">
<table width="90%" border=0 cellspacing=0 cellpadding=0>
  <tr>
    <td></td>
    <td>
      <table width="100%" border=0 cellspacing=0 cellpadding=0>
        <tr>
          <td>
<?php $graph2->disp_legend(); ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
<?php $graph2->disp_graph(); ?>
</table>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
