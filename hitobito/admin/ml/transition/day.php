<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ML��������ܡ����̡˥ڡ���
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

// �ڡ����إå���������
$header = new header_class('Ml��������ܡ������', BACK_TOP);

// ���ϥѥ�᡼��
$year = $_GET['year'];
$month = $_GET['month'];
$room_id = $_GET['room_id'];

// ����
if (isset($_GET['prev_month'])) {
	if (--$month < 1) {
		$year--;
		$month = 12;
	}
}

// ���
if (isset($_GET['next_month'])) {
	if (++$month > 12) {
		$year++;
		$month = 1;
	}
}

// ML�������С��������
$graph1 = new daily_graph_class($year, $month);
$graph1->set_row(0, '����������ML��', 1);
$graph1->set_row(1, '���С���', 2);

$year2 = $year;
$month2 = $month + 1;
if ($month2 > 12) {
	$year2++;
	$month2 = 1;
}

// ����������ML����ML���С��������
$where = $room_id ? "AND day_navi_page_id=(SELECT nvp_navi_page_id FROM t_navi_page WHERE nvp_room_id=$room_id)" : 'AND day_navi_page_id=0';
$sql = "SELECT day_ml_num AS ml_num, day_member_num AS member_num, date_part('day', day_date) AS day"
	. " FROM t_daily_count"
	. " WHERE day_date >= '$year-$month-01' AND day_date < '$year2-$month2-01' $where";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->day]->room_num = $fetch->ml_num;
	$ary[$fetch->day]->member_num = $fetch->member_num;
}

// ȯ���Կ���ȯ���������
$graph2 = new daily_graph_class($year, $month);
$graph2->set_row(0, '������ȯ���Կ�', 1);
$graph2->set_row(1, '��ȯ����', 2);

$where = $room_id ? "AND rom_room_id=" . sql_number($room_id) : '';
$sql = "SELECT COUNT(*) AS remark_num, COUNT(DISTINCT rmk_member_id) AS remark_member_num, date_part('day', rmk_date) AS day"
	. " FROM l_room_remark LEFT JOIN t_room ON rmk_room_id=rom_room_id"
	. " WHERE rmk_date >= '$year-$month-01' AND rmk_date < '$year2-$month2-01' $where"
	. " GROUP BY day";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0;$i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->day]->remark_member_num = $fetch->remark_member_num;
	$ary[$fetch->day]->remark_num = $fetch->remark_num;
}

// ˬ��Կ����ڡ����ӥ塼�������
$graph3 = new daily_graph_class($year, $month);
$graph3->set_row(0, 'ˬ��Կ�', 1);
$graph3->set_row(1, '�ڡ����ӥ塼��', 2);

$sql = "SELECT COUNT(*) AS pv_num, COUNT(DISTINCT pvl_ip_addr) AS uu_num, date_part('day', pvl_date) AS day"
	. " FROM l_page_view LEFT JOIN l_room_remark ON pvl_pv_id=rmk_pv_id"
	. " LEFT JOIN t_room ON rmk_room_id=rom_room_id"
	. " WHERE pvl_pv_id=rmk_pv_id AND pvl_date >= '$year-$month-01' AND pvl_date < '$year2-$month2-01' $where"
	. " GROUP BY day";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0;$i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->day]->pv_num = $fetch->pv_num;
	$ary[$fetch->day]->uu_num = $fetch->uu_num;
}

// ML����ML���С��������̤˼���
for ($day = 1; $day <= 31; $day++) {
	$graph1->set_data(0, $day, $ary[$day]->room_num);
	$graph1->set_data(1, $day, $ary[$day]->member_num);

	$graph2->set_data(0, $day, $ary[$day]->remark_member_num);
	$graph2->set_data(1, $day, $ary[$day]->remark_num);

	$graph3->set_data(0, $day, $ary[$day]->uu_num);
	$graph3->set_data(1, $day, $ary[$day]->pv_num);

	if (++$m > 12) {
		$y++;
		$m = 1;
	}
}

// �������ݡ��Ƚ���
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("ml_day_{$year}_{$month}.csv");
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
          <td class="m0">
<?php $graph1->disp_legend(); ?>
          </td>
          <td align="right">
            <select name="year" onChange="document.graph.submit()">
<?=select_year(min(2005, $year), '', $year)?>
            </select>
            <input type="submit" name="prev_month" value="��">
            <select name="month" onChange="document.graph.submit()">
<?=select_month('', $month)?>
            </select>
            <input type="submit" name="next_month" value="��">
            <select name="room_id" onchange="submit()"><?=select_room('���ҤȤӤȡ�net����', $room_id)?></select>
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
</form>

<?php $header->page_footer(); ?>

</body>
</html>
