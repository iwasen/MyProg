<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ML��ƿ���ܡʷ��̡˥ڡ���
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
$header = new header_class('ML��������ܡʷ����', BACK_TOP);

// ���ϥѥ�᡼��
$year = $_GET['year'];
$room_id = $_GET['room_id'];

// �ǥե���Ȥ�ǯ������
if (!$year)
	$year = date('Y') - 1;

// ��ǯ
if (isset($_GET['prev_year']))
	$year--;

// ��ǯ
if (isset($_GET['next_year']))
	$year++;

// ML�������С��������
$graph1 = new monthly_graph_class($year, "day.php?room_id=$room_id", '�����ML��/���С������פ�ɽ��');
$graph1->set_row(0, '����������ML��', 1);
$graph1->set_row(1, '���С���', 2);

// ����ǯ�η���ML����ML���С��������
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

// ȯ���Կ���ȯ���������
$graph2 = new monthly_graph_class($year, "day.php?room_id=$room_id", '�����ȯ���Կ���ȯ�������פ�ɽ��');
$graph2->set_row(0, '������ȯ���Կ�', 1);
$graph2->set_row(1, '��ȯ����', 2);

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

// ˬ��Կ����ڡ����ӥ塼�������
$graph3 = new monthly_graph_class($year, "day.php?room_id=$room_id", '�����ˬ��Կ����ڡ����ӥ塼�����פ�ɽ��');
$graph3->set_row(0, 'ˬ��Կ�', 1);
$graph3->set_row(1, '�ڡ����ӥ塼��', 2);

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


// ���С������ؤ���Ͽ�Կ��������ѻ�
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

// �������ݡ��Ƚ���
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
            <input type="submit" name="prev_year" value="��">
            <select name="year" onChange="document.graph.submit()">
<?=select_year(min(2005, $year), '', $year)?>
            </select>
            <input type="submit" name="next_year" value="��">
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
