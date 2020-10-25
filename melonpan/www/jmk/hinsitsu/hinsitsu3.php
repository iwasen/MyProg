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

//出力期間
switch ($term) {
    case "all":
        $where1 = "";
				$term_name1 = "全て";
				break;
    case "1m":
				$where1 = " AND LB.modefy_dt BETWEEN now() - interval '1 month' AND 'now'";
				$term_name1 = "過去1ヶ月";
				break;
    case "3m":
				$where1 = " AND LB.modefy_dt BETWEEN now() - interval '3 month' AND 'now'";
				$term_name1 = "過去3ヶ月";
        break;
    case "6m":
				$where1 = " AND LB.modefy_dt BETWEEN now() - interval '6 month' AND 'now'";
				$term_name1 = "過去6ヶ月";
        break;
}

//出力条件
switch ($chk_flg) {
    case "subject":
        $where2 = " AND LB.MAG_TITL like '%$keyword%'";
				$term_name2 = "Subjectのみ";
				break;
    case "honbun":
//		$where2 = " AND LB.mag_body_search like '%$keyword%'";
		$where2 = " AND LB.mag_body like '%$keyword%'";
				$term_name2 = "本文のみ";
				break;
		case "both":
//		$where2 = " AND LB.MAG_TITL like '%$keyword%' AND LB.mag_body_search like '%$keyword%'";
		$where2 = " AND LB.MAG_TITL like '%$keyword%' AND LB.mag_body like '%$keyword%'";
				$term_name2 = "Subject＆本文";
				break;
}

//==== Main ===
$order_by = order_by(4, 0, 'MM.mag_pub_stop_flg', 'mag_pub_stop_dt', 'MM.mag_id', 'MM.mag_nm', 'LB.mag_titl', 'MP.owner_nm_kana', 'LB.mag_volume');
$sql = "SELECT LB.index_id, LB.mag_volume, LB.mag_titl, MM.mag_id,MM.mag_nm,MM.mag_pub_stop_flg, to_char(MM.mag_pub_stop_dt,'yyyy/mm/dd') as mag_pub_stop_dt"
	.	", MM.publisher_id, MM.mag_pub_status_flg, MP.owner_nm_kanj, MP.owner_nm_kana"
	. " FROM L_BACKNUMBER LB, M_MAGAZINE MM, M_PUBLISHER MP"
	. " WHERE LB.mag_id=MM.mag_id AND MM.mag_pub_status_flg<>'99' AND MM.publisher_id<>0" .	$where1 . $where2
	. " AND MM.publisher_id=MP.publisher_id"
	. " " . $order_by;
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
			<td>■Subject検索　【"<?= stripcslashes($keyword) ?>"：<?= $term_name1 ?>：<?= $term_name2 ?>】 <font size=-1>※メルマガに警告/フラグ解除する場合は、statusをクリックしてください。</font></td>
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
	$mag_id = $fetch->mag_id;
	$mag_nm = $fetch->mag_nm;
	$mag_titl = $fetch->mag_titl;
	$owner_nm_kanj = $fetch->owner_nm_kanj;
	$owner_nm_kana = $fetch->owner_nm_kana;
	$publisher_id = $fetch->publisher_id;
	$mag_pub_status_flg = $fetch->mag_pub_status_flg;
	$mag_pub_stop_flg = $fetch->mag_pub_stop_flg;
	$mag_pub_stop_dt = $fetch->mag_pub_stop_dt;
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
			<td title="メルマガの詳細を表示します"><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= htmlspecialchars($mag_nm) ?></a></td>
			<td title="メルマガの内容を表示します"><a href='JavaScript:OnClick_honbun(<?= $index_id ?>)'><?= htmlspecialchars($mag_titl) ?></a></td>
			<td title="発行者の詳細を表示します"><a href='JavaScript:OnClick_pub("<?= $publisher_id ?>")'><?= htmlspecialchars($owner_nm_kanj) ?></a></td>
			<td align="right"><?= number_format($mag_volume) ?></td>
		</tr>
<?php
$line++;
}
?>
<input type="hidden" name="keyword" value="<?= $keyword ?>">
<input type="hidden" name="term" value="<?= $term ?>">
<input type="hidden" name="chk_flg" value="<?= $chk_flg ?>">
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
