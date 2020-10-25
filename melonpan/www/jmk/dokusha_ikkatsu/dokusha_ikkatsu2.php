<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:読者一括登録/削除
'******************************************************/

$title_text = $TT_dokusha_ikkatsu;
$title_color = $TC_DOKUSHA;


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
function OnClick_add(mag_id) {
	location.href = "dokusha_ikkatsu3.php?mag_id=" + mag_id;
}
function OnClick_cut(mag_id) {
	location.href = "dokusha_ikkatsu5.php?mag_id=" + mag_id;
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
		<td>■該当メルマガ一覧</td>
		<td align="right">該当件数：<span id="rec_count"></span>件 <input type="button" value=" 戻る " onclick="history.back()"></td>
			<table border=1 cellspacing=0 cellpadding=3 width='90%'>
				<tr bgcolor="#ffcc99">
					<th width="5%" align="center"><nobr>メルマガID</nobr></th>
					<th width="35%" align="center"><nobr>メルマガ名</nobr></th>
					<th width="20%" align="center"><nobr>発行者</nobr></th>
					<th width="15%" align="center"><nobr>メールアドレス</nobr></th>
					<th width="10%" align="center"><nobr>読者追加</nobr></th>
					<th width="10%" align="center"><nobr>読者削除</nobr></th>
				</tr>
<?php
if ($mag_id <> "") {
	$mag_id = sprintf("%06d", $mag_id);

	$sql = "SELECT MAG.MAG_ID,MAG.MAG_PUB_STOP_FLG,MAG.MAG_NM,PUB.PUBLISHER_ID,PUB.OWNER_NM_KANJ,PUB.MAIL_ADD_MAIN"
		. " FROM M_MAGAZINE MAG,M_PUBLISHER PUB "
		. " WHERE MAG.MAG_ID = '$mag_id' AND MAG.PUBLISHER_ID = PUB.PUBLISHER_ID "
		. " ORDER BY MAG.MAG_ID";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$publisher_id = $fetch->publisher_id;
		$mag_nm = $fetch->mag_nm;
		$owner_nm_kanj = $fetch->owner_nm_kanj;
		$mail_add_main = $fetch->mail_add_main;
?>
				<tr>
					<td align="center"><?= $mag_id ?></td>
					<td><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= htmlspecialchars($mag_nm) ?></a></td>
					<td align="left"><a href='JavaScript:OnClick_pub("<?= $publisher_id ?>")'><?= htmlspecialchars($owner_nm_kanj) ?></a></td>
					<td align="left"><a href="mailto:<?= $mail_add_main ?>"><?= $mail_add_main ?></a></td>
					<td align="center"><input type="button" value="追加" onclick='OnClick_add("<?= $mag_id ?>")'></td>
					<td align="center"><input type="button" value="削除" onclick='OnClick_cut("<?= $mag_id ?>")'></td>
				</tr>
<?php
	}
} elseif ($keyword <> "") {
	$line = 0;

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

	$sql = "SELECT MAG.MAG_ID,MAG.MAG_PUB_STOP_FLG,MAG.MAG_NM,PUB.PUBLISHER_ID,PUB.OWNER_NM_KANJ,PUB.MAIL_ADD_MAIN"
		. " FROM M_MAGAZINE MAG,M_PUBLISHER PUB "
		. " WHERE ($where) AND MAG.PUBLISHER_ID = PUB.PUBLISHER_ID "
		. " ORDER BY MAG.MAG_ID";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mag_id = $fetch->mag_id;
		$publisher_id = $fetch->publisher_id;
		$mag_pub_stop_flg = $fetch->mag_pub_stop_flg;
		$mag_nm = $fetch->mag_nm;
		$owner_nm_kanj = $fetch->owner_nm_kanj;
		$mail_add_main = $fetch->mail_add_main;

		if ($mag_pub_stop_flg == "00") {
			$mag_pub_stop_flg = "OK";
		} elseif ($mag_pub_stop_flg == "01") {
			$mag_pub_stop_flg = "Y";
		} elseif ($mag_pub_stop_flg == "02") {
			$mag_pub_stop_flg = "R";
		}
	

?>
				<tr class="tc<?= $line % 2; ?>">
					<td align="center"><?= $mag_id ?></td>
					<td><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= $mag_nm ?></a></td>
					<td align="left"><a href='JavaScript:OnClick_pub("<?= $publisher_id ?>")'><?= $owner_nm_kanj ?></a></td>
					<td align="left"><a href="mailto:<?= $mail_add_main ?>"><?= $mail_add_main ?></a></td>
					<td align="center"><input type="button" name="ng" value="追加" onclick='OnClick_add("<?= $mag_id ?>")'></td>
					<td align="center"><input type="button" name="ok" value="削除" onclick='OnClick_cut("<?= $mag_id ?>")'></td>
				</tr>
<?php
	$line++;
	}
}
?>
			</table>
		</td>
	</tr>
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
