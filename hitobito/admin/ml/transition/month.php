<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ML投稿数推移（月別）ページ
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
include("$inc/graph.php");
include("$inc/select.php");
include("$inc/csv.php");

// ページヘッダ情報設定
$header = new header_class('ML活性化推移（月毎）', BACK_TOP);

// 入力パラメータ
$year = $_GET['year'];
$room_id = $_GET['room_id'];

// デフォルトの年を設定
if (!$year)
	$year = date('Y') - 1;

// 前年
if (isset($_GET['prev_year']))
	$year--;

// 翌年
if (isset($_GET['next_year']))
	$year++;

// ML数／メンバー数グラフ
$graph1 = new monthly_graph_class($year, "day.php?room_id=$room_id", '日毎のML数/メンバー数集計を表示');
$graph1->set_row(0, '　　　　　ML数', 1);
$graph1->set_row(1, 'メンバー数', 2);

// 指定年の月別ML数とMLメンバー数を取得
$year2 = $year + 2;
$where = $room_id ? "AND day_navi_page_id=(SELECT nvp_navi_page_id FROM t_navi_page WHERE nvp_room_id=$room_id)" : 'AND day_navi_page_id=0';
$cond_date = "SELECT MAX(day_date) FROM t_daily_count WHERE day_date >= '$year-01-01' AND day_date < '$year2-01-01' GROUP BY to_char(day_date, 'YYYYMM')";
$sql = "SELECT day_ml_num AS ml_num, day_member_num AS member_num, to_char(day_date, 'YYYYMM') AS ym"
	. " FROM t_daily_count"
	. " WHERE day_date IN ($cond_date) $where";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->ym]->room_num = $fetch->ml_num;
	$ary[$fetch->ym]->member_num = $fetch->member_num;
}

// 発言者数／発言数グラフ
$graph2 = new monthly_graph_class($year, "day.php?room_id=$room_id", '日毎の発言者数／発言数集計を表示');
$graph2->set_row(0, '　　　発言者数', 1);
$graph2->set_row(1, '　発言数', 2);

$where = $room_id ? "AND rom_room_id=" . sql_number($room_id) : '';
$sql = "SELECT COUNT(*) AS remark_num, COUNT(DISTINCT rmk_member_id) AS remark_member_num, to_char(rmk_date, 'YYYYMM') AS ym"
	. " FROM l_room_remark LEFT JOIN t_room ON rmk_room_id=rom_room_id"
	. " WHERE rmk_date>='$year-01-01' AND rmk_date<'$year2-01-01' $where"
	. " GROUP BY ym";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0;$i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->ym]->remark_member_num = $fetch->remark_member_num;
	$ary[$fetch->ym]->remark_num = $fetch->remark_num;
}

// 訪問者数／ページビュー数グラフ
$graph3 = new monthly_graph_class($year, "day.php?room_id=$room_id", '日毎の訪問者数／ページビュー数集計を表示');
$graph3->set_row(0, '訪問者数', 1);
$graph3->set_row(1, 'ページビュー数', 2);

$sql = "SELECT COUNT(*) AS pv_num, COUNT(DISTINCT pvl_ip_addr) AS uu_num, to_char(pvl_date, 'YYYYMM') AS ym"
	. " FROM l_page_view LEFT JOIN l_room_remark ON pvl_pv_id=rmk_pv_id"
	. " LEFT JOIN t_room ON rmk_room_id=rom_room_id"
	. " WHERE pvl_pv_id=rmk_pv_id AND pvl_date>='$year-01-01' AND pvl_date<'$year2-01-01' $where"
	. " GROUP BY ym";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0;$i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->ym]->pv_num = $fetch->pv_num;
	$ary[$fetch->ym]->uu_num = $fetch->uu_num;
}


// メンバー数と便り登録者数を月毎に積算
$current_ym = date('Ym');
$y = $year;
$m = 1;
for ($i = 1; $i <= 24; $i++) {
	$ym = sprintf('%04d%02d', $y, $m);

	if ($ym > $current_ym)
		break;

	$graph1->set_data(0, $ym, $ary[$ym]->room_num);
	$graph1->set_data(1, $ym, $ary[$ym]->member_num);

	$graph2->set_data(0, $ym, $ary[$ym]->remark_member_num);
	$graph2->set_data(1, $ym, $ary[$ym]->remark_num);

	$graph3->set_data(0, $ym, $ary[$ym]->uu_num);
	$graph3->set_data(1, $ym, $ary[$ym]->pv_num);

	if (++$m > 12) {
		$y++;
		$m = 1;
	}
}

// エクスポート処理
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("ml_month_$year.csv");
	$csv->export_data($graph1, $graph2, $graph3);
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
            <input type="submit" name="prev_year" value="≪">
            <select name="year" onChange="document.graph.submit()">
<?=select_year(min(2005, $year), '', $year)?>
            </select>
            <input type="submit" name="next_year" value="≫">
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
<table width="100%" border=0 cellspacing=0 cellpadding=0>
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
<hr class="headfood">
<table width="100%" border=0 cellspacing=0 cellpadding=0>
  <tr>
    <td></td>
    <td>
      <table width="100%" border=0 cellspacing=0 cellpadding=0>
        <tr>
          <td>
<?php $graph3->disp_legend(); ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
<?php $graph3->disp_graph(); ?>
</table>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
