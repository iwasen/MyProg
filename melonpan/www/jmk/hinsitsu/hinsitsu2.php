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

$sql = "SELECT word FROM M_REDWORD WHERE word_id=1";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$word_moto = $fetch->word;
	if (!$word_moto) {
		header ("location: ../admin/err_msg.php?id=0606");
		exit;
	}
}
//$word_moto = replace_single($word_moto);
$words = split("\n",$word_moto);

while (list($key,$val) = each($words)) {
	if (trim($val) == "") { next; } else {
//		$where .= "or LB.MAG_BODY_SEARCH like '%".trim(replace_single($val))."%' ";
		$where .= "or LB.MAG_BODY like '%".trim(replace_single($val))."%' ";
		$ninigo .= "\"" . trim($val) . "\" ";
	}
}

$where = "_".$where;
$where = ereg_replace("_or", "", $where);

$order_by = order_by(4, 0, 'MM.mag_pub_stop_flg', 'mag_pub_stop_dt', 'MM.mag_id', 'MM.mag_nm', 'LB.mag_titl', 'MP.owner_nm_kana', 'LB.mag_volume');
$sql = "SELECT LB.index_id,LB.mag_volume, LB.mag_titl, MM.mag_id, MM.mag_nm, MM.mag_pub_stop_flg, MM.mag_pub_status_flg,"
	. "to_char(MM.mag_pub_stop_dt,'yyyy/mm/dd') as mag_pub_stop_dt, MM.publisher_id, MP.owner_nm_kanj, MP.owner_nm_kana"
	. " FROM L_BACKNUMBER LB, M_MAGAZINE MM, M_PUBLISHER MP"
	. " WHERE LB.mag_id = MM.mag_id AND (".$where.") AND MM.mag_pub_status_flg<>'99' AND MM.PUBLISHER_ID <> 0 AND MM.publisher_id=MP.publisher_id "
	. $order_by;
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow <= 0) {
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
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
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
			<td>■任意語抽出 【<?= $ninigo ?>】<font size=-1>※メルマガに警告/フラグ解除する場合は、statusをクリックしてください。</font></td>
			<td align="right">該当件数：<span id="rec_count"></span>件 <input type="button" value=" 戻る " onclick="location.href='../hinsitsu/index.php'"><br>
			</td>
		</tr>
	</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
	<table border=1 cellspacing=0 cellpadding=3 width='100%'>
		<tr bgcolor="#ffcc99">
<?
		sort_header(1, 'status');
		sort_header(2, 'status更新日');
		sort_header(3, 'メルマガID');
		sort_header(4, 'メルマガ名');
		sort_header(5, 'Subject');
		sort_header(6, '発行者');
		sort_header(7, '発行部数');
?>
		</tr>
<?php

$line = 0;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$index_id = $fetch->index_id;
	$mag_volume = $fetch->mag_volume;
	$mag_titl = $fetch->mag_titl;
	$mag_id = $fetch->mag_id;
	$mag_nm = $fetch->mag_nm;
	$owner_nm_kanj = $fetch->owner_nm_kanj;
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
			<td align="center" title="status変更処理をします"><?= $stop_flg ?><br><font size=-1><?= $status_flg ?></font></td>
			<td align="center"><?= $mag_pub_stop_dt ?></td>
			<td align="center"><?= $mag_id ?></td>
			<td title="メルマガの詳細を表示します"><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= htmlspecialchars($mag_nm) ?></a></td>
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

<SCRIPT LANGUAGE=javascript>
<!--
document.all.rec_count.innerHTML = <?= $nrow ?>;
//-->
</SCRIPT>

<? footer_jmk(0); ?>

</body>
</html>
