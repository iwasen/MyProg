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
function OnClick_com(cho_id) {
  var win;
  win = window.open("../melmaga_detail/choice.php?cho_id=" + cho_id, "info_com", "scrollbars=no,resizable=yes,width=400,height=260");
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
		<td>■<?= htmlspecialchars($melonpai_name) ?>さん 月別「みつくろい」作業状況一覧　　
			<select name="info_year"><? select_year(2000, '', date($info_year)); ?></select>年
			<select name="info_month" onchange="submit()"><? select_month('', date($info_month)); ?></select>月</td>
		<td align="right">件数：<span id="rec_count"></span>件 <input type="button" value=" 戻る " onclick="location.href='melonpai_kannri2.php?melonpai_id=<?= $melonpai_id ?>'"></td>
			<table border=1 cellspacing=0 cellpadding=3 width='80%'>
				<tr bgcolor="#99ccff">
					<th align="center" colspan="1">投稿日</th>
					<th align="center" colspan="1">処理日</th>
					<th align="center" colspan="1">依頼者</th>
					<th align="center" colspan="1">タイトル</th>
					<th align="center" colspan="1">処理</th>
				</tr>
<?php
$line = 0;
$sql = "SELECT to_char(modify_dt, 'YYYY/MM/DD') as modify_dt, to_char(cho_dt, 'YYYY/MM/DD') as cho_dt, send_mail_addr, com_title, cho_flg, cho_id"
	. " FROM T_CHOICE"
	. " WHERE melonpai_id=$melonpai_id AND to_char(modify_dt, 'YYYYMM')='$yyyymm'"
	. " ORDER BY modify_dt";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$modify_dt = $fetch->modify_dt;
	$modify_dt = $fetch->modify_dt;
	$cho_dt = $fetch->cho_dt;
	$send_mail_addr = $fetch->send_mail_addr;
	$com_title = $fetch->com_title;
	$cho_id = $fetch->cho_id;
	$cho_flg = $fetch->cho_flg;
	if ($cho_dt == "") { $cho_dt = "<br>"; }
	if ($com_title == "") { $cho_name = "<br>"; }
	if ($cho_flg == "0") { $cho_name = "<font color=\"#808080\">処理中</font>"; }
	if ($cho_flg == "1") { $cho_name = "処理済"; }
?>
				<tr class="tc<?= $line % 2; ?>">
					<td align="center"><?= $modify_dt ?></td>
					<td align="center"><?= $cho_dt ?></td>
					<td align="left"><?= htmlspecialchars($send_mail_addr) ?></td>
					<td align="left"><a href='JavaScript:OnClick_com("<?= $cho_id ?>")'><?= htmlspecialchars($com_title) ?></a></td>
					<td align="center"><?= $cho_name ?></td>
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
