<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:メルマガナビ管理
'******************************************************/

$title_text = $TT_melonpai_kannri;
$title_color = $TC_DOKUSHA;

if (!$info_year) {
	$info_year = date("Y");
}
if (!$info_month) {
	$info_month = date("m");
}
$yyyymm =  $info_year . sprintf("%02d", $info_month);

$sql = "SELECT melonpai_name FROM M_MELONPAI WHERE melonpai_id=$melonpai_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$melonpai_name = $fetch->melonpai_name;
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
function OnClick_recom(recomend_id) {
  var win;
  win = window.open("../melmaga_detail/recommend.php?recomend_id=" + recomend_id, "info_pub", "scrollbars=no,resizable=yes,width=400,height=260");
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
		<td>■<?= htmlspecialchars($melonpai_name) ?>さん 月別「推薦文」作業状況一覧　　
			<select name="info_year"><? select_year(2000, '', date($info_year)); ?></select>年
			<select name="info_month" onchange="submit()"><? select_month('', date($info_month)); ?></select>月</td>
		<td align="right">件数：<span id="rec_count"></span>件 <input type="button" value=" 戻る " onclick="location.href='melonpai_kannri2.php?melonpai_id=<?= $melonpai_id ?>'"></td>
			<table border=1 cellspacing=0 cellpadding=3 width='80%'>
				<tr bgcolor="#99ccff">
					<th align="center" colspan="1">作業日</th>
					<th align="center" colspan="1">ﾒﾙﾏｶﾞID</th>
					<th align="center" colspan="1">読者ID</th>
					<th align="center" colspan="1">タイトル</th>
					<th align="center" colspan="4">処理</th>
				</tr>
<?php
$line = 0;
$sql = "SELECT to_char(LM.modify_dt, 'YYYY/MM/DD') as modify_dt, TR.mag_id, TR.reader_id, LM.recom_titl, LM.log_pro_no, LM.recomend_id"
	. ", TR.recomend_id AS recomend_log"
	. " FROM L_MELONPAI_LOG LM LEFT JOIN T_RECOMEND TR ON LM.recomend_id=TR.recomend_id"
	. " WHERE melonpai_id=$melonpai_id AND (log_pro_no=5 OR log_pro_no=6) AND to_char(LM.modify_dt, 'YYYYMM')='$yyyymm'"
	. " ORDER BY LM.modify_dt";
$result = db_exec($sql);
$nrow = pg_numrows($result);

for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$modify_dt = $fetch->modify_dt;
	$reader_id = $fetch->reader_id;
	$mag_id = $fetch->mag_id;
	$recom_titl = $fetch->recom_titl;
	$recomend_id = $fetch->recomend_id;
	$recomend_log = $fetch->recomend_log;
	$log_pro_no = $fetch->log_pro_no;
	if ($mag_id == "") { $mag = "-"; }
	if ($mag_id <> "") { $mag = "<a href='JavaScript:OnClick_mag(\"" . $mag_id . "\")'>" . $mag_id . "</a>"; }
	if ($reader_id == "") { $reader_id = "-"; }
	if ($log_pro_no == 5) { $log_pro = "公開"; }
	if ($log_pro_no == 6) { $log_pro = "削除"; }
	if ($recomend_log == "") { $log_pro = "削除"; }
?>
				<tr class="tc<?= $line % 2; ?>">
					<td align="center"><?= $modify_dt ?></td>
					<td align="center"><?= $mag ?></td>
					<td align="right"><?= $reader_id ?></td>
					<td align="left"><a href='JavaScript:OnClick_recom("<?= $recomend_id ?>")'><?= htmlspecialchars($recom_titl) ?></a></td>
					<td align="center"><?= $log_pro ?></td>
				</tr>
<?php
$line++;
}
?>
			</table>
		</td>
	<tr>
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
