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

$start_date = $s_year . sprintf("%02d", $s_month) . sprintf("%02d", $s_day) . "00:00:00";
$end_date = $e_year . sprintf("%02d", $e_month) . sprintf("%02d", $e_day) . "23:59:59";

$sql = "SELECT LB.index_id"
	. " FROM L_BACKNUMBER LB, M_MAGAZINE MM"
	. " WHERE LB.mag_id=MM.mag_id AND LB.modefy_dt BETWEEN to_timestamp('$start_date', 'YYYYMMDD HH24:MI:SS') AND to_timestamp('$end_date', 'YYYYMMDD HH24:MI:SS')"
	. " AND MM.mag_pub_stop_flg<>'02' AND MM.mag_pub_status_flg<>'99' AND MM.mag_status_flg<>'99' AND MM.publisher_id<>0";
$result = db_exec($sql);
$nrow = pg_numrows($result);

if ($nrow > 0) {
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$index_id = $fetch->index_id;
		$v[$i] = $index_id;
	}
} else {
	header ("location: ../admin/err_msg.php?id=0606");
	exit;
}

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
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form style="margin:0" method="post" name="form1">
	<table border=0 cellspacing=0 cellpadding=0 width='100%'>
		<tr>
			<td>■ランダム抽出【<?= $s_year . "/" . $s_month . "/" . $s_day . " 〜 " . $e_year . "/" . $e_month . "/" . $e_day . " : " . $mag_count . "件" ?>】　<font size=-1>※メルマガに警告/フラグ解除する場合は、statusをクリックしてください。</font></td>
			<td align="right"><input type="button" value=" 戻る " onclick="location.href='../hinsitsu/index.php'"><br>
			</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=3 width='100%'>
		<tr bgcolor="#ffcc99">
			<th align="center"><nobr>status</nobr></th>
			<th align="center"><nobr>status更新日</nobr></th>
			<th align="center"><nobr>メルマガID</nobr></th>
			<th align="center"><nobr>メルマガ名</nobr></th>
			<th align="center"><nobr>Sunbect</nobr></th>
			<th align="center"><nobr>発行者</nobr></th>
			<th align="center"><nobr>発行部数</nobr></td>	
		</tr>
<?php
mt_srand((double)microtime()*1000000);
for ($i = $nrow - 1; $i > 0; $i--) {
	$j = mt_rand(0, $i);
	$t = $v[$i];  $v[$i] = $v[$j];  $v[$j] = $t;
}

for ($i = 0; $i < $mag_count -1 && $i < $nrow - 1  ; $i++) {
	$id .= $v[$i] . ",";
}
$id .= $v[$i];

$sql = "select LB.index_id,LB.mag_volume, LB.mag_titl, MM.mag_id, MM.mag_nm,MM.mag_pub_stop_flg,MM.mag_pub_status_flg,"
	. "to_char(MM.MAG_PUB_STOP_DT,'yyyy/mm/dd') as mag_pub_stop_dt ,MM.publisher_id, MP.owner_nm_kanj"
	. " FROM L_BACKNUMBER LB,M_MAGAZINE MM, M_PUBLISHER MP"
	. " WHERE LB.index_id IN (" . $id . ") and LB.mag_id = MM.mag_id and MM.publisher_id=MP.publisher_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);

for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$index_id = $fetch->index_id;
	$mag_volume = $fetch->mag_volume;
	$mag_titl = $fetch->mag_titl;
	$mag_id = $fetch->mag_id;
	$mag_nm = $fetch->mag_nm;
	$owner_nm_kanj = $fetch->owner_nm_kanj;
	$publisher_id = $fetch->publisher_id;
	$mag_pub_status_flg = $fetch->mag_pub_status_flg;
	$mag_pub_stop_flg = $fetch->mag_pub_stop_flg;
	$mag_pub_stop_dt = $fetch->mag_pub_stop_dt;
	$publisher_id = $fetch->publisher_id;
	if ($mag_pub_stop_flg == '00') { $stop_flg="<a href='hinsitsu5.php?mag_id=" . $mag_id ."'>OK</a>"; }
	if ($mag_pub_stop_flg == '01') { $stop_flg="<a href='hinsitsu5.php?mag_id=" . $mag_id ."'><font color=\"orange\">Y</font></a>"; }
	if ($mag_pub_stop_flg == '02') { $stop_flg="<a href='hinsitsu5.php?mag_id=" . $mag_id ."'><font color=\"red\">R</font></a>"; }
	if ($mag_pub_status_flg == '02') { $status_flg="非承認"; }
	if ($mag_pub_status_flg <> '02') { $status_flg=""; }
	if ($mag_pub_stop_dt == "") { $mag_pub_stop_dt="-"; }
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center" title="status変更処理をします"><?= $stop_flg ?><?= $status_flg ?></td>
			<td align="center"><?= $mag_pub_stop_dt ?></td>
			<td align="center"><?= $mag_id ?></td>
			<td title="メルマガの詳細を表示します"><a href='JavaScript:OnClick_mag(<?= $mag_id ?>)'><?= htmlspecialchars($mag_nm) ?></a></td>
			<td title="メルマガの内容を表示します"><a href='JavaScript:OnClick_honbun(<?= $index_id ?>)'><?= htmlspecialchars($mag_titl) ?></a></td>
			<td title="発行者の詳細を表示します"><a href='JavaScript:OnClick_pub("<?= $publisher_id ?>")'><?= htmlspecialchars($owner_nm_kanj) ?></a></td>
			<td align="right"><?= number_format($mag_volume) ?></td>
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
