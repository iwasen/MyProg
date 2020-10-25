<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ���С���ܡʷ��̡˥ڡ���
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
$header = new header_class('���С������Խ��סʷ����', BACK_TOP);

// ���ϥѥ�᡼��
$year = $_REQUEST['year'];
$room_id = $_REQUEST['room_id'];

// �ǥե���Ȥ�ǯ������
if (!$year)
	$year = date('Y') - 1;

// ��ǯ
if (isset($_REQUEST['prev_year']))
	$year--;

// ��ǯ
if (isset($_REQUEST['next_year']))
	$year++;

// ���С������ؤ���Ͽ�Կ������
$graph1 = new monthly_graph_class($year, "day.php?room_id=$room_id", '����������Խ��פ�ɽ��');
$graph1->set_row(0, '���С���', 1);
$graph1->set_row(1, '�ؤ���Ͽ�Կ�', 2);

// ����Կ������Կ������
$graph2 = new monthly_graph_class($year, "day.php?room_id=$room_id", '����������Խ��פ�ɽ��');
$graph2->set_row(0, '��������Կ�', 1);
$graph2->set_row(1, '�������Կ�', 2);

// ����ǯ�η��̥��С������ؤ���Ͽ�Կ������
$year2 = $year + 2;
$where = $room_id ? "AND day_navi_page_id=(SELECT nvp_navi_page_id FROM t_navi_page WHERE nvp_room_id=$room_id)" : 'AND day_navi_page_id=0';
$cond_date = "SELECT MAX(day_date) FROM t_daily_count WHERE day_date >= '$year-01-01' AND day_date < '$year2-01-01' GROUP BY to_char(day_date, 'YYYYMM')";
$sql = "SELECT day_member_num AS member_num, day_letter_num AS letter_num, to_char(day_date, 'YYYYMM') AS ym"
	. " FROM t_daily_count"
	. " WHERE day_date IN ($cond_date) $where";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$fetch->ym]->member_num = $fetch->member_num;
	$ary[$fetch->ym]->letter_num = $fetch->letter_num;
}

// ����ǯ�η�������Կ������
$where = $room_id ? "AND rmm_room_id=" . sql_number($room_id) : '';
$sql = "SELECT COUNT(*) as member_num, to_char(rmm_regist_date, 'YYYYMM') as ym"
		. " FROM t_room_member"
		. " JOIN t_member ON mbr_member_id=rmm_member_id"
		. " WHERE rmm_regist_date>='$year-01-01' AND rmm_regist_date<'$year2-01-01' $where"
		. " GROUP BY ym";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$ary[$fetch->ym]->regist_member_num = $fetch->member_num;
}

// ����ǯ�η������Կ������
$sql = "SELECT COUNT(*) as member_num, to_char(rmm_resign_date, 'YYYYMM') as ym"
		. " FROM t_room_member"
		. " JOIN t_member ON mbr_member_id=rmm_member_id"
		. " WHERE rmm_resign_date>='$year-01-01' AND rmm_resign_date<'$year2-01-01' $where"
		. " GROUP BY ym";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$ary[$fetch->ym]->resign_member_num = $fetch->member_num;
}

// ���С������ؤ���Ͽ�Կ��������ѻ�
$current_ym = date('Ym');
$y = $year;
$m = 1;
for ($i = 1; $i <= 24; $i++) {
	$ym = sprintf('%04d%02d', $y, $m);

	if ($ym > $current_ym)
		break;

	$graph1->set_data(0, $ym, $ary[$ym]->member_num);
	$graph1->set_data(1, $ym, $ary[$ym]->letter_num);

	$graph2->set_data(0, $ym, $ary[$ym]->regist_member_num);
	$graph2->set_data(1, $ym, $ary[$ym]->resign_member_num);

	if (++$m > 12) {
		$y++;
		$m = 1;
	}
}

// �������ݡ��Ƚ���
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("member_month_$year.csv");
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
</form>

<?php $header->page_footer(); ?>

</body>
</html>
