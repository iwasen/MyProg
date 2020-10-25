<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * Blog��������ܡʷ��̡˥ڡ���
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

// �ڡ����إå���������
$header = new header_class('Blog��������ܡʷ����', BACK_TOP);

// ���ϥѥ�᡼��
$year = $_GET['year'];
$navi_page_id = $_GET['navi_page_id'];

// �ǥե���Ȥ�ǯ������
if (!$year)
	$year = date('Y') - 1;

// ��ǯ
if (isset($_GET['prev_year']))
	$year--;

// ��ǯ
if (isset($_GET['next_year']))
	$year++;

// Blog���������������
$graph1 = new monthly_graph_class($year, "day.php?navi_page_id=$navi_page_id", '�����Blog/���������פ�ɽ��');

$graph1->set_row(0, '����Blog��', 1);
$graph1->set_row(1, '��������', 2);

// ����ǯ�η���Blog������
$year2 = $year + 2;
$where = $navi_page_id ? "AND day_navi_page_id=$navi_page_id" : 'AND day_navi_page_id=0';
$cond_date = "SELECT MAX(day_date) FROM t_daily_count WHERE day_date >= '$year-01-01' AND day_date < '$year2-01-01' GROUP BY to_char(day_date, 'YYYYMM')";
$sql = "SELECT day_blog_num AS blog_num, to_char(day_date, 'YYYYMM') AS ym"
	. " FROM t_daily_count"
	. " WHERE day_date IN ($cond_date) $where";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->ym]->blog_num = $fetch->blog_num;
}

// ����ǯ�η��̵���������
$where = $navi_page_id ? "AND nvp_navi_page_id=" . sql_number($navi_page_id) : '';
$sql = "SELECT COUNT(*) AS article_num, to_char(bla_date, 'YYYYMM') AS ym"
	. " FROM t_blog_article LEFT JOIN t_navi_page ON bla_blog_id=nvp_blog_id"
	. " WHERE bla_status=1 AND bla_date>='$year-01-01' AND bla_date<'$year2-01-01' $where"
	. " GROUP BY ym";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0;$i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->ym]->article_num = $fetch->article_num;
}

// �ȥ�å��Хå����������ȿ������
$graph2 = new monthly_graph_class($year, "day.php?navi_page_id=$navi_page_id", '����Υȥ�å�B/�����ȿ����פ�ɽ��');

$graph2->set_row(0, '�ȥ�å�B��', 1);
$graph2->set_row(1, '�����ȿ�', 2);

// ����ǯ�η��̥ȥ�å��Хå�������
$sql = "SELECT COUNT(*) AS trackback_num, to_char(blt_date, 'YYYYMM') AS ym"
	. " FROM t_blog_trackback LEFT JOIN t_blog_article ON blt_article_id=bla_article_id LEFT JOIN t_navi_page ON bla_blog_id=nvp_blog_id"
	. " WHERE bla_date>='$year-01-01' AND bla_date<'$year2-01-01' $where"
	. " GROUP BY ym";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0;$i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->ym]->trackback_num = $fetch->trackback_num;
}

// ����ǯ�η��̥����ȿ�����
$sql = "SELECT COUNT(*) AS comment_num, to_char(blo_date, 'YYYYMM') AS ym"
	. " FROM t_blog_comment LEFT JOIN t_blog_article ON blo_article_id=bla_article_id LEFT JOIN t_navi_page ON bla_blog_id=nvp_blog_id"
	. " WHERE bla_date>='$year-01-01' AND bla_date<'$year2-01-01' $where"
	. " GROUP BY ym";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0;$i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->ym]->comment_num = $fetch->comment_num;
}

// UU����PV�������
$graph3 = new monthly_graph_class($year, "day.php?navi_page_id=$navi_page_id", '�����UU/PV�����פ�ɽ��');

$graph3->set_row(0, '������UU��', 1);
$graph3->set_row(1, '������PV��', 2);

// ����ǯ�η���PV/UU��
$sql = "SELECT COUNT(*) AS pv_num, COUNT(DISTINCT pvl_ip_addr) AS uu_num, to_char(pvl_date, 'YYYYMM') AS ym"
	. " FROM l_page_view"
	. " LEFT JOIN t_blog_article ON pvl_pv_id=bla_pv_id"
	. " LEFT JOIN t_navi_page ON bla_blog_id=nvp_blog_id"
	. " LEFT JOIN t_blog_trackback ON bla_article_id=blt_article_id AND blt_pv_id=pvl_pv_id"
	. " LEFT JOIN t_blog_comment ON bla_article_id=blo_article_id AND blo_pv_id=pvl_pv_id"
	. " WHERE pvl_date>='$year-01-01' AND pvl_date<'$year2-01-01' $where"
	. " GROUP BY ym";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0;$i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->ym]->pv_num = $fetch->pv_num;
	$ary[$fetch->ym]->uu_num = $fetch->uu_num;
}


// ���󥱡��ȿ������󥱡��Ȳ����������
$graph4 = new monthly_graph_class($year, "day.php?navi_page_id=$navi_page_id", '����Υ��󥱡���/���󥱡��Ȳ��������פ�ɽ��');

$graph4->set_row(0, '���󥱡��ȿ�', 1);
$graph4->set_row(1, '���󥱡��Ȳ�����', 2);

// ����ǯ�η��̥��󥱡��ȿ�����
$sql = "SELECT COUNT(*) AS enquete_num, SUM(nea_answer_count) AS answer_num, to_char(nen_date, 'YYYYMM') AS ym"
	. " FROM t_navi_enquete LEFT JOIN t_navi_enq_answer ON nen_enquete_id=nea_enquete_id LEFT JOIN t_navi_page ON nen_navi_page_id=nvp_navi_page_id"
	. " WHERE nen_date>='$year-01-01' AND nen_date<'$year2-01-01' $where"
	. " GROUP BY ym";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0;$i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->ym]->enquete_num = $fetch->enquete_num;
	$ary[$fetch->ym]->answer_num = $fetch->answer_num;
}


// ���ޥ�ȯ�Կ������ޥ��ɼԿ������
$graph5 = new monthly_graph_class($year, "day.php?navi_page_id=$navi_page_id", '����Υ��ޥ�ȯ��/���ޥ��ɼԿ����פ�ɽ��');

$graph5->set_row(0, '�����ޥ�ȯ�Կ�', 1);
$graph5->set_row(1, '���ޥ��ɼԿ�', 2);

$sql = "SELECT COUNT(*) AS melmaga_num, to_char(mmc_send_date, 'YYYYMM') AS ym"
	. " FROM t_melmaga_contents LEFT JOIN t_navi_page ON mmc_melmaga_id=nvp_melmaga_id"
	. " WHERE mmc_send_date>='$year-01-01' AND mmc_send_date<'$year2-01-01' $where"
	. " GROUP BY ym";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0;$i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->ym]->melmaga_num = $fetch->melmaga_num;
}

// Blog���ȵ��������Ȥ��ѻ�
$current_ym = date('Ym');
$y = $year;
$m = 1;
for ($i = 1; $i <=24; $i++) {
	$ym = sprintf('%04d%02d', $y, $m);

	if ($ym > $current_ym)
		break;

	$graph1->set_data(0, $ym, $ary[$ym]->blog_num);
	$graph1->set_data(1, $ym, $ary[$ym]->article_num);

	$graph2->set_data(0, $ym, $ary[$ym]->trackback_num);
	$graph2->set_data(1, $ym, $ary[$ym]->comment_num);

	$graph3->set_data(0, $ym, $ary[$ym]->uu_num);
	$graph3->set_data(1, $ym, $ary[$ym]->pv_num);

	$graph4->set_data(0, $ym, $ary[$ym]->enquete_num);
	$graph4->set_data(1, $ym, $ary[$ym]->answer_num);

	$graph5->set_data(0, $ym, $ary[$ym]->melmaga_num);
	$graph5->set_data(1, $ym, $ary[$ym]->melmaga_member_num);

	if (++$m > 12) {
		$y++;
		$m = 1;
	}
}

// �������ݡ��Ƚ���
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("blog_month_$year.csv");
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
          <td>
<?php $graph1->disp_legend(); ?>
          </td>
          <td align="right">
            <input type="submit" name="prev_year" value="��">
            <select name="year" onChange="document.graph.submit()">
<?=select_year(min(2005, $year), '', $year)?>
            </select>
            <input type="submit" name="next_year" value="��">
            <select name="navi_page_id" onchange="submit()"><?=select_navi_page_title('���ҤȤӤȡ�net����', $navi_page_id)?></select>
            <input type="submit" name="export" value="�������ݡ���">
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
<hr class="headfood">
<table width="100%" border=0 cellspacing=0 cellpadding=0>
  <tr>
    <td></td>
    <td>
      <table width="100%" border=0 cellspacing=0 cellpadding=0>
        <tr>
          <td>
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
          <td>
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
