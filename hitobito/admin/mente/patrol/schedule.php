<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ѥȥ����������˥塼�ڡ���
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
include("$inc/date.php");

// ���ϥѥ�᡼��
$year = $_REQUEST['year'];
$month = $_REQUEST['month'];

// �ǥե���Ȥ�ǯ������
if (!$year)
	$year = date('Y');

// �ǥե���Ȥη������
if (!$month)
	$month = date('m');

// ����
if (isset($_REQUEST['prev_month'])) {
	if (--$month < 1) {
		$year--;
		$month = 12;
	}
}

// ���
if (isset($_REQUEST['next_month'])) {
	if (++$month > 12) {
		$year++;
		$month = 1;
	}
}

// �إå���������
$header = new header_class('�������塼��', BACK_TOP);

// �������塼��ǡ����ɤ߹���
$data_ary = array();
$this_month = get_date_from_ymd($year, $month, 1);
$next_month = add_month($this_month, 1);
$sql = sprintf("SELECT * FROM t_schedule WHERE sch_date>=%s AND sch_date<%s", sql_date2($this_month), sql_date2($next_month));
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$date = get_datepart($fetch->sch_date);
	$data = &$data_ary[$date['d']];

	$data->blog_total = $fetch->sch_blog_total;
	$data->blog_nocheck = $fetch->sch_blog_nocheck;
	$data->blog_problem = $fetch->sch_blog_problem;
	$data->blog_pickup = $fetch->sch_blog_pickup;
	$data->ml_total = $fetch->sch_ml_total;
	$data->ml_nocheck = $fetch->sch_ml_nocheck;
	$data->ml_problem = $fetch->sch_ml_problem;
	$data->ml_pickup = $fetch->sch_ml_pickup;
	$data->melmaga_total = $fetch->sch_melmaga_total;
	$data->melmaga_nocheck = $fetch->sch_melmaga_nocheck;
	$data->melmaga_problem = $fetch->sch_melmaga_problem;
	$data->melmaga_pickup = $fetch->sch_melmaga_pickup;
	$data->enquete_total = $fetch->sch_enquete_total;
	$data->enquete_nocheck = $fetch->sch_enquete_nocheck;
	$data->enquete_problem = $fetch->sch_enquete_problem;
	$data->enquete_pickup = $fetch->sch_enquete_pickup;
	$data->link_total = $fetch->sch_link_total;
	$data->link_nocheck = $fetch->sch_link_nocheck;
	$data->link_problem = $fetch->sch_link_problem;
	$data->link_pickup = $fetch->sch_link_pickup;
}

// ��κǽ�����������������
$time = mktime(0, 0, 0, $month, 1 , $year);
$week = date('w', $time);
$max = date('t', $time);

// ���ˤ���Ƭ��
if (--$week < 0)
		$week = 6;

$week_ary = array('��', '��', '��', '��', '��', '��', '��');

// ������������
$cal_ary = array();
for ($i = 0; $i < $max; $i++) {
	$row = (int)($week / 7);
	$col = $week % 7;
	$week++;

	$data = &$cal_ary[$row][$col];

	$day = $i + 1;

	$data = $data_ary[$day];

	$data->date = "{$day}��({$week_ary[$col]})";
	if ($col == 5)
		$data->date = '<font color="#0000FF">' . $data->date . '</font>';
	elseif ($col == 6)
		$data->date = '<font color="#FF0000">' . $data->date . '</font>';
}
?>
<html lang="ja">
<?php $header->html_header(); ?>
</head>
<body>

<?php $header->page_header(); ?>

<form name="graph">

<table align="center" border=0 cellpadding=8 cellspacing=0 width="90%">
  <tr>
    <td>
      <table width="100%" border=1 cellspacing=0 cellpadding=1 bordercolorlight='#f0f0f0' frame='box'>
        <tr>
          <th colspan="7" align="right">
            <select name="year" onChange="document.graph.submit()">
<?=select_year(min(2005, $year), '', $year)?>
            </select>
            <input type="submit" name="prev_month" value="��">
            <select name="month" onChange="document.graph.submit()">
<?=select_month('', $month)?>
            </select>
            <input type="submit" name="next_month" value="��">
          </th>
        </tr>
<?php
foreach ($cal_ary as $row => $row_data) {
?>
        <tr>
<?php
	for ($col = 0; $col < 7; $col++) {
?>
          <th class="head"><?=isset($row_data[$col]) ? $row_data[$col]->date : ' '?></th>
<?php
	}
?>
        </tr>
        <tr>
<?php
	for ($col = 0; $col < 7; $col++) {
?>
          <th>
<?php
		if (isset($row_data[$col])) {
			$data = &$row_data[$col];
?>
            <table width="100%" border="1" cellspacing="0" cellpadding="0">
              <tr>
                <td nowrap align="center"><font size="1"></font></td>
                <td nowrap align="center"><font size="1">��</font></td>
                <td nowrap align="center"><font size="1">̤</font></td>
                <td nowrap align="center"><font size="1">��</font></td>
                <td nowrap align="center"><font size="1">P</font></td>
              </tr>
              <tr>
                <td><font size="1">Blog</font></td>
                <td align="right"><a href="<?=$top?>/blog/article/list.php"><font size="1"><?=number_format($data->blog_total)?></font></a></td>
                <td align="right"><a href="<?=$top?>/blog/article/list.php"><font size="1"><?=number_format($data->blog_nocheck)?></font></a></td>
                <td align="right"><a href="<?=$top?>/blog/article/list.php"><font size="1"><?=number_format($data->blog_problem)?></font></a></td>
                <td align="right"><a href="<?=$top?>/blog/article/list.php"><font size="1"><?=number_format($data->blog_pickup)?></font></a></td>
              </tr>
              <tr>
                <td><font size="1">ML</font></td>
                <td align="right"><a href="<?=$top?>/ml/contribution/list.php"><font size="1"><?=number_format($data->ml_total)?></font></a></td>
                <td align="right"><a href="<?=$top?>/ml/contribution/list.php"><font size="1"><?=number_format($data->ml_nocheck)?></font></a></td>
                <td align="right"><a href="<?=$top?>/ml/contribution/list.php"><font size="1"><?=number_format($data->ml_problem)?></font></a></td>
                <td align="right"><a href="<?=$top?>/ml/contribution/list.php"><font size="1"><?=number_format($data->ml_pickup)?></font></a></td>
              </tr>
              <tr>
                <td><font size="1">���ޥ�</font></td>
                <td align="right"><a href="<?=$top?>/blog/magazine/list.php"><font size="1"><?=number_format($data->melmaga_total)?></font></a></td>
                <td align="right"><a href="<?=$top?>/blog/magazine/list.php"><font size="1"><?=number_format($data->melmaga_nocheck)?></font></a></td>
                <td align="right"><a href="<?=$top?>/blog/magazine/list.php"><font size="1"><?=number_format($data->melmaga_problem)?></font></a></td>
                <td align="right"><a href="<?=$top?>/blog/magazine/list.php"><font size="1"><?=number_format($data->melmaga_pickup)?></font></a></td>
              </tr>
              <tr>
                <td><font size="1">���󥱡���</font></td>
                <td align="right"><a href="<?=$top?>/blog/enquete/list.php"><font size="1"><?=number_format($data->enquete_total)?></font></a></td>
                <td align="right"><a href="<?=$top?>/blog/enquete/list.php"><font size="1"><?=number_format($data->enquete_nocheck)?></font></a></td>
                <td align="right"><a href="<?=$top?>/blog/enquete/list.php"><font size="1"><?=number_format($data->enquete_problem)?></font></a></td>
                <td align="right"><a href="<?=$top?>/blog/enquete/list.php"><font size="1"><?=number_format($data->enquete_pickup)?></font></a></td>
              </tr>
              <tr>
                <td><font size="1">���</font></td>
                <td align="right"><a href="<?=$top?>/blog/link/list.php"><font size="1"><?=number_format($data->link_total)?></font></a></td>
                <td align="right"><a href="<?=$top?>/blog/link/list.php"><font size="1"><?=number_format($data->link_nocheck)?></font></a></td>
                <td align="right"><a href="<?=$top?>/blog/link/list.php"><font size="1"><?=number_format($data->link_problem)?></font></a></td>
                <td align="right"><a href="<?=$top?>/blog/link/list.php"><font size="1"><?=number_format($data->link_pickup)?></font></a></td>
              </tr>
            </table>
<?php
		}
?>
          </th>
<?php
	}
?>
				</tr>
<?php
}
?>
      </table>
      <table border=1 cellspacing=0 cellpadding=0>
        <tr>
          <td>�����</td>
          <td>̤��̤�����å���</td>
          <td>�䡧���ꤢ���</td>
          <td> P���ԥå����å׿�</td>
        </tr>
      </table>
    </td>
  </tr>
</table>

</form>

<?php $header->page_footer(); ?>

</body>
</html>
