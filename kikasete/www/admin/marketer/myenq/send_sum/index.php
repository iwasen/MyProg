<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｍｙアンケート発信数集計
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

set_global2('myenquete', 'Ｍｙアンケート管理', '発信数集計', BACK_TOP);

$today = getdate();

if (!isset($year))
	$year = $today['year'];

if (!isset($month))
	$month = $today['mon'];

$d = strtotime("$year/$month/1");
$sd = strtotime("$year/$month/1");
$st = $d - date('w', $d) * DAY_SEC;

$d = strtotime("$year/" . ($month + 1) . "/1");
$nsd = $d - DAY_SEC;
$ed = $d + (6 - date('w', $d)) * DAY_SEC;

//年月の集計
$sql = "SELECT 
count(*) AS count
FROM t_enquete_list 
INNER JOIN t_enquete on en_enquete_id = el_enquete_id 
WHERE en_enq_kind =2 
AND el_send_date BETWEEN " . sql_date(date('Y/m/d', $sd)) . " AND " . sql_date(date('Y/m/d', $nsd));
//echo($sql);
$result0 = db_exec($sql);
$nrow0 = pg_numrows($result0);
$fetch0 = pg_fetch_object($result0, 0);
$sum_month = $fetch0->count;


//年月日時分秒単位の集計
$sql = "SELECT el_enquete_id AS id, el_add_send_no AS send_no, to_timestamp(el_send_date, 'YYYY.MM.DD hh24:mi') AS send_date, count(*) as send_num"
		. " FROM t_enquete_list"
		. " INNER JOIN t_enquete ON en_enquete_id = el_enquete_id"
		. " WHERE en_enq_kind = 2"
		. " AND el_send_date BETWEEN " . sql_date(date('Y/m/d', $st)) . " AND " . sql_date(date('Y/m/d', $ed))
		. " GROUP BY send_date, id, send_no"
		. " ORDER BY send_date";
//echo($sql);
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
		<td colspan=2>■Ｍｙアンケート発信数集計</td>
	</tr>
	<tr>
		<td>
			<select name="year" onchange="submit()"><? select_year(2002, '', $year) ?></select>年
			<select name="month" onchange="submit()"><? select_month('', $month) ?></select>月
		</td>
		<td align="right">
			月集計：<?= $sum_month ?>&nbsp;&nbsp;
			<input type="button" value="　戻る　" onclick="location.href='../../../menu.php'">
		</td>
	</tr>
	</form>
</table>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
		<th width="10%"><font color="red">日</font></th>
		<th width="10%">月</th>
		<th width="10%">火</th>
		<th width="10%">水</th>
		<th width="10%">木</th>
		<th width="10%">金</th>
		<th width="10%"><font color="blue">土</font></th>
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
			
			//enquete_idに該当する、marketer_idとmy_enq_noを取得
			$sql = "SELECT * FROM t_my_enquete WHERE me_enquete_id = $fetch->id";
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
			$job .= "<td align='center'><a href='../show.php?marketer_id={$enq_fetch->me_marketer_id}&my_enq_no={$enq_fetch->me_my_enq_no}'>$fetch->id</font></a></td>\n";
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
