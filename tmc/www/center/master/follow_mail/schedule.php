<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:����ե����᡼���ۿ��������塼��
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/select.php");

// ʬ����
function select_minute($selected) {
	for ($i = 0; $i < 60; $i += 10)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// ���ϥѥ�᡼��
$year_from = $_POST['year_from'];
$month_from = $_POST['month_from'];
$year_to = $_POST['year_to'];
$month_to = $_POST['month_to'];

// �������ÿ�
define('DAY_SEC', 24*60*60);

// ��Ͽ�Ѥ��ۿ�ͽ��������
$sql = "SELECT to_char(fld_send_date, 'YYYY/MM/DD') AS ymd,to_char(fld_send_date, 'HH24') AS hour,to_char(fld_send_date, 'MI') AS minute FROM t_follow_date ORDER BY fld_send_date";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
$send_date = array();
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$send_date[] = $fetch->ymd;
	if ($i == 0) {
		$hour = (int)$fetch->hour;
		$minute = (int)$fetch->minute;
	}
}

if (!$year_from) {
	if ($nrow > 0) {
		$ary = explode('/', $send_date[0]);
		$year_from = $ary[0];
		$month_from = $ary[1];

		$ary = explode('/', $send_date[$nrow - 1]);
		$year_to = $ary[0];
		$month_to = $ary[1];
	} else {
		$year_from = '2003';
		$month_from = '09';

		$year_to = '2004';
		$month_to = '09';
	}
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	return confirm("����ե����᡼����ۿ�������Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>

<? center_header('�ޥ������ƥʥ󥹡ý���ե����᡼����ۿ�������') ?>

<form method="post" name="form1" action="schedule_update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<tr>
		<td>������ե����᡼����ۿ����������ꤷ�Ƥ���������</td>
		<td align="right">
			<input type="submit" value="����Ͽ��">
			<input type="button" value="����롡" onclick="location.href='list.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2>
			�������
			<select name="year_from"><? select_year(2003, '', $year_from) ?></select>ǯ
			<select name="month_from"><? select_month('', $month_from) ?></select>��
			��
			<select name="year_to"><? select_year(2003, '', $year_to) ?></select>ǯ
			<select name="month_to"><? select_month('', $month_to) ?></select>��
			<input type="button" value="�ѹ�" onclick="document.form1.action='';document.form1.submit()">
		</td>
	</tr>
	<tr>
		<td colspan=2>
			�ۿ�����
			<select name="hour"><? select_hour('', $hour) ?></select>��
			<select name="minute"><? select_minute($minute) ?></select>ʬ
		</td>
	</tr>
</table>

<?
$start_year = (int)$year_from;
$start_month = (int)$month_from;
$end_year = (int)$year_to;
$end_month = (int)$month_to;

$year = $start_year;
$month = $start_month;

while (sprintf('%04d%02d', $year, $month) <= sprintf('%04d%02d', $end_year, $end_month)) {
?>
<br><b><?=$year?>ǯ <?=$month?>��</b><br>
<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
		<th width="10%" bgcolor="#ffdddd"><font color="red">��</font></th>
		<th width="10%" bgcolor="#dddddd">��</th>
		<th width="10%" bgcolor="#dddddd">��</th>
		<th width="10%" bgcolor="#dddddd">��</th>
		<th width="10%" bgcolor="#dddddd">��</th>
		<th width="10%" bgcolor="#dddddd">��</th>
		<th width="10%" bgcolor="#ddddff"><font color="blue">��</font></th>
	</tr>
<?
	$d = strtotime("$year/$month/1");
	$st = $d - date('w', $d) * DAY_SEC;

	$d = strtotime("$year/" . ($month + 1) . "/1") - DAY_SEC;
	$ed = $d + (6 - date('w', $d)) * DAY_SEC;

	$d = $st;
	while ($d <= $ed) {
		$week = date('w', $d);
		if ($week == 0) {
?>
		<tr>
<?
		}

?>
			<td width="10%" align="center">
<?

		if (date('m', $d) == $month) {
			switch ($week) {
			case 0:
				$color = 'red';
				break;
			case 6:
				$color = 'blue';
				break;
			default:
				$color = 'black';
				break;
			}
			$day = (int)date('d', $d);
			$date = sprintf('%04d/%02d/%02d', $year, $month, $day);
?>
				<input type="checkbox" name="date[]" <?=value($date)?> <?=checked(in_array($date, $send_date))?>><a href="schedule_jogai.php?date=<?=$date?>"><font color="<?=$color?>"><b><?=$day?></b></font></a>
<?
		} else {
?>
				<font color="gray"><?=(int)date('d', $d)?></font>
<?
		}
?>
			</td>
<?
		if ($week == 6) {
?>
		</tr>
<?
		}
		$d += DAY_SEC;
	}
?>
</table>
<?
	if (++$month > 12) {
		$month = 1;
		$year++;
	}
}
?>
</form>

<? center_footer() ?>

</body>
</html>
