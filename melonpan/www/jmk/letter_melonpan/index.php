<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:めろんぱんレター配信
'******************************************************/

$title_text = $TT_letter_melonpan;
$title_color = $TC_MASTER;

//'=== Main ===

$sub = "SELECT count(*) from T_MAILADDR WHERE mag_id='000049'";
$sql = "SELECT (" . $sub . ") as mag_count, R.index_id, R.body_id, R.mag_id, TO_CHAR(R.resrv_hour,'yyyy/mm/dd hh24:mi:ss') AS resrv_hour, T.mag_titl";
$sql .= " FROM T_PUB_RESERVE R, T_BODY T";
$sql .= " WHERE R.mag_id='000049' AND R.resrv_flg = '0' AND R.index_id = T.index_id";
$sql .= " ORDER BY R.resrv_hour";
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
			<td>■めろんぱんレター配信</td>
			<td align="right">
				<input type="button" value="新規登録" onclick="location.href='letter_melonpan1.php'">
				<input type="button" value=" 戻る " onclick="location.href='../menu.php'">
			</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=1 width='80%'>
		<tr bgcolor="#ffcc99">
			<th align="center"><nobr>配信予定日時</nobr></th>
			<th align="center"><nobr>メルマガタイトル</nobr></th>
			<th align="center"><nobr>予定配信数</nobr></th>
		</tr>
<?php
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$mag_id = $fetch->mag_id;
	$index_id = $fetch->index_id;
	$resrv_hour = $fetch->resrv_hour;
	$mag_titl = $fetch->mag_titl;
	$mag_count = $fetch->mag_count;
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><a href='letter_melonpan4.php?index_id=<?= $index_id ?>'><?= $resrv_hour ?></a></td>
			<td><?= htmlspecialchars($mag_titl) ?></td>
			<td align="right"><nobr><?= number_format($mag_count) ?></nobr></td>
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
