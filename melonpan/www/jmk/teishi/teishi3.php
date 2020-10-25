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

$keyword = ereg_replace("　"," ",$keyword);
$keyword = trim($keyword);
$keys = split(" ",$keyword);

$allay = count($keys);
if ($allay > 1) {
	$where = "";
	while (list($key,$val) = each($keys)) {
		$where .= $andor;
		$where .= " (MAG.MAG_NM like '%$val%' or MAG.MAG_INTRO_LONG like '%$val%' or ";
		$where .= " MAG.MAG_INTRO_SHORT like '%$val%') ";
	}
	$where = "_".$where;
	$where = ereg_replace("_$andor","",$where);
} else {
	$where .= " MAG.MAG_NM like '%$keyword%' or MAG.MAG_INTRO_LONG like '%$keyword%' or ";
	$where .= " MAG.MAG_INTRO_SHORT like '%$keyword%' ";
}

$sql = "SELECT MAG.MAG_ID,MAG.MAG_PUB_STOP_FLG,MAG.MAG_NM,PUB.PUBLISHER_ID,PUB.OWNER_NM_KANJ,PUB.MAIL_ADD_MAIN";
$sql .= " FROM M_MAGAZINE MAG,M_PUBLISHER PUB ";
$sql .= " WHERE ($where) AND MAG.PUBLISHER_ID = PUB.PUBLISHER_ID ";
$sql .= " ORDER BY MAG.MAG_ID";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow <= 0) {
	header ("location: ../admin/err_msg.php?id=0406");
	exit;
} else {
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
function OnClick_ok(mag_id) {
	if (confirm("このメルマガを停止状態から復活します。よろしいですか？")) {
	location.href = "teishi4.php?mag_id=OK" + mag_id;
	}
}
function OnClick_ng(mag_id) {
	if (confirm("このメルマガの発行を停止します。よろしいですか？")) {
	location.href = "teishi4.php?mag_id=NG" + mag_id;
	}
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
		<td>■メルマガ発行停止処理【強制発行停止】</td>
		<td align="right">該当件数：<span id="rec_count"></span>件 <input type="button" value=" 戻る " onclick="history.back()"></td>
	</tr>
<table border=1 cellspacing=0 cellpadding=1 width='90%'>
	<tr bgcolor="#ffcc99">
		<th width="5%" align="center"><nobr>メルマガID</nobr></th>
		<th width="5%" align="center"><nobr>警告</nobr></th>
		<th width="35%" align="center"><nobr>メルマガ名</nobr></th>
		<th width="20%" align="center"><nobr>発行者</nobr></th>
		<th width="15%" align="center"><nobr>メールアドレス</nobr></th>
		<th width="10%" align="center"><nobr>停止</nobr></th>
		<th width="10%" align="center"><nobr>復活</nobr></hd>
	</tr>
<?php
	$line = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mag_id = $fetch->mag_id;
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
	<tr class="tc<?= $line % 2; ?>">
		<td align="center"><?= $mag_id ?></td>
		<td align="center"><?= $mag_pub_stop_flg ?></td>
		<td><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= htmlspecialchars($mag_nm) ?></a></td>
		<td align="left"><a href='JavaScript:OnClick_pub("<?= $publisher_id ?>")'><?= htmlspecialchars($owner_nm_kanj) ?></a></td>
		<td align="left"><a href="mailto:<?= $mail_add_main ?>"><?= $mail_add_main ?></a></td>
		<td align="center"><input type="button" name="ng" value="停止" onclick='OnClick_ng("<?= $mag_id ?>")'></td>
		<td align="center"><input type="button" name="ok" value="復活" onclick='OnClick_ok("<?= $mag_id ?>")'></td>
	</tr>
<?php
$line++;
	}
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
