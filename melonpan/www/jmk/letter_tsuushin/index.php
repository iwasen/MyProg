<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:めろんぱん通信配信
'******************************************************/

$title_text = $TT_letter_tsuushin;
$title_color = $TC_MASTER;

//'=== Main ===
$sub = "SELECT COUNT(DISTINCT MP.mail_add_main)"
	. " FROM M_PUBLISHER MP, M_MAGAZINE MM"
	. " WHERE MM.mag_status_flg IN ('00','01') AND MM.mag_pub_status_flg IN ('00','01','03')"
	. " AND MM.mag_pub_stop_flg in ('00','01') AND MP.publisher_id=MM.publisher_id";
$sql = "SELECT (" . $sub . ") as send_cnt, TS_id, TO_CHAR(TS_send_date,'yyyy/mm/dd hh24:mi:ss') as TS_send_date, TS_subject"
	. ", TS_status, TO_CHAR(TS_send_start,'yyyy/mm/dd hh24:mi:ss') AS TS_send_start, TS_send_volume"
	. " FROM T_TSUUSHIN ORDER BY TS_send_date DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_letter(TS_id) {
  var win;
  win = window.open("letter_honbun.php?id=" + TS_id, "info_mag", "scrollbars=yes,resizable=yes,width=600,height=800");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" style="margin:0">
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td>■めろんぱん通信配信</td>
			<td align="right">
				<input type="button" value="新規登録" onclick="location.href='letter_tsuushin1.php'">
				<input type="button" value=" 戻る " onclick="location.href='../menu.php'">
			</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=1 width='80%'>
		<tr bgcolor="#ffcc99">
			<th align="center"><nobr>配信日時</nobr></th>
			<th align="center"><nobr>メルマガタイトル</nobr></th>
			<th align="center"><nobr>配信数</nobr></th>
		</tr>
<?php
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$TS_id = $fetch->ts_id;
	$TS_send_date = $fetch->ts_send_date;
	$TS_subject = $fetch->ts_subject;
	$TS_status = $fetch->ts_status;
	$TS_send_start = $fetch->ts_send_start;
	$TS_send_volume = $fetch->ts_send_volume;
	$send_cnt = $fetch->send_cnt;
	if ($TS_send_start != "") {
		$TS_send_date = $TS_send_start;
	} else {
		$TS_send_date = "<a href='letter_tsuushin2.php?id=" . $TS_id . "'>" . $TS_send_date . "</a>";
	}
	if ($TS_send_volume != "") {
		$send_cnt = $TS_send_volume;
	}
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><?= $TS_send_date ?></td>
			<td><a href='JavaScript:OnClick_letter("<?= $TS_id ?>")'><?= htmlspecialchars($TS_subject) ?></a></td>
			<td align="right"><?= number_format($send_cnt) ?></td>
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
