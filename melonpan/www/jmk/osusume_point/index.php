<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/database.php");
include ("../inc/admin_inc.php");
include ("../inc/com_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:オススメ/ポイント付与
'******************************************************/

$title_text = $TT_osusume_point;
$title_color = $TC_MASTER;

$sql = "SELECT OL.mag_id, to_char(OL.sel_date, 'YYYY/MM/DD') as sel_date, MM.mag_nm, PB.owner_nm_kanj, to_char(BN.modefy_dt, 'YYYY/MM/DD') as modefy_dt"
	. ", BN.mag_volume, MM.publisher_id"
	. " FROM T_OSUSUME_LIST OL, M_MAGAZINE MM, M_PUBLISHER PB, L_BACKNUMBER BN"
	. " WHERE date_to IS NULL AND OL.sel_date <= BN.modefy_dt AND OL.mag_id=MM.mag_id AND MM.publisher_id=PB.publisher_id AND OL.mag_id=BN.mag_id"
	. " ORDER BY OL.mag_id, BN.modefy_dt";
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
function OnClick_mag(mag_id) {
  var win;
  win = window.open("../melmaga_detail/mag_detail1.php?mag_id=" + mag_id, "info_mag", "scrollbars=yes,resizable=yes,width=600,height=700");
  win.focus();
}
function OnClick_pub(pub_id) {
  var win;
  win = window.open("../melmaga_detail/publisher.php?pub_id=" + pub_id, "info_pub", "scrollbars=yes,resizable=yes,width=600,height=500");
  win.focus();
}
function OnClick_sel(mag_id, sel_date) {
  var win;
  win = window.open("../melmaga_detail/osusume_comment.php?mag_id=" + mag_id +  "&sel_date=" + sel_date, "info_mag", "scrollbars=yes,resizable=yes,width=450,height=300");
  win.focus();
}
function OnChange_calc(mag_volume, i) {
	n = document.form1.tanka[i].value;
	document.form1.total[i].value = Math.round(mag_volume * n);
}
function OnSubmit_form1() {
  return confirm("実行します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form style="margin:0" method="post" name="form1" action="osusume_point.php" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='100%'>
		<tr>
			<td>■オススメメルマガ・ポイント未付与一覧　<font size=-1>(※オススメに選ばれて、最初の１回の発行にポイントを付与します。)</font></td>
			<td align="right"><input type="button" value=" 戻る " onclick="location.href='../menu.php'"><br>
			</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=3 width='100%'>
		<tr bgcolor="#ffcc99">
			<th align="center"><nobr>メルマガID</nobr></th>
			<th align="center"><nobr>メルマガ名</nobr></th>
			<th align="center"><nobr>発行者</nobr></th>
			<th align="center"><nobr>選定日</nobr></th>
			<th align="center"><nobr>配信日</nobr></th>
			<th align="center"><nobr>配信数</nobr></th>
			<th align="center"><nobr>単価</nobr></th>
			<th align="center"><nobr>合計</nobr></th>
			<th align="center"><nobr>処理</nobr></th>
		</tr>
<?php
$line = 0;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$mag_id = $fetch->mag_id;
	$mag_nm = $fetch->mag_nm;
	$owner_nm_kanj = $fetch->owner_nm_kanj;
	$sel_date = $fetch->sel_date;
	$modefy_dt = $fetch->modefy_dt;
	$mag_volume = $fetch->mag_volume;
	$publisher_id = $fetch->publisher_id;
	if ($mag_id == $old_mag_id) { continue; }
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><?= $mag_id ?></td>
			<td align="left"><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= $mag_nm ?></a></td>
			<td align="left"><a href='JavaScript:OnClick_pub("<?= $publisher_id ?>")'><?= $owner_nm_kanj ?></a></td>
			<td align="center"><a href='JavaScript:OnClick_sel("<?= $mag_id . "\", \"" . $sel_date ?>")'><?= $sel_date ?></a></td>
			<td align="center"><?= $modefy_dt ?></td>
			<td align="right"><?= number_format($mag_volume) ?></td>
			<td align="center">×<input size=3 DIR=rtl name=tanka maxlength=4 value=1 OnChange='JavaScript:OnChange_calc(<?= $mag_volume ?>, <?= $line ?>)'></td>
			<td align="center"><input type="text" name="total" size=10 maxlength=10 DIR=rtl value=<?= number_format($mag_volume) ?>></td>
			<td align="center"><input type="checkbox" name="chk[]"></td>
		</tr>
<?php
$old_mag_id = $mag_id;
$line++;
}
?>

	</table><br>
  <input type="submit" value=" 実行 ">
  <input type="reset" value="リセット">
  <input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>
</SCRIPT>

<? footer_jmk(0); ?>

</body>
</html>
