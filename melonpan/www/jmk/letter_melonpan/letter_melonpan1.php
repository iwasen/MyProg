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
function OnClick_sel(mag_id, melonpai_id, sel_date) {
  var win;
  win = window.open("../melmaga_detail/osusume_comment.php?mag_id=" + mag_id + "&melonpai_id=" + melonpai_id +  "&sel_date=" + sel_date, "info_mag", "scrollbars=yes,resizable=yes,width=450,height=300");
  win.focus();
}
function OnSubmit_form1() {
	return confirm("登録します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" style="margin:0" action="letter_melonpan2.php" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='90%'>
		<tr>
			<td>■めろんぱんレター掲載候補メルマガ一覧
			<td align="right">
				<input type="submit" value=" 登録 ">
				<input type="button" value=" 戻る " onclick="location.href='index.php'">
			</td>
		</tr>
	</table>
	<table border=0 cellspacing=0 cellpadding=0 width='90%'>
		<tr>
			<td>≪おすすめメルマガ≫
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=1 width='90%'>
		<tr bgcolor="#ffcc99">
			<th align="center"><nobr>メルマガID</nobr></th>
			<th align="center"><nobr>メルマガ名</nobr></th>
			<th align="center"><nobr>読者数</nobr></th>
			<th align = "center"><nobr>選定日</nobr></th>
			<th align="center"><nobr>本登録承認日</nobr></th>
			<th align="center"><nobr>掲載</nobr></th>
		</tr>
<?
$line = 0;$j = 0;

$sub = "SELECT count(*) from T_MAILADDR WHERE mag_id=OL.mag_id";
$sql = "SELECT (" . $sub . ") as dokusha_count, OL.mag_id, MM.mag_nm, TO_CHAR(MM.mag_agr_dt, 'YYYY/MM/DD') as mag_agr_dt"
	. ", TO_CHAR(OL.sel_date, 'YYYY/MM/DD') as sel_date, OL.melonpai_id"
	. " FROM T_OSUSUME_LIST OL, M_MAGAZINE MM"
	. " WHERE OL.date_to IS NULL AND OL.keisai_date IS NULL AND OL.mag_id=MM.mag_id AND MM.mag_status_flg='00' AND mag_pub_stop_flg='00'"
	. " ORDER BY OL.sel_date";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$dokusha_count = $fetch->dokusha_count;
	$mag_id = $fetch->mag_id;
	$mag_nm = $fetch->mag_nm;
	$mag_agr_dt = $fetch->mag_agr_dt;
	$sel_date = $fetch->sel_date;
	$melonpai_id = $fetch->melonpai_id;
?>
		<tr class="tc<?= $line % 2; ?>">
			<input type="hidden" name="osusume_mag_id[<?= $j ?>]" value="<?= $mag_id ?>">
			<td align="center"><?= $mag_id ?></td>
			<td><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= $mag_nm ?></a></td>
			<td align="right"><?= number_format($dokusha_count) ?></td>
			<td align="center"><a href='JavaScript:OnClick_sel("<?= $mag_id . "\", \"" . $melonpai_id . "\", \"" . $sel_date ?>")'><?= $sel_date ?></a></td>
			<td align="center"><?= replace_br($mag_agr_dt) ?></td>
			<td align="center">
				<input type="radio" name="osusume_keisai[<?= $j ?>]" value="<?= $mag_id ?>">Yes　
				<input type="radio" name="osusume_keisai[<?= $j ?>]" value = "" checked>No
			</td>
		</tr>
<?
$line++;$j++;
}
?>
	</table><br>
	<table border=0 cellspacing=0 cellpadding=0 width='90%'>
		<tr>
			<td>≪新着メルマガ≫</td>
		</tr>
	</table>
<?php
$line = 0;$j = 0;$catg1_temp = "";
$before_14day = strftime ("%Y%m%d", mktime (0,0,0,date(m),date(d)-14,date(Y))); 
$sub = "SELECT count(*) FROM T_MAILADDR WHERE mag_id=MM.mag_id";
$sql = "SELECT (" . $sub . ") as dokusha_count, MM.mag_id, MM.mag_nm, TO_CHAR(MM.mag_agr_dt, 'YYYY/MM/DD') as mag_agr_dt"
	. ", SUBSTRING(MC.catg_id from 1 for 2) as catg1, MC.catg"
	. " FROM M_MAGAZINE MM, M_MAG_CATG MC"
	. " WHERE MM.keisai_date IS NULL AND MM.mag_agr_dt BETWEEN to_date($before_14day, 'YYYYMMDD') AND 'now' AND MM.mag_status_flg='00'"
	. " AND mag_pub_status_flg='03' AND mag_pub_stop_flg='00' AND RPAD(SUBSTRING(MM.mag_catg_1 from 1 for 2), 4, '0')=MC.catg_id"
	. " ORDER BY catg1,MM.mag_agr_dt";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$dokusha_count = $fetch->dokusha_count;
	$mag_id = $fetch->mag_id;
	$mag_nm = $fetch->mag_nm;
	$mag_agr_dt = $fetch->mag_agr_dt;
	$catg1 = $fetch->catg1;
	$catg = $fetch->catg;
	if ($catg1 <> $catg1_temp) {
?>

	<table border=0 cellspacing=0 cellpadding=3 width='90%'>
		<tr>
			<td align="left">【<?= substr($catg, 2) ?>】</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=1 width='90%'>
		<tr bgcolor="#ffcc99">
			<th width = "10%" align="center"><nobr>メルマガID</nobr></th>
			<th width = "50%" align="center"><nobr>メルマガ名</nobr></th>
			<th width = "10%" align="center"><nobr>読者数</nobr></th>
			<th width = "15%" align="center"><nobr>本登録承認日</nobr></th>
			<th width = "15%" align="center"><nobr>掲載</nobr></th>
		</tr>
<?
	}
?>
		<tr class="tc<?= $line % 2; ?>">
			<input type="hidden" name="new_mag_id[<?= $j ?>]" value="<?= $mag_id ?>">
			<td align="center"><?= $mag_id ?></td>
			<td><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= htmlspecialchars($mag_nm) ?></a></td>
			<td align="right"><?= number_format($dokusha_count) ?></td>
			<td align="center"><?= replace_br($mag_agr_dt) ?></td>
			<td align="center"><nobr>
				<input type="radio" name="new_keisai[<?= $j ?>]" value="<?= $mag_id ?>">Yes　
				<input type="radio" name="new_keisai[<?= $j ?>]" value="" checked>No　
			</nobr></td>
		</tr>
<?php
$catg1_temp = $catg1;	
$line++; $j++;
}
?>
	</table>
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
