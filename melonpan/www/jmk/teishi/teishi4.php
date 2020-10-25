<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:メルマガ停止処理
'******************************************************/

$title_text = $TT_teishi;
$title_color = $TC_HAKKOUSHA;

$job_flg = substr($mag_id, 0, 2);
$mag_id = substr($mag_id, -6);
$mag_id = sprintf("%06d", $mag_id);

//'=== タイトルコメント選択 ===
if ($job_flg == "NG") {
	$sql = "update M_MAGAZINE set MAG_PUB_STOP_FLG = '02', MAG_PUB_STOP_DT = 'now' where MAG_ID = '$mag_id'";
	db_exec($sql);
	$comment = "下記のメルマガの発行を停止しました。";
} elseif ($job_flg == "OK") {
	$sql = "update M_MAGAZINE set MAG_PUB_STOP_FLG = '00', MAG_PUB_STOP_DT=null where MAG_ID = '$mag_id'";
	db_exec($sql);
	$comment = "下記のメルマガを停止状態から復活しました。";
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
<form method="post" name="form1" style="margin:0">
<center>
<table border=0 cellspacing=0 cellpadding=0 width='90%'>
	<tr>
		<td>■<?= $comment ?></td>
		<td align="right"><input type="button" value=" 戻る " onclick="location.href='teishi1.php'">
</td>
	</tr>
<table border=1 cellspacing=0 cellpadding=1 width='90%'>
	<tr>
		<td class="h1" width="5%" align="center"><nobr>メルマガID</nobr></td>
		<td class="h1" width="5%" align="center"><nobr>警告</nobr></td>
		<td class="h1" width="35%" align="center"><nobr>メルマガ名</nobr></td>
		<td class="h1" width="20%" align="center"><nobr>発行者</nobr></td>
		<td class="h1" width="15%" align="center"><nobr>メールアドレス</nobr></td>
	</tr>
<?php
if ($mag_id <> "") {
	$mag_id = sprintf("%06d", $mag_id);

	$sql = "SELECT MAG.MAG_ID,MAG.MAG_PUB_STOP_FLG,MAG.MAG_NM,PUB.PUBLISHER_ID,PUB.OWNER_NM_KANJ,PUB.MAIL_ADD_MAIN";
	$sql .= " FROM M_MAGAZINE MAG,M_PUBLISHER PUB ";
	$sql .= " WHERE MAG.MAG_ID = '$mag_id' AND MAG.PUBLISHER_ID = PUB.PUBLISHER_ID ";
	$sql .= " ORDER BY MAG.MAG_ID";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	if ($nrow > 0) {
		$fetch = pg_fetch_object($result, $i);
		$publisher_id = $fetch->publisher_id;
		$mag_pub_stop_flg = $fetch->mag_pub_stop_flg;
		$mag_nm = $fetch->mag_nm;
		$owner_nm_kanj = $fetch->owner_nm_kanj;
		$mail_add_main = $fetch->mail_add_main;
		if ($mag_pub_stop_flg == "00") {
			$mag_pub_stop_flg = "<font color=\"green\">OK</font>";
		} elseif ($mag_pub_stop_flg == "01") {
			$mag_pub_stop_flg = "<font color=\"#FF9900\">Y</font>";
		} elseif ($mag_pub_stop_flg == "02") {
			$mag_pub_stop_flg = "<font color=\"red\">R</font>";
		}
?>
	<tr class="tc0">
		<td align="center"><?= $mag_id ?></td>
		<td align="center"><font color="red"><?= $mag_pub_stop_flg ?></font></td>
		<td><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= $mag_nm ?></a></td>
		<td align="left"><a href='JavaScript:OnClick_pub("<?= $publisher_id ?>")'><?= $owner_nm_kanj ?></a></td>
		<td align="center"><a href="mailto:<?= $mail_add_main ?>"><?= $mail_add_main ?></a></td>
	</tr>
<?
	}
}
?>
</table>
</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
