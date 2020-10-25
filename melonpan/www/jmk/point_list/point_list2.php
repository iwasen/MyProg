<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :¤á¤í¤ó¤Ñ¤ó»öÌ³¶ÉÍÑ¥Ú¡¼¥¸
' Content:¥Ý¥¤¥ó¥È¾õ¶·±ÜÍ÷
'******************************************************/

$title_text = $TT_point_list;
$title_color = $TC_MASTER;

if (!$info_year) {
	$info_year = $year;
}
if (!$info_month) {
	$info_month = $month;
}
$yyyymm =  $info_year . sprintf("%02d", $info_month);

//¹àÌÜ½ç¥½¡¼¥È
function title($title, $sort, $sort2, $cd, $info_year, $info_month) {
	if ($sort == $sort2) {
		print "<th><font color='brown'>" . $title  . "</font></th>";
	} else {
		print "<th class='sort' onClick='location.href=\"point_list2.php?sort2=" . $sort . "&cd=" . $cd . "&info_year=" . $info_year . "&info_month=" . $info_month . "\"' align='center'>" . $title . "</th>";
	}
}
if (!$sort2) { $sort2 = 0; }

//=== Main ===
$sql = "SELECT * FROM M_POINT_ATTRIBUTION WHERE attribution_cd='$cd'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$attribution = $fetch->attribution;
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
function OnClick_info(kind2, id) {
	if (kind2 == "2") {
	  var win;
	  win = window.open("../melmaga_detail/publisher.php?pub_id=" + id, "info_pub", "scrollbars=yes,resizable=yes,width=600,height=500");
	  win.focus();
	}
	if (kind2 == "1") {
	  var win;
	  win = window.open("../melmaga_detail/mymelonpan.php?reader_id=" + id, "info_mymelonpan", "scrollbars=yes,resizable=yes,width=500,height=380");
	  win.focus();
	}
	if (kind2 == "3") {
	  var win;
	  win = window.open("../melmaga_detail/melonpai.php?melonpai_id=" + id, "info_melonpai", "scrollbars=yes,resizable=yes,width=650,height=400");
	  win.focus();
	}
}
function OnClick_history(kind2, id) {
  var win;
  win = window.open("../melmaga_detail/point_history.php?kind=" + kind2 + "&id=" + id, "history", "scrollbars=yes,resizable=yes,width=700,height=500");
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
		<td>¢£¡Ú<?= $cd ?>:<?= $attribution ?>¡Û
			<select name="info_year"><? select_year(2000, '', date($info_year)); ?></select>Ç¯
			<select name="info_month" onchange="submit()"><? select_month('', date($info_month)); ?></select>·î
		</td>
		<td align="right">
		  <input type="button" value=" Ìá¤ë "onclick="location.href='point_list1.php?year=<?= $info_year ?>&month=<?= $info_month ?>'">
		</td>
	</tr>
<table border=1 cellspacing=0 cellpadding=1 width='80%'>
	<tr bgcolor="#ffcc99">
<?php
title("ÆüÉÕ", 0, $sort2, $cd, $info_year, $info_month);
title("ID", 1, $sort2, $cd, $info_year, $info_month);
title("ÅÐÏ¿Ì¾", 2, $sort2, $cd, $info_year, $info_month);
title("¥á¡¼¥ë¥¢¥É¥ì¥¹", 3, $sort2, $cd, $info_year, $info_month);
title("¥Ý¥¤¥ó¥ÈÁý¸º", 4, $sort2, $cd, $info_year, $info_month);
?>
	</tr>
<?
$line = 0; $total = 0;
if ($cd >= '01' && $cd <= '11') {
	switch ($sort2) {
		case 0:
			$sort_name = " ORDER BY trans_date DESC";
			break;
		case 1:
			$sort_name = " ORDER BY publisher_id";
			break;
		case 2:
			$sort_name = " ORDER BY owner_nm_kana";
			break;
		case 3:
			$sort_name = " ORDER BY mail_add_main";
			break;
		case 4:
			$sort_name = " ORDER BY trans_point DESC";
			break;
	}

	$kind2 = "2";
	$sql = "SELECT to_char(trans_date, 'YYYY/MM/DD') as trans_date, owner_kind, owner_id, trans_point, owner_nm_kanj, owner_nm_kana, mail_add_main"
	. " FROM T_POINT_DETAIL LEFT JOIN M_PUBLISHER ON owner_id=publisher_id"
	. " WHERE to_char(trans_date, 'YYYYMM')='$yyyymm' AND attribution_cd='$cd'" . $sort_name;
/*
	$sql = "SELECT to_char(trans_date, 'YYYY/MM/DD') as trans_date, owner_kind, owner_id, trans_point, owner_nm_kanj, owner_nm_kana, mail_add_main"
	. " FROM T_POINT_DETAIL, M_PUBLISHER"
	. " WHERE to_char(trans_date, 'YYYYMM')='$yyyymm' AND attribution_cd='$cd' AND owner_id=publisher_id(+)" . $sort_name;
*/
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$trans_date = $fetch->trans_date;
		$owner_kind = $fetch->owner_kind;
		$id = $fetch->owner_id;
		$owner_nm_kanj = $fetch->owner_nm_kanj;
		$owner_nm_kana = $fetch->owner_nm_kana;
		$mail_add_main = $fetch->mail_add_main;
		$trans_point = $fetch->trans_point;
		if ($owner_nm_kanj == "") {
			$owner_nm_kanj = "¡ãÂà²ñ¡ä";
		} else {
			$owner_nm_kanj = "<a href='JavaScript:OnClick_info(" . $kind2 . "," . $id . ")'>" . $owner_nm_kanj . "</a>";
		}
		if ($mail_add_main == "") { $mail_add_main = "<br>"; }
?>
	<tr class="tc<?= $line % 2; ?>">
		<td align="center"><?= $trans_date ?></td>
		<td align="center"><a href='JavaScript:OnClick_history("<?= $kind2 ?>","<?= $id ?>")'><?= $id ?></a></td>
		<td align="left"><?= $owner_nm_kanj ?></td>
		<td align="left"><?= $mail_add_main ?></td>
<?
		if ($trans_point < 0) { print "<td align='right'><font color='red'>" . number_format($trans_point) . "</a></td>"; }
		else { print "<td align='right'>" . number_format($trans_point) . "</td>"; }
		$total = $total + $trans_point;
?>
	</tr>
<?
	$line++;
	}
?>
	<tr bgcolor="#FFFFFF">
		<th align="center" colspan=4>·×</th>
<?
		if ($total < 0) { print "<th align='right'><font color='red'>" . number_format($total) . "</font></th>"; }
		else { print "<th align='right'>" . number_format($total) . "</th>"; }
?>
	</tr>
<?php
} elseif ($cd >= '20' && $cd <= '31') {
	switch ($sort2) {
		case 0:
			$sort_name = " ORDER BY trans_date DESC";
			break;
		case 1:
			$sort_name = " ORDER BY reader_id";
			break;
		case 2:
			break;
		case 3:
			$sort_name = " ORDER BY mail_addr";
			break;
		case 4:
			$sort_name = " ORDER BY trans_point DESC";
			break;
	}

	$kind2 = "1";
/* **
	$sql = "SELECT to_char(trans_date, 'YYYY/MM/DD') as trans_date, owner_kind, owner_id, trans_point, mail_addr"
		. " FROM T_POINT_DETAIL, M_MELONPA"
		. " WHERE to_char(trans_date, 'YYYYMM')='$yyyymm' AND attribution_cd='$cd' AND owner_id=reader_id(+)" . $sort_name;
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
*/
	$sql = "SELECT to_char(trans_date, 'YYYY/MM/DD') as trans_date, owner_kind, owner_id, trans_point, mail_addr"
		. " FROM T_POINT_DETAIL LEFT JOIN M_MELONPA ON owner_id=reader_id"
		. " WHERE to_char(trans_date, 'YYYYMM')='$yyyymm' AND attribution_cd='$cd'" . $sort_name;
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$trans_date = $fetch->trans_date;
		$owner_kind = $fetch->owner_kind;
		$id = $fetch->owner_id;
		$mail_addr = $fetch->mail_addr;
		$trans_point = $fetch->trans_point;
		if ($mail_addr == "") {
			$mail_addr = "¡ãÂà²ñ¡ä";
		} else {
			$mail_addr = "<a href='JavaScript:OnClick_info(" . $kind2 . "," . $id . ")'>" . $mail_addr . "</a>";
		}
?>
	<tr class="tc<?= $line % 2; ?>">
		<td align="center"><?= $trans_date ?></td>
		<td align="center"><a href='JavaScript:OnClick_history("<?= $kind2 ?>","<?= $id ?>")'><?= $id ?></a></td>
		<td align="left"><br></td>
		<td align="left"><?= $mail_addr ?></td>
<?
		if ($trans_point < 0) { print "<td align='right'><font color='red'>" . number_format($trans_point) . "</a></td>"; }
		else { print "<td align='right'>" . number_format($trans_point) . "</td>"; }
?>
	</tr>
<?php
	$total = $total + $trans_point;
	$line++;
	}
?>
	<tr bgcolor="#FFFFFF">
		<th align="center" colspan=4>·×</th>
<?
		if ($total < 0) { print "<th align='right'><font color='red'>" . number_format($total) . "</font></th>"; }
		else { print "<th align='right'>" . number_format($total) . "</th>"; }
?>
	</tr>
<?
} elseif ($cd >= '51' && $cd <= '61') {
	switch ($sort2) {
		case 0:
			$sort_name = " ORDER BY trans_date DESC";
			break;
		case 1:
			$sort_name = " ORDER BY melonpai_id";
			break;
		case 2:
			$sort_name = " ORDER BY melonpai_name";
			break;
		case 3:
			$sort_name = " ORDER BY melonpai_mail_addr";
			break;
		case 4:
			$sort_name = " ORDER BY trans_point DESC";
			break;
	}

	$kind2 = "3";
/* **
	$sql = "SELECT to_char(trans_date, 'YYYY/MM/DD') as trans_date, owner_kind, owner_id, trans_point, melonpai_mail_addr, melonpai_name"
		. " FROM T_POINT_DETAIL, M_MELONPAI"
		. " WHERE to_char(trans_date, 'YYYYMM')='$yyyymm' AND attribution_cd='$cd' AND owner_id=melonpai_id(+)" . $sort_name;
*/
	$sql = "SELECT to_char(trans_date, 'YYYY/MM/DD') as trans_date, owner_kind, owner_id, trans_point, melonpai_mail_addr, melonpai_name"
		. " FROM T_POINT_DETAIL LEFT JOIN M_MELONPAI ON owner_id=melonpai_id"
		. " WHERE to_char(trans_date, 'YYYYMM')='$yyyymm' AND attribution_cd='$cd'" . $sort_name;

	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$trans_date = $fetch->trans_date;
		$owner_kind = $fetch->owner_kind;
		$id = $fetch->owner_id;
		$melonpai_name = $fetch->melonpai_name;
		$melonpai_mail_addr = $fetch->melonpai_mail_addr;
		$trans_point = $fetch->trans_point;
		if ($melonpai_name == "") {
			$melonpai_name = "¡ãÂà²ñ¡ä";
		} else {
			$melonpai_name = "<a href='JavaScript:OnClick_info(" . $kind2 . "," . $id . ")'>" . $melonpai_name . "</a>";
		}
		if ($melonpai_mail_addr == "") { $melonpai_mail_addr = "<br>"; }
?>
	<tr class="tc<?= $line % 2; ?>">
		<td align="center"><?= $trans_date ?></td>
		<td align="center"><a href='JavaScript:OnClick_history("<?= $kind2 ?>","<?= $id ?>")'><?= $id ?></a></td>
		<td align="left"><?= $melonpai_name ?></td>
		<td align="left"><?= $melonpai_mail_addr ?></td>
<?
		if ($trans_point < 0) { print "<td align='right'><font color='red'>" . number_format($trans_point) . "</a></td>"; }
		else { print "<td align='right'>" . number_format($trans_point) . "</td>"; }
?>
	</tr>
<?php
	$total = $total + $trans_point;
	$line++;
	}
?>
	<tr bgcolor="#FFFFFF">
		<th align="center" colspan=4>·×</th>
<?
		if ($total < 0) { print "<th align='right'><font color='red'>" . number_format($total) . "</font></th>"; }
		else { print "<th align='right'>" . number_format($total) . "</th>"; }
?>
	</tr>
<?
}
?>
</table>
  <input type="button" value=" Ìá¤ë "onclick="location.href='point_list1.php?kind1=<?= $kind1 ?>&year=<?= $info_year ?>&month=<?= $info_month ?>'">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
