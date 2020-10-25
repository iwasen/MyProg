<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:サーバ停止期間設定
'******************************************************/

$title_text = $TT_server_mainte;
$title_color = $TC_OTHER;

//配信予約件数表示
function info_count($seq_no,$mag_count) {
	if ($mag_count) {
		print "<a href='haishin_edit.php?seq_no=".$seq_no."'>".$mag_count."</a>";
	} else {
		print "該当なし";
	}
}

//'=== Main ===
$sub = "SELECT count(*) FROM t_pub_reserve WHERE resrv_hour BETWEEN sp_start_date AND sp_end_date and resrv_flg='0'";
$sql = "SELECT (".$sub.") AS mag_count,sp_seq_no, TO_CHAR(sp_start_date,'yyyy/mm/dd hh24:MI') AS sp_start_date, TO_CHAR(sp_end_date,'yyyy/mm/dd hh24:MI') AS sp_end_date FROM t_stop_period ORDER BY sp_seq_no DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
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
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td>■予約禁止設定一覧</td>
			<td align="right">
				<input type="button" value="新規登録" onclick="location.href='new.php'">
				<input type="button" value=" 戻る " onclick="location.href='../menu.php'">
			</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=1 width='80%'>
		<tr bgcolor="#ffcc99">
			<th align="center"><nobr>SEQ</nobr></th>
			<th align="center"><nobr>開始日時</nobr></th>
			<th align="center"><nobr>終了日時</nobr></th>
			<th align="center"><nobr>配信予約</nobr></th>
		</tr>
<?php
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$seq_no = $fetch->sp_seq_no;
	$start_date = $fetch->sp_start_date;
	$end_date = $fetch->sp_end_date;
	$mag_count = $fetch->mag_count;
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><a href='edit.php?seq_no=<?= $seq_no ?>'><?= $seq_no ?></a></td>
			<td align="center"><?= $start_date ?></td>
			<td align="center"><nobr><?= $end_date ?></nobr></td>
			<td align="center"><?= info_count($seq_no,$mag_count) ?></td>
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
