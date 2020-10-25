<?php
/**
 * ひとびと･net 事務局ページ
 *
 * Blog活性化推移（日別）ページ
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
$header = new header_class('Blog活性化推移（日毎）', BACK_TOP);

// 入力パラメータ
$year = $_GET['year'];
$month = $_GET['month'];
$navi_page_id = $_GET['navi_page_id'];

// 前月
if (isset($_GET['prev_month'])) {
	if (--$month < 1) {
		$year--;
		$month = 12;
	}
}

// 翌月
if (isset($_GET['next_month'])) {
	if (++$month > 12) {
		$year++;
		$month = 1;
	}
}
// 共通
$year2 = $year;
$month2 = $month + 1;
if ($month2 > 12) {
	$year2++;
	$month2 = 1;
}

// Blog数／記事数グラフ
$graph1 = new daily_graph_class($year, $month);

$graph1->set_row(0, 'Blog数', 1);
$graph1->set_row(1, '記事数', 2);

// 指定年月の日別Bog数を取得
$where = $navi_page_id ? "AND day_navi_page_id=$navi_page_id" : 'AND day_navi_page_id=0';
$sql = "SELECT day_blog_num AS blog_num, date_part('day', day_date) AS day"
	. " FROM t_daily_count"
	. " WHERE day_date >= '$year-$month-01' AND day_date < '$year2-$month2-01' $where";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->day]->blog_num = $fetch->blog_num;
}

// 指定月の日別記事数を取得
$where = $navi_page_id ? "AND nvp_navi_page_id=" . sql_number($navi_page_id) : '';
$sql = "SELECT COUNT(*) AS article_num, date_part('day', bla_date) AS day"
	. " FROM t_blog_article LEFT JOIN t_navi_page ON bla_blog_id=nvp_blog_id"
	. " WHERE bla_status=1 AND bla_date>='$year-$month-01' AND bla_date<'$year2-$month2-01' $where"
	. " GROUP BY day";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->day]->article_num = $fetch->article_num;
}


// トラックバック数／コメント数グラフ
$graph2 = new daily_graph_class($year, $month);

$graph2->set_row(0, 'トラックB数', 1);
$graph2->set_row(1, 'コメント数', 2);

// 指定月の日別トラックバック数取得
$sql = "SELECT COUNT(*) AS trackback_num, date_part('day', blt_date) AS day"
	. " FROM t_blog_trackback LEFT JOIN t_blog_article ON blt_article_id=bla_article_id LEFT JOIN t_navi_page ON bla_blog_id=nvp_blog_id"
	. " WHERE blt_date>='$year-$month-01' AND blt_date<'$year2-$month2-01' $where"
	. " GROUP BY day";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0;$i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->day]->trackback_num = $fetch->trackback_num;
}

// 指定年の日別コメント数取得
$sql = "SELECT COUNT(*) AS comment_num, date_part('day', blo_date) AS day"
	. " FROM t_blog_comment LEFT JOIN t_blog_article ON blo_article_id=bla_article_id LEFT JOIN t_navi_page ON bla_blog_id=nvp_blog_id"
	. " WHERE blo_date>='$year-$month-01' AND blo_date<'$year2-$month2-01' $where"
	. " GROUP BY day";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0;$i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->day]->comment_num = $fetch->comment_num;
}


// UU数／PV数グラフ
$graph3 = new daily_graph_class($year, $month);

$graph3->set_row(0, 'UU数', 1);
$graph3->set_row(1, 'PV数', 2);

// 指定年の日別PV/UU数
$sql = "SELECT COUNT(*) AS pv_num, COUNT(DISTINCT pvl_ip_addr) AS uu_num, date_part('day', pvl_date) AS day"
	. " FROM l_page_view"
	. " LEFT JOIN t_blog_article ON pvl_pv_id=bla_pv_id"
	. " LEFT JOIN t_navi_page ON bla_blog_id=nvp_blog_id"
	. " LEFT JOIN t_blog_trackback ON bla_article_id=blt_article_id AND blt_pv_id=pvl_pv_id"
	. " LEFT JOIN t_blog_comment ON bla_article_id=blo_article_id AND blo_pv_id=pvl_pv_id"
	. " WHERE pvl_date>='$year-$month-01' AND pvl_date<'$year2-$month2-01' $where"
	. " GROUP BY day";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0;$i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->day]->pv_num = $fetch->pv_num;
	$ary[$fetch->day]->uu_num = $fetch->uu_num;
}


// アンケート数／アンケート回答数グラフ
$graph4 = new daily_graph_class($year, $month);

$graph4->set_row(0, 'アンケート数', 1);
$graph4->set_row(1, 'アンケート回答数', 2);

// 指定年の月別アンケート数取得
$sql = "SELECT COUNT(*) AS enquete_num, SUM(nea_answer_count) AS answer_num, date_part('day', nen_date) AS day"
	. " FROM t_navi_enquete LEFT JOIN t_navi_enq_answer ON nen_enquete_id=nea_enquete_id LEFT JOIN t_navi_page ON nen_navi_page_id=nvp_navi_page_id"
	. " WHERE nen_date>='$year-$month-01' AND nen_date<'$year2-$month2-01' $where"
	. " GROUP BY day";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0;$i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->day]->enquete_num = $fetch->enquete_num;
	$ary[$fetch->day]->answer_num = $fetch->answer_num;
}


// メルマガ発行数／メルマガ読者数グラフ
$graph5 = new daily_graph_class($year, $month);

$graph5->set_row(0, 'メルマガ発行数', 1);
$graph5->set_row(1, 'メルマガ読者数', 2);

$sql = "SELECT COUNT(*) AS melmaga_num, date_part('day', mmc_send_date) AS day"
	. " FROM t_melmaga_contents LEFT JOIN t_navi_page ON mmc_melmaga_id=nvp_melmaga_id"
	. " WHERE mmc_send_date>='$year-$month-01' AND mmc_send_date<'$year2-$month2-01' $where"
	. " GROUP BY day";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0;$i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->day]->melmaga_num = $fetch->melmaga_num;
}

// Blog数と記事数を日ごとに積算
for ($day = 1; $day <=31; $day++) {
	$graph1->set_data(0, $day, $ary[$day]->blog_num);
	$graph1->set_data(1, $day, $ary[$day]->article_num);

	$graph2->set_data(0, $day, $ary[$day]->trackback_num);
	$graph2->set_data(1, $day, $ary[$day]->comment_num);

	$graph3->set_data(0, $day, $ary[$day]->uu_num);
	$graph3->set_data(1, $day, $ary[$day]->pv_num);

	$graph4->set_data(0, $day, $ary[$day]->enquete_num);
	$graph4->set_data(1, $day, $ary[$day]->answer_num);

	$graph5->set_data(0, $day, $ary[$day]->melmaga_num);
	$graph5->set_data(1, $day, $ary[$day]->melmaga_member_num);

	if (++$m > 12) {
		$y++;
		$m = 1;
	}
}

// エクスポート処理
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("blog_day_{$year}_{$month}.csv");
	$csv->export_data($graph1, $graph2, $graph3, $graph4, $graph5);
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
            <select name="year" onChange="document.graph.submit()">
<?=select_year(min(2005, $year), '', $year)?>
            </select>
            <input type="submit" name="prev_month" value="≪">
            <select name="month" onChange="document.graph.submit()">
<?=select_month('', $month)?>
            </select>
            <input type="submit" name="next_month" value="≫">
            <select name="navi_page_id" onchange="submit()"><?=select_navi_page_title('★ひとびと・net全体', $navi_page_id)?></select>
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
          <td class="m0">
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
          <td class="m0">
<?php $graph3->disp_legend(); ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
<?php $graph3->disp_graph(); ?>
</table>
<hr class="headfood">
<table width="100%" border=0 cellspacing=0 cellpadding=0>
  <tr>
    <td></td>
    <td>
      <table width="100%" border=0 cellspacing=0 cellpadding=0>
        <tr>
          <td class="m0">
<?php $graph4->disp_legend(); ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
<?php $graph4->disp_graph(); ?>
</table>
<hr class="headfood">
<table width="100%" border=0 cellspacing=0 cellpadding=0>
  <tr>
    <td></td>
    <td>
      <table width="100%" border=0 cellspacing=0 cellpadding=0>
        <tr>
          <td class="m0">
<?php $graph5->disp_legend(); ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
<?php $graph5->disp_graph(); ?>
</table>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
