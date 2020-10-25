<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:「プレゼント応募」管理
'******************************************************/

$title_text = $TT_present;
$title_color = $TC_OTHER;

$present_id = sprintf("%05d", $present_id);

$sql = "SELECT PD_present_name, PD_prize_1, pd_prize_2, PD_prize_3, PD_prize_name1, PD_prize_name2, PD_prize_name3"
		. " FROM M_PRESENT_DATA"
		. " WHERE PD_present_id='$present_id'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$present_name = $fetch->pd_present_name;
	$prize_1 = $fetch->pd_prize_1;
	$prize_2 = $fetch->pd_prize_2;
	$prize_3 = $fetch->pd_prize_3;
	$prize_name1 = $fetch->pd_prize_name1;
	$prize_name2 = $fetch->pd_prize_name2;
	$prize_name3 = $fetch->pd_prize_name3;
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
function OnClick_id(present_id) {
	location.href = "present2.php?present_id=" + present_id;
}
function OnClick_tousen(present_id) {
	location.href = "tousen.php?present_id=" + present_id;
}
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
<table border=0 cellspacing=0 cellpadding=0 width='80%'>
	<tr>
		<td>■「<?= $present_name ?>」当選者一覧</td>
		<td align="right"><input type="button" value=" 戻る " onclick="history.back()">
		</td>
	</tr>
</table>
<table border=1 cellspacing=0 cellpadding=2 width='80%'>
	<tr bgcolor="#ccffaa">
		<th colspan="5" align="left">１等　<font size=-1>(賞品： <?= $prize_name1 ?>　当選人数： <?= $prize_1 ?>名)</font></th>
	</tr>
	<tr bgcolor="#cce9dd">
		<th align="center" width="40%"><nobr>メールアドレス</nobr></th>
		<th align="center"><nobr>ﾒﾙﾏｶﾞID1</nobr></th>
		<th align="center"><nobr>ﾒﾙﾏｶﾞID2</nobr></th>
		<th align="center"><nobr>ﾒﾙﾏｶﾞID3</nobr></th>
		<th align="center"><nobr>応募日時</nobr></th>
	</tr>
<?php
$sql = "SELECT PL_mail_adr, PL_mag_id1, PL_mag_id2, PL_mag_id3, to_char(PL_date, 'YYYY/MM/DD HH24:MI:SS') as PL_date, PL_prize"
		. " FROM  T_PRESENT_LIST"
		. " WHERE PL_present_id='$present_id' AND PL_prize=1"
		. " ORDER BY PL_prize, PL_date";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$mail_adr = $fetch->pl_mail_adr;
	$mag_id1 = $fetch->pl_mag_id1;
	$mag_id2 = $fetch->pl_mag_id2;
	$mag_id3 = $fetch->pl_mag_id3;
	$date = $fetch->pl_date;
	$prize = $fetch->pl_prize;
?>
	<tr class="tc<?= $line % 2; ?>">
		<td align="left"><?= $mail_adr ?></td>
		<td align="center"><a href='JavaScript:OnClick_mag("<?= $mag_id1 ?>")'><nobr><?= $mag_id1 ?></nobr></a></td>
		<td align="center"><a href='JavaScript:OnClick_mag("<?= $mag_id2 ?>")'><nobr><?= $mag_id2 ?></nobr></a></td>
		<td align="center"><a href='JavaScript:OnClick_mag("<?= $mag_id3 ?>")'><nobr><?= $mag_id3 ?></nobr></a></td>
		<td align="center"><nobr><?= $date ?></nobr></td>
	</tr>
<?php
$line++;
}
if ($prize_2) {
?>
</table><br>
<table border=1 cellspacing=0 cellpadding=2 width='80%'>
	<tr bgcolor="#ccffaa">
		<th colspan="5" align="left">２等　<font size=-1>(賞品： <?= $prize_name2 ?>　当選人数： <?= $prize_2 ?>名)</font></th>
	</tr>
	<tr bgcolor="#cce9dd">
		<th align="center" width="40%"><nobr>メールアドレス</nobr></th>
		<th align="center"><nobr>ﾒﾙﾏｶﾞID1</nobr></th>
		<th align="center"><nobr>ﾒﾙﾏｶﾞID2</nobr></th>
		<th align="center"><nobr>ﾒﾙﾏｶﾞID3</nobr></th>
		<th align="center"><nobr>応募日時</nobr></th>
	</tr>
<?php
	$sql = "SELECT PL_mail_adr, PL_mag_id1, PL_mag_id2, PL_mag_id3, to_char(PL_date, 'YYYY/MM/DD HH24:MI:SS') as PL_date, PL_prize"
			. " FROM  T_PRESENT_LIST"
			. " WHERE PL_present_id='$present_id' AND PL_prize=2"
			. " ORDER BY PL_date";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mail_adr = $fetch->pl_mail_adr;
		$mag_id1 = $fetch->pl_mag_id1;
		$mag_id2 = $fetch->pl_mag_id2;
		$mag_id3 = $fetch->pl_mag_id3;
		$date = $fetch->pl_date;
		$prize = $fetch->pl_prize;
?>
	<tr class="tc<?= $line % 2; ?>">
		<td align="left"><?= $mail_adr ?></td>
		<td align="center"><a href='JavaScript:OnClick_mag("<?= $mag_id1 ?>")'><nobr><?= $mag_id1 ?></nobr></a></td>
		<td align="center"><a href='JavaScript:OnClick_mag("<?= $mag_id2 ?>")'><nobr><?= $mag_id2 ?></nobr></a></td>
		<td align="center"><a href='JavaScript:OnClick_mag("<?= $mag_id3 ?>")'><nobr><?= $mag_id3 ?></nobr></a></td>
		<td align="center"><nobr><?= $date ?></nobr></td>
	</tr>
<?php
	$line++;
	}
}
if ($prize_3) {
?>
</table><br>
<table border=1 cellspacing=0 cellpadding=2 width='80%'>
	<tr bgcolor="#ccffaa">
		<th colspan="5" align="left">３等　<font size=-1>(賞品： <?= $prize_name3 ?>　当選人数： <?= $prize_3 ?>名)</font></th>
	</tr>
	<tr bgcolor="#cce9dd">
		<th align="center" width="40%"><nobr>メールアドレス</nobr></th>
		<th align="center"><nobr>ﾒﾙﾏｶﾞID1</nobr></th>
		<th align="center"><nobr>ﾒﾙﾏｶﾞID2</nobr></th>
		<th align="center"><nobr>ﾒﾙﾏｶﾞID3</nobr></th>
		<th align="center"><nobr>応募日時</nobr></th>
	</tr>
<?php
	$sql = "SELECT PL_mail_adr, PL_mag_id1, PL_mag_id2, PL_mag_id3, to_char(PL_date, 'YYYY/MM/DD HH24:MI:SS') as PL_date, PL_prize"
			. " FROM  T_PRESENT_LIST"
			. " WHERE PL_present_id='$present_id' AND PL_prize=3"
			. " ORDER BY PL_date";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mail_adr = $fetch->pl_mail_adr;
		$mag_id1 = $fetch->pl_mag_id1;
		$mag_id2 = $fetch->pl_mag_id2;
		$mag_id3 = $fetch->pl_mag_id3;
		$date = $fetch->pl_date;
		$prize = $fetch->pl_prize;
?>
	<tr class="tc<?= $line % 2; ?>">
		<td align="left"><?= $mail_adr ?></td>
		<td align="center"><a href='JavaScript:OnClick_mag("<?= $mag_id1 ?>")'><nobr><?= $mag_id1 ?></nobr></a></td>
		<td align="center"><a href='JavaScript:OnClick_mag("<?= $mag_id2 ?>")'><nobr><?= $mag_id2 ?></nobr></a></td>
		<td align="center"><a href='JavaScript:OnClick_mag("<?= $mag_id3 ?>")'><nobr><?= $mag_id3 ?></nobr></a></td>
		<td align="center"><nobr><?= $date ?></nobr></td>
	</tr>
<?php
	$line++;
	}
}
?>
</table>
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
