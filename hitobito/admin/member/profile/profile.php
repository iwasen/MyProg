<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ���С��ץ�ե�����ڡ���
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
include("$inc/list.php");
include("$inc/format.php");
include("$inc/decode.php");
include("$inc/csv.php");
include("$inc/select.php");

// �ڡ����إå���������
$header = new header_class('���С��ץ�ե�����', BACK_TOP);

// ���ϥѥ�᡼��
$year = $_REQUEST['year'];
$month = $_POST['month'];
$room_id = $_REQUEST['room_id'];

// �ǥե���Ȥ�ǯ������
if (!$year)
	$year = date('Y');

// �ǥե���Ȥη������
if (!$month)
	$month = date('m');

// ��ǯ
if (isset($_POST['prev_month'])) {
	if (--$month < 1) {
		$year--;
		$month = 12;
	}
}

// ��ǯ
if (isset($_POST['next_month'])) {
	if (++$month > 12) {
		$year++;
		$month = 1;
	}
}


// �ꥹ�Ƚ������饹����
$list1 = new list_class('small');

// ��ļ�����
$add_where = $room_id ? " AND rmm_room_id=$room_id" : '';

// ǯ�����
$next_month = mktime(0, 0, 0, $month + 1, 1, $year);
$base_date = date('Y-m-d', $next_month);
$add_case = $month ? ' AND rmm_guest_date IS NOT NULL AND rmm_guest_date < ' . sql_date($base_date)
		 . ' AND (rmm_resign_date IS NULL OR rmm_resign_date >= ' . sql_date($base_date) .')': '';

// ǯ���̥ꥹ�ȥإå�����
$list1->set_header('ǯ����','left', '');
$list1->set_header_group('�磻�磻��ļ�',3);
$list1->set_header('����', 'right', '');
$list1->set_header('����', 'right', '');
$list1->set_header('���', 'right', '');

$sql = "SELECT DATE_PART('Y', age(mbr_birthday)) AS member_age"
	. ", CASE WHEN mbr_sex_cd=1 $add_case THEN 1 END AS ml_male"
	. ", CASE WHEN mbr_sex_cd=2 $add_case THEN 1 END AS ml_female"
	. " FROM t_room_member"
	. " LEFT JOIN t_member ON rmm_member_id=mbr_member_id"
	. " WHERE mbr_status=1" . $add_where
	. " ORDER BY member_age";
$result = db_exec($sql);
$nrow = pg_numrows($result);

$j = 0;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$age = $fetch->member_age;

	if ($age <= 9)
		$j = 0;
	elseif ($age >= 80)
		$j = 15;
	else
		$j = floor($age / 5) - 1;

	$ag_ml_male[$j] += $fetch->ml_male;
	$ag_ml_female[$j] += $fetch->ml_female;

	$ag_ml_male_total += $fetch->ml_male;
	$ag_ml_female_total += $fetch->ml_female;
}


$n1 = '';
$n2 = 9;
for ($i = 0; $i <= 15; $i++) {
	if ($n2 > 80)
		$n2 = '';

	$list1->set_data("$n1 - $n2");
	$list1->set_data(number_format($ag_ml_male[$i]));
	$list1->set_data(number_format($ag_ml_female[$i]));
	$list1->set_data(number_format($ag_ml_male[$i] + $ag_ml_female[$i]));

	$n1 = $n2 + 1;
	$n2 = $n2 + 5;
}
$list1->set_data('��');
$list1->set_data(number_format($ag_ml_male_total));
$list1->set_data(number_format($ag_ml_female_total));
$list1->set_data(number_format($ag_ml_male_total + $ag_ml_female_total));


// �ꥹ�Ƚ������饹����
$list2 = new list_class('small');

// ��ƻ�ܸ��̥ꥹ�Ƚ������饹����
$list3 = new list_class('small');

// �ꥹ�ȥإå�����
$list3->set_header('��ƻ�ܸ���','left', '');
$list3->set_header_group('�磻�磻��ļ�',3);
$list3->set_header('����', 'right', '');
$list3->set_header('����', 'right', '');
$list3->set_header('���', 'right', '');

$sb1 = "SELECT mbr_address1"
	. ", SUM(CASE WHEN mbr_sex_cd = 1 $add_case THEN 1 END) AS ml_male"
	. ", SUM(CASE WHEN mbr_sex_cd = 2 $add_case THEN 1 END) AS ml_female"
	. " FROM t_room_member"
	. " LEFT JOIN t_member ON rmm_member_id=mbr_member_id"
	. " WHERE mbr_status=1" . $add_where
	. " GROUP BY mbr_address1";
$sql = "SELECT tdf_name,sb1.*"
	. " FROM m_todofuken"
	. " LEFT JOIN ($sb1) sb1 ON mbr_address1=tdf_area_id"
	. " ORDER BY tdf_area_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list3->set_data($fetch->tdf_name);
	$list3->set_data(number_format($fetch->ml_male));
	$list3->set_data(number_format($fetch->ml_female));
	$list3->set_data(number_format($fetch->ml_male + $fetch->ml_female));

	$ad_ml_male += $fetch->ml_male;
	$ad_ml_female += $fetch->ml_female;
}
$list3->set_data('��');
$list3->set_data($ad_ml_male);
$list3->set_data($ad_ml_female);
$list3->set_data(number_format($ad_ml_male + $ad_ml_female));

// �������ݡ��Ƚ���
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("navi_profile.csv");
	$csv->export_data($list1);
	$csv->output_newline();
	$csv->export_data($list2);
	$csv->output_newline();
	$csv->export_data($list3);
	exit;
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body>
<?php $header->page_header(); ?>
<form name="form1" method="post">
<table width="100%" border=0 cellspacing=0 cellpadding=0>
  <tr>
    <td align="right">
			<nobr>
			<select name="year" onChange="submit()">
<?=select_year(min(2005, $year), '', $year)?>
			</select>
			<input type="submit" name="prev_month" value="��">
			<select name="month" onChange="submit()">
<?=select_month('', $month)?>
			</select>
			<input type="submit" name="next_month" value="��">
      <select name="room_id" onchange="submit()"><?=select_room('���ҤȤӤȡ�net����', $room_id)?></select>
      <input type="submit" name="export" value="�������ݡ���">
			</nobr>
    </td>
  </tr>
</table>
<table width="100%" border=0>
	<tr>
		<td width="50%" valign="top">
<?php $list1->disp_list(); ?>
<br>
<?php $list2->disp_list(); ?>
		</td>
		<td width="50%" valign="top">
<?php $list3->disp_list(); ?>
	</tr>
</table>
</form>
<?php $header->page_footer(); ?>

</body>
</html>
