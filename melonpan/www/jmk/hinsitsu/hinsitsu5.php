<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:品質チェック
'******************************************************/

$title_text = $TT_hinshitsu;
$title_color = $TC_HAKKOUSHA;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_mag(mag_id) {
  var win;
  win = window.open("../melmaga_detail/mag_detail1.php?mag_id=" + mag_id, "info_mag", "scrollbars=yes,resizable=yes,width=600,height=700");
  win.focus();
}
function OnClick_honbun(index_id) {
  var win;
  win = window.open("../melmaga_detail/mag_honbun.php?backnum=" + index_id, "info_mag", "scrollbars=yes,resizable=yes,width=600,height=800");
  win.focus();
}
function OnClick_pub(pub_id) {
  var win;
  win = window.open("../melmaga_detail/publisher.php?pub_id=" + pub_id, "info_pub", "scrollbars=yes,resizable=yes,width=600,height=500");
  win.focus();
}
function OnClick_yellow(mag_id) {
	if(confirm("このメルマガ発行者に警告を通知します。よろしいですか？")){
		location.href = "hinsitsu6.php?flg=y&mag_id=" + mag_id;
	}
}
function OnClick_red(mag_id) {
	if(confirm("このメルマガ発行者に警告を通知します。よろしいですか？")){
		location.href = "hinsitsu6.php?flg=r&mag_id=" + mag_id;
	}
}
function OnClick_noyellow(mag_id) {
	if(confirm("このメルマガのイエローフラグを取り消します。よろしいですか？")){
		location.href = "hinsitsu7.php?flg=y&mag_id=" + mag_id;
	}
}
function OnClick_nored(mag_id) {
	if(confirm("このメルマガのレッドフラグを取り消します。よろしいですか？")){
		location.href = "hinsitsu7.php?flg=r&mag_id=" + mag_id;
	}
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1">
	<table border=0 cellspacing=0 cellpadding=0 width='90%'>
		<tr>
			<td>■メルマガ過去ログ
			</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=3 width='90%'>
		<tr bgcolor="#ffcc99">
			<th align="center"><nobr>status</nobr></th>
			<th align="center"><nobr>status更新日</nobr></th>
			<th align="center"><nobr>メルマガID</nobr></th>
			<th align="center"><nobr>メルマガ名</nobr></th>
			<th align="center"><nobr>発行者</nobr></th>
		</tr>
<?php
$sql = "select LB.index_id,LB.mag_volume, LB.mag_titl, MM.mag_id, MM.mag_nm,MM.mag_pub_stop_flg,"
	. "to_char(MM.MAG_PUB_STOP_DT,'yyyy/mm/dd') as mag_pub_stop_dt ,MM.publisher_id, MP.owner_nm_kanj"
	. " FROM L_BACKNUMBER LB,M_MAGAZINE MM, M_PUBLISHER MP"
	. " WHERE MM.mag_id='$mag_id' and LB.mag_id = MM.mag_id AND MM.publisher_id=MP.publisher_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$index_id = $fetch->index_id;
	$mag_id = $fetch->mag_id;
	$mag_nm = $fetch->mag_nm;
	$owner_nm_kanj = $fetch->owner_nm_kanj;
	$publisher_id = $fetch->publisher_id;
	$mag_pub_stop_flg = $fetch->mag_pub_stop_flg;
	$mag_pub_stop_dt = $fetch->mag_pub_stop_dt;
	$publisher_id = $fetch->publisher_id;
	if ($mag_pub_stop_flg == '00') { $stop_flg="OK"; }
	if ($mag_pub_stop_flg == '01') { $stop_flg="<font color=\"orange\">Y</font>"; }
	if ($mag_pub_stop_flg == '02') { $stop_flg="<font color=\"red\">R</font>"; }
	if ($mag_pub_status_flg == '02') { $status_flg="非承認"; }
	if ($mag_pub_status_flg <> '02') { $status_flg=""; }
	if ($mag_pub_stop_dt == "") { $mag_pub_stop_dt="-"; }?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><?= $stop_flg ?><?= $status_flg ?></td>
			<td align="center"><?= $mag_pub_stop_dt ?></td>
			<td align="center"><?= $mag_id ?></td>
			<td><a href='JavaScript:OnClick_mag(<?= $mag_id ?>)'><?= htmlspecialchars($mag_nm) ?></a></td>
			<td><a href='JavaScript:OnClick_pub("<?= $publisher_id ?>")'><?= htmlspecialchars($owner_nm_kanj) ?></a></td>
		</tr>
<?php
}

?>

	</table><br>
	<table border=1 cellspacing=0 cellpadding=3 width='80%'>
		<tr bgcolor="#ffcc99">
			<th align="center"><nobr>配信日</nobr></th>
			<th align="center"><nobr>Sunbect</nobr></th>
			<th align="center"><nobr>発行部数</nobr></th>	
		</tr>
<?php
$line = 0;
$sql = "select LB.index_id,LB.mag_volume, LB.mag_titl, MM.mag_id, MM.mag_nm,MM.mag_pub_stop_flg,"
	. "to_char(MM.MAG_PUB_STOP_DT,'yyyy/mm/dd') as mag_pub_stop_dt ,MM.publisher_id, MP.owner_nm_kanj, to_char(LB.modefy_dt, 'yyyy/mm/dd') as modefy_dt"
	. " FROM L_BACKNUMBER LB,M_MAGAZINE MM, M_PUBLISHER MP"
	. " WHERE MM.mag_id='$mag_id' and LB.mag_id = MM.mag_id AND MM.publisher_id=MP.publisher_id"
	. " ORDER BY LB.index_id DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$index_id = $fetch->index_id;
	$mag_volume = $fetch->mag_volume;
	$mag_titl = $fetch->mag_titl;
	$mag_id = $fetch->mag_id;
	$mag_nm = $fetch->mag_nm;
	$modefy_dt = $fetch->modefy_dt;
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><?= $modefy_dt ?></td>
			<td><a href='JavaScript:OnClick_honbun(<?= $index_id ?>)'><?= htmlspecialchars($mag_titl) ?></a></td>
			<td align="right"><?= number_format($mag_volume) ?></td>
		</tr>
<?php
$line++;
}

?>
</table><br>
<? if ($mag_pub_stop_flg == '00') { ?>
<input type="button" name="yellow" value="イエローフラグを立てる" onclick='JavaScript:OnClick_yellow("<?= $mag_id ?>")'>
<?
} elseif ($mag_pub_stop_flg == '01'){
?>
<input type="button" name="red" value="レッドフラグ立てる" onclick='JavaScript:OnClick_red("<?= $mag_id ?>")'>
<?
}
if ($mag_pub_stop_flg == '01') {
?>
<input type="button" value="イエローフラグを取り消す" onclick='JavaScript:OnClick_noyellow("<?= $mag_id ?>")'>
<?
} elseif ($mag_pub_stop_flg == '02') {
?>
<input type="button" value="レッドフラグを取り消す" onclick='JavaScript:OnClick_nored("<?= $mag_id ?>")'>
<?
}
?>

<input type="button" value=" 戻る " onclick="history.back()">

</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
