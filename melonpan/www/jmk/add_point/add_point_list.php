<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:発行者・読者/ポイント付与
'******************************************************/

$title_text = $TT_add_point;
$title_color = $TC_MASTER;

//項目順ソート
function title($title, $sort, $sort2, $regist_list) {
	if ($sort == $sort2) {
		print "<th><font color='brown'>" . $title  . "</font></th>";
	} else {
		print "<th class='sort' onClick='location.href=\"add_point_list.php?regist_list=" . $regist_list . "&sort2=" . $sort . "\"' align='center'>" . $title . "</th>";
	}
}

if (!$sort2) { $sort2 = 0; }
switch ($sort2) {
	case 0:
		if ($regist_list == "hakkousha") {
			$sort_name = " publisher_id";
		} elseif ($regist_list == "dokusha") {
			$sort_name = " reader_id";
		} elseif ($regist_list == "navi") {
			$sort_name = " melonpai_id";
		}
		break;
	case 1:
		if ($regist_list == "hakkousha") {
			$sort_name = " owner_nm_kana";
		} elseif ($regist_list == "navi") {
			$sort_name = " melonpai_name";
		}
		break;
	case 2:
		if ($regist_list == "hakkousha") {
			$sort_name = " mail_add_main";
		} elseif ($regist_list == "dokusha") {
			$sort_name = " mail_addr";
		} elseif ($regist_list == "navi") {
			$sort_name = " melonpai_mail_addr";
		}
		break;
	case 3:
		if ($regist_list == "hakkousha" || $regist_list == "dokusha") {
			$sort_name = " pt_melonpan DESC";
		} elseif ($regist_list == "navi"){
			$sort_name = " melonpai_point DESC";
		}
		break;
}

//=== Main ===
switch ($regist_list) {
	case "hakkousha":
		$sql = "SELECT publisher_id, owner_nm_kanj, owner_nm_kana, mail_add_main, pt_melonpan FROM M_PUBLISHER"
			. " WHERE publisher_id != 0 ORDER BY" . $sort_name;
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		$title = "発行者一覧";
		break;
	case "dokusha":
		$sql = "SELECT reader_id, mail_addr, pt_melonpan FROM M_MELONPA ORDER BY" . $sort_name;
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		$title = "読者一覧";
		break;
	case "navi":
		$sql = "SELECT melonpai_id, melonpai_name, melonpai_mail_addr, melonpai_point FROM M_MELONPAI ORDER BY" . $sort_name;
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		$title = "メルマガナビ一覧";
		break;
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
  win = window.open("../melmaga_detail/mag_detail1.php?mag_id=" + mag_id, "info_mag", "scrollbars=yes,resizable=yes,width=500,height=700");
  win.focus();
}
function OnClick_pub(pub_id) {
  var win;
  win = window.open("../melmaga_detail/publisher.php?pub_id=" + pub_id, "info_pub", "scrollbars=yes,resizable=yes,width=600,height=500");
  win.focus();
}
function OnClick_mymelonpan(reader_id) {
  var win;
  win = window.open("../melmaga_detail/mymelonpan.php?reader_id=" + reader_id, "info_project", "scrollbars=yes,resizable=yes,width=500,height=380");
  win.focus();
}
function OnClick_melonpai(melonpai_id) {
  var win;
  win = window.open("../melmaga_detail/melonpai.php?melonpai_id=" + melonpai_id, "info_melonpai", "scrollbars=yes,resizable=yes,width=650,height=400");
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
			<td>■<?= $title ?> <font size=-1>（※項目名をクリックするとソートできます。）</font></td>
			<td align="right">該当件数：<span id="rec_count"></span>件 <input type="button" value=" 戻る " onclick="location.href='index.php'"></td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=1 width='80%'>
		<tr bgcolor="#ffcc99">
<?php
title("ID", 0, $sort2, $regist_list);
if ($regist_list != "dokusha") { title("登録名", 1, $sort2, $regist_list); }
title("メールアドレス", 2, $sort2, $regist_list);
title("ポイント残", 3, $sort2, $regist_list);
?>
		</tr>
<?php
$line = 0;
if ($regist_list == "hakkousha") {
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$publisher_id = $fetch->publisher_id;
		$owner_nm_kanj = $fetch->owner_nm_kanj;
		$owner_nm_kana = $fetch->owner_nm_kana;
		$mail_add_main = $fetch->mail_add_main;
		$pt_melonpan = $fetch->pt_melonpan;
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><a href="add_point1.php?regist_list=<?= $regist_list ?>&id=<?= $publisher_id ?>")'><nobr><?= $publisher_id ?></nobr></a></td>
			<td align="left"><a href='JavaScript:OnClick_pub("<?= $publisher_id ?>")'><nobr><?= htmlspecialchars($owner_nm_kanj) ?></nobr></a></td>
			<td align="left"><nobr><?= htmlspecialchars($mail_add_main) ?></nobr></td>
			<td align="right"><?= number_format($pt_melonpan) ?></nobr></td>
		</tr>
	<?php
		$line++;
	}
} elseif ($regist_list == "dokusha") {
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$reader_id = $fetch->reader_id;
		$mail_addr = $fetch->mail_addr;
		$pt_melonpan = $fetch->pt_melonpan;
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><a href="add_point1.php?regist_list=<?= $regist_list ?>&id=<?= $reader_id ?>")'><nobr><?= $reader_id ?></nobr></a></td>
			<td align="left"><a href='JavaScript:OnClick_mymelonpan("<?= $reader_id ?>")'><nobr><?= htmlspecialchars($mail_addr) ?></nobr></a></td>
			<td align="right"><?= number_format($pt_melonpan) ?></nobr></td>
		</tr>
	<?php
		$line++;
	}
} elseif ($regist_list == "navi") {
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$melonpai_id = $fetch->melonpai_id;
		$melonpai_name = $fetch->melonpai_name;
		$melonpai_mail_addr = $fetch->melonpai_mail_addr;
		$melonpai_point = $fetch->melonpai_point;
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><a href="add_point1.php?regist_list=<?= $regist_list ?>&id=<?= $melonpai_id ?>")'><nobr><?= $melonpai_id ?></nobr></a></td>
			<td align="left"><a href='JavaScript:OnClick_melonpai("<?= $melonpai_id ?>")'><nobr><?= htmlspecialchars($melonpai_name) ?></nobr></a></td>
			<td align="left"><nobr><?= replace_br(htmlspecialchars($melonpai_mail_addr)) ?></nobr></td>
			<td align="right"><?= number_format($melonpai_point) ?></nobr></td>
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
