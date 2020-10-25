<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:本登録承認処理
'******************************************************/

$title_text = $TT_hon_touroku;
$title_color = $TC_HAKKOUSHA;

$sql = "SELECT MAG_ID,MAG_NM,PUBLISHER_ID,to_char(MAG_PUB_STATUS_DT, 'yyyy/mm/dd') as MAG_PUB_STATUS_DT";
$sql = $sql . " FROM M_MAGAZINE";
$sql = $sql . " WHERE MAG_ID='$mag_id'";
$result = db_exec($sql);
$nrow = pg_numrows($result);

if ($nrow > 0) {
	$fetch = pg_fetch_object($result, 0);
	$mag_pub_status_dt = $fetch->mag_pub_status_dt;
	$publisher_id = $fetch->publisher_id;
	$mag_id = $fetch->mag_id;
	$mag_nm = $fetch->mag_nm;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_mag_shousai(mag_id) {
  var win;
  win = window.open("../melmaga_detail/mag_detail1.php?mag_id=" + mag_id, "info_mag", "scrollbars=yes,resizable=yes,width=650,height=700");
  win.focus();
}
function OnClick_honbun(index_id,mag_id) {
  var win;
  win = window.open("../melmaga_detail/mag_honbun.php?backnum=" + index_id, "info_mag", "scrollbars=yes,resizable=yes,width=600,height=800");
  win.focus();
}
function OnClick_print(mag_id) {
	location.href = "hon_print.php?mag_id=" + mag_id;
}
function OnClick_mag(mag_id) {
	if (confirm("このメルマガの本登録を承認します。よろしいですか？")) {
	location.href = "hon_touroku3.php?mag_id=ok" + mag_id;
	}
}
function OnClick_mag2(mag_id) {
	if (confirm("このメルマガの本登録承認を拒否します。よろしいですか？")) {
	location.href = "hon_touroku3.php?mag_id=ng" + mag_id;
	}
}
function OnClick_pub(pub_id) {
  var win;
  win = window.open("../melmaga_detail/publisher.php?pub_id=" + pub_id, "info_pub", "scrollbars=yes,resizable=yes,width=600,height=500");
  win.focus();
}
//-->
</SCRIPT>

</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<form method="post" name="form1">
<center>
<table border=0 cellspacing=0 cellpadding=0 width='80%'>
	<tr>
		<td>■本登録承認処理 <font size="-1">(承認/非承認の通知が発行者に送信されます。)</font></td>
		<td align="right">
			<input type="button" value="　印刷　" onclick='OnClick_print("<?= $mag_id ?>")'>
			<input type="button" value="発行者情報" onclick='OnClick_pub("<?= $publisher_id ?>")'>
		</td>
	</tr>
</table>
<table border=1 cellspacing=0 cellpadding=1 width='80%'>
	<tr bgcolor="#ffcc99">
		<th align="center"><nobr>申請日</nobr></th>
		<th align="center"><nobr>メルマガID</nobr></th>
		<th align="center"><nobr>申請メルマガ名</nobr></th>
	</tr>
	<tr>
		<td class="h2" align="center"><nobr><?= $mag_pub_status_dt ?></nobr></td>
		<td class="h2" align="center"><nobr><?= $mag_id ?></nobr></td>
		<td class="h2"><nobr><a href='JavaScript:OnClick_mag_shousai("<?= $mag_id ?>")'><?= htmlspecialchars($mag_nm) ?></a></nobr></td>
	</tr>
<?php
}
?>
</table><br>
<table border=1 cellspacing=0 cellpadding=1 width='80%'>
	<tr bgcolor="#fcc99">
		<th align="center"><nobr>配信日</nobr></th>
		<th align="center"><nobr>配信部数</nobr></th>
		<th align="center"><nobr>タイトル</nobr></th>
	</tr>
<?php
$sql = "SELECT INDEX_ID,MAG_VOLUME,MAG_TITL,to_char(MODEFY_DT, 'yyyy/mm/dd') as MODEFY_DT";
$sql = $sql . " FROM L_BACKNUMBER";
$sql = $sql . " WHERE mag_id = '$mag_id'";
$sql = $sql . " order by index_id DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);

for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$modefy_dt = $fetch->modefy_dt;
	$mag_volume = $fetch->mag_volume;
	$mag_titl = $fetch->mag_titl;
	$index_id = $fetch->index_id;
?>
	<tr>
		<td class="h2" align="center"><nobr><?= $modefy_dt ?></nobr></td>
		<td class="h2" align="right"><nobr><?= $mag_volume ?></nobr></td>
		<td class="h2"><nobr><a href='JavaScript:OnClick_honbun("<?= $fetch->index_id ?>")'><?= htmlspecialchars($mag_titl) ?></a></nobr></td>
	</tr>
<?php
}
?>
</table><br>
<input type="button" value="　承認する　" onclick='OnClick_mag("<?= $mag_id ?>")'>
<input type="button" value="承認しない" onclick='OnClick_mag2("<?= $mag_id ?>")'>
<input type="button" value=" 戻る " onclick="location.href='hon_touroku1.php'">
</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
