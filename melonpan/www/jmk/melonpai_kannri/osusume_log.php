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
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" style="margin:0">
<table border=0 cellspacing=0 cellpadding=0 width='90%'>
	<tr>
		<td>■<?= htmlspecialchars($melonpai_name) ?>さん 月別「オススメ」作業状況一覧　　
			<select name="info_year"><? select_year(2000, '', date($info_year)); ?></select>年
			<select name="info_month" onchange="submit()"><? select_month('', date($info_month)); ?></select>月</td>
		<td align="right">件数：<span id="rec_count"></span>件 <input type="button" value=" 戻る " onclick="location.href='melonpai_kannri2.php?melonpai_id=<?= $melonpai_id ?>'"></td>
			<table border=1 cellspacing=0 cellpadding=3 width='90%'>
				<tr bgcolor="#99ccff">
					<th align="center"><nobr>選定日</nobr></th>
					<th align="center"><nobr>ﾒﾙﾏｶﾞID</nobr></th>
					<th align="center"><nobr>メルマガ名</nobr></th>
					<th align="center"><nobr>有効期間</nobr></th>
					<th align="center"><nobr>オススメ理由</nobr></th>
				</tr>
<?php
$line = 0;
$sql = "SELECT OL.mag_id, to_char(OL.sel_date, 'YYYY/MM/DD') as sel_date, to_char(OL.date_from, 'YYYY/MM/DD') as date_from"
	. ", to_char(OL.date_to, 'YYYY/MM/DD') as date_to, OL.reason, MM.mag_nm"
	. " FROM T_OSUSUME_LIST OL, M_MAGAZINE MM"
	. " WHERE OL.melonpai_id=$melonpai_id AND to_char(OL.sel_date, 'YYYYMM')='$yyyymm' AND OL.mag_id=MM.mag_id"
	. " ORDER BY OL.sel_date";
$result = db_exec($sql);
$nrow = pg_numrows($result);

for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$mag_id = $fetch->mag_id;
	$mag_nm = $fetch->mag_nm;
	$sel_date = $fetch->sel_date;
	$date_from = $fetch->date_from;
	$date_to = $fetch->date_to;
	$reason = $fetch->reason;
?>
				<tr class="tc<?= $line % 2; ?>">
					<td align="center"><?= $sel_date ?></td>
					<td align="center"><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= $mag_id ?></a></td>
					<td align="left"><?= htmlspecialchars($mag_nm) ?></td>
					<td align="left"><nobr><?= $date_from ?>〜<?= $date_to ?></nobr></td>
					<td align="left"><?= htmlspecialchars($reason) ?></td>
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
