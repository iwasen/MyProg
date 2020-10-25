<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:広告クリックカウンタ設定
'******************************************************/

$title_text = $TT_click;
$title_color = $TC_OTHER;

if ($sel_id) {
	$sub = "select *,date_trunc('day', lc_date) as cc_date from l_click_counter";
	$sql = "select count(*) as cnt, cc_date from (".$sub.") as sub where lc_ct_id=".sql_number($sel_id)."group by cc_date order by cc_date";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
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
			<td>■日付別クリック数： <?= sprintf("%06d", $sel_id)." ".$click_name[$sel_id] ?></td>
			<td align="right"><input type="button" value=" 戻る " onclick="history.back()"></td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=1 width=600>
	<tr bgcolor="ffcc99">
		<th align="center"><nobr>時間</nobr></th>
		<th align="center"><nobr>クリック数</nobr></th>
	</tr>
<?
if ($nrow){
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$click_cnt = $fetch->cnt;
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><nobr><?= format_date($fetch->cc_date) ?></nobr></td>
			<td align="right"><nobr><?= number_format($click_cnt) ?></nobr></td>
		</tr>
	<?php
	$line++;
	}
}
	?>
	</table>
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
