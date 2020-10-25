<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ナビ登録集計ページ
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
$header = new header_class('ナビ登録集計', BACK_TOP);

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

// メンバー数／便り登録者数グラフ
$graph1 = new monthly_graph_class($year);

$graph1->set_row(0, 'ナビページ', 1);
$graph1->set_row(1, '新規');
$graph1->set_row(2, '閉鎖');
$graph1->set_row(3, 'ワイワイ会議', 2);
$graph1->set_row(4, '新規');
$graph1->set_row(5, '閉鎖');

// 指定年以前のナビページとワイワイ会議数
$sql = "SELECT COUNT(*) AS blog_num, SUM(CASE WHEN nvp_type=1 THEN 1 ELSE 0 END) AS ml_num"
	. " FROM t_navi_page"
	. " WHERE nvp_open_flag=1 AND nvp_open_date<'$year-01-01'";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$blog_num = $fetch->blog_num;
	$ml_num = $fetch->ml_num;
}

// 指定年の月別ナビページとワイワイ会議数を取得
$year2 = $year + 2;
$sql = "SELECT COUNT(*) AS blog_num, SUM(CASE WHEN nvp_type=1 THEN 1 ELSE 0 END) AS ml_num"
	. ",to_char(nvp_open_date, 'YYYYMM') AS ym"
	. " FROM t_navi_page"
	. " WHERE nvp_open_flag=1 AND nvp_open_date>='$year-01-01' AND nvp_open_date<'$year2-01-01'"
	. " GROUP BY ym";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->ym]->open_blog_num = $fetch->blog_num;
	$ary[$fetch->ym]->open_ml_num = $fetch->ml_num;
}

// 指定年の閉鎖ナビページとワイワイ会議数を取得
$sql = "SELECT COUNT(*) AS blog_num, SUM(CASE WHEN nvp_type=1 THEN 1 ELSE 0 END) AS ml_num"
	. ",to_char(nvp_resign_date, 'YYYYMM') AS ym"
	. " FROM t_navi_page"
	. " WHERE nvp_open_flag=9 AND nvp_resign_date>='$year-01-01' AND nvp_resign_date<'$year2-01-01'"
	. " GROUP BY ym";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->ym]->resign_blog_num = $fetch->blog_num;
	$ary[$fetch->ym]->resign_ml_num = $fetch->ml_num;
}

// ナビページとワイワイ会議数を月毎に積算
$current_ym = date('Ym');
$y = $year;
$m = 1;
for ($i = 1; $i <= 24; $i++) {
	$ym = sprintf('%04d%02d', $y, $m);
	if ($ym > $current_ym)
		break;

	$blog_num += $ary[$ym]->open_blog_num - $ary[$ym]->resign_blog_num;
	$ml_num += $ary[$ym]->open_ml_num - $ary[$ym]->resign_ml_num;

	$graph1->set_data(0, $ym, $blog_num);
	$graph1->set_data(1, $ym, $ary[$ym]->open_blog_num);
	$graph1->set_data(2, $ym, $ary[$ym]->resign_blog_num);
	$graph1->set_data(3, $ym, $ml_num);
	$graph1->set_data(4, $ym, $ary[$ym]->open_ml_num);
	$graph1->set_data(5, $ym, $ary[$ym]->resign_ml_num);

	if (++$m > 12) {
		$y++;
		$m = 1;
	}

}

// エクスポート処理
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("navi_month_$year.csv");
	$csv->export_data($graph1);
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
          <td class="m0">
<?php $graph1->disp_legend(); ?>
          </td>
          <td align="right">
            <input type="submit" name="prev_year" value="≪">
            <select name="year" onChange="document.graph.submit()">
<?=select_year(min(2005, $year), '', $year)?>
            </select>
            <input type="submit" name="next_year" value="≫">
            <input type="submit" name="export" value="エクスポート">
          </td>
        </tr>
      </table>
    </td>
  </tr>
<?php $graph1->disp_graph(); ?>
</table>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
