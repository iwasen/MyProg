<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�������塼��ɽ��
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

define('DAY_SEC', 24*60*60);

set_global2('proenquete', '�У�異�󥱡��ȴ���', '�������塼��', BACK_TOP);

$today = getdate();

if (!isset($year))
	$year = $today['year'];

if (!isset($month))
	$month = $today['mon'];

$d = strtotime("$year/$month/1");
$st = $d - date('w', $d) * DAY_SEC;

$d = strtotime("$year/" . ($month + 1) . "/1");
$ed = $d + (6 - date('w', $d)) * DAY_SEC;

/*
$sql = "SELECT jb_job_id,jb_send_status,jb_start_date,jb_send_num"
		. " FROM t_job"
		. " WHERE jb_start_date BETWEEN " . sql_date(date('Y/m/d', $st)) . " AND " . sql_date(date('Y/m/d', $ed))
		. " ORDER BY jb_start_date,jb_job_id";
*/
$sql = "SELECT el_enquete_id AS id, el_add_send_no AS send_no, to_timestamp(el_send_date, 'YYYY.MM.DD hh24:mi:ss') AS send_date, count(*) as send_num"
		. " FROM t_enquete_list"
		. " INNER JOIN t_enquete on en_enquete_id = el_enquete_id"
		. " WHERE en_enq_kind in (5, 6)"
		. " AND el_send_date BETWEEN " . sql_date(date('Y/m/d', $st)) . " AND " . sql_date(date('Y/m/d', $ed))
		. " GROUP BY send_date, id, send_no";


echo($sql);
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<form method="get" name="form1">
	<tr>
		<td>
			<select name="year" onchange="submit()"><? select_year(2002, '', $year) ?></select>ǯ
			<select name="month" onchange="submit()"><? select_month('', $month) ?></select>��
			�У�異�󥱡���ȯ��������
		</td>
		<td align="right">
			<input type="button" value="����롡" onclick="location.href='../../index.php'">
		</td>
	</tr>
	</form>
</table>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
		<th width="10%"><font color="red">��</font></th>
		<th width="10%">��</th>
		<th width="10%">��</th>
		<th width="10%">��</th>
		<th width="10%">��</th>
		<th width="10%">��</th>
		<th width="10%"><font color="blue">��</font></th>
	</tr>
<?
$d = $st;
$i = 0;
while ($d <= $ed) {
	$week = date('w', $d);
	if ($week == 0)
		echo "<tr>\n";

	$job = '';
	$sum = 0;
	if ($i < $nrow) {
		for ( ; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			
			
			$sql = "SELECT * FROM t_job WHERE jb_enquete_id = $fetch->id";
			$result2 = db_exec($sql);
			$enq_fetch = pg_fetch_object($result2, 0);
			

			$date = sql_time($fetch->send_date);
			$ymd = strtotime(date('Y', $date) . '/' . date('m', $date) . '/' . date('d', $date));
			if ($ymd != $d)
				break;

			switch ($fetch->send_no) {
			case 0:
				$color = 'blue';
				break;
			case 1:
				$color = 'green';
				break;
			case 2:
				$color = "red";
				break;
			default:
				$color = 'black';
				break;
			}

			$job .= "<tr>\n";
			$job .= "<td align='center'><a href='../job/jb_contents.php?job_id={$enq_fetch->jb_job_id}'>$fetch->id</font></td>\n";
			$job .= "<td align='center'>" . date('H', $date) . ":" . date('i', $date) . "</td>\n";
			$job .= "<td align='right'>" . number_format($fetch->send_num) . "</td>\n";
			$job .= "</tr>\n";
			$sum += $fetch->send_num;
		}
	}

	if ($job == '')
		$job = "<tr><td colspan=3><br></td></tr>\n";

	if ($sum == 0)
		$sum = '';
	else
		$sum = number_format($sum);

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
	} else
		$color = 'gray';

	echo "<td width='10%' valign='top'>\n";
	echo "<table border=0 cellpadding=1 cellspacing=0 width='100%'>\n";
	if (date('Ymd') == date('Ymd', $d))
		echo "<tr bgcolor='#bbccff'>";
	else
		echo "<tr class='tc0'>";
	echo "<td align='center' width='10%'><font size='+1' color='$color'><b>" . (int)date('d', $d) . "</b></font></td>\n";
	echo "<td align='right' colspan=2><font color='#008800'>$sum</font></td></tr>\n";
	echo $job;
	echo "</table>\n";
	echo "</td>\n";

	if ($week == 6)
		echo "</tr>";

	$d += DAY_SEC;
}
?>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
