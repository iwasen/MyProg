<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:���𥯥�å�����������
'******************************************************/

$title_text = $TT_click;
$title_color = $TC_OTHER;

if ($sel_id) {
	$sub = "select *,date_part('hour', lc_date) as lc_time from l_click_counter";
	$sql = "select count(*) as cnt, lc_time from (".$sub.") as sub where lc_ct_id=".sql_number($sel_id)."group by lc_time order by lc_time";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	if ($nrow) {
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$click_cnt[$fetch->lc_time] = $fetch->cnt;
		}
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" style="margin:0">
	<table border=0 cellspacing=0 cellpadding=0 width=600>
		<tr>
			<td>�������̥���å����� <?= sprintf("%06d", $sel_id)." ".$click_name[$sel_id] ?></td>
			<td align="right"><input type="button" value=" ��� " onclick="history.back()"></td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=1 width=600>
	<tr bgcolor="ffcc99">
		<th align="center"><nobr>����</nobr></th>
		<th align="center"><nobr>����å���</nobr></th>
	</tr>
<?
for ($i = 0; $i < 24; $i++) {
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><nobr><?= $i ?></nobr></td>
			<td align="right"><nobr><?= number_format($click_cnt[$i]) ?></nobr></td>
		</tr>
	<?php
	$line++;
	}
	?>
	</table>
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
