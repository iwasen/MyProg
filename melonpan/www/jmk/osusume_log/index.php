<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:オススメログ一覧
'******************************************************/

$title_text = $TT_osusume_log;
$title_color = $TC_MASTER;

//項目順ソート
function title($title, $sort, $sort2, $kind) {
	if ($sort == $sort2) {
		print "<th rowspan=2><font color='brown'>" . $title  . "</font></th>";
	} else {
		print "<th class='sort' onClick='location.href=\"index.php?sort2=" . $sort . "&kind=" . $kind . "\"' align='center' rowspan=2>" . $title . "</th>";
	}
}

if (!$sort2) { $sort2 = 0; }
switch ($sort2) {
	case 0:
		$sort_name = "sel_date DESC";
		break;
	case 1:
		$sort_name = "melonpai_id";
		break;
	case 2:
		$sort_name = "mag_id";
		break;
}

//表示選択
switch ($kind) {
	case "":
		$where = " AND date_to IS NULL";
		break;
	case "1":
		$where = " AND date_to IS NOT NULL";
		break;
	case "2":
		$where = "";
		break;
}

//=== Main ===
$sql = "SELECT mag_id, melonpai_id, to_char(sel_date, 'YYYY/MM/DD') as sel_date, to_char(date_from, 'YYYY/MM/DD') as date_from"
	. ", to_char(date_to, 'YYYY/MM/DD') as date_to, reason"
	. " FROM T_OSUSUME_LIST WHERE sel_date BETWEEN now() - interval '1 year' AND 'now'" . $where
	. " ORDER BY " . $sort_name;


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
function OnClick_kind(kind) {
  location.href = "index.php?kind=" + kind;
}
function OnClick_mag(mag_id) {
  var win;
  win = window.open("../melmaga_detail/mag_detail1.php?mag_id=" + mag_id, "info_mag", "scrollbars=yes,resizable=yes,width=600,height=700");
  win.focus();
}
function OnClick_melonpai(melonpai_id) {
  var win;
  win = window.open("../melmaga_detail/melonpai.php?melonpai_id=" + melonpai_id, "info_melonpai", "scrollbars=yes,resizable=yes,width=650,height=400");
  win.focus();
}
function OnSubmit_form1() {
  return confirm("解除します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" style="margin:0" action="osusume_log1.php" onSubmit="return OnSubmit_form1();">
<table border=0 cellspacing=0 cellpadding=0 width='100%'>
	<tr>
		<td>■オススメログ一覧</td>
	</tr>
	<tr>
		<td><font size="-1">
			<input type="radio" onclick="OnClick_kind('')" <? If ($kind == "") {print " checked";} ?>>有効期間中のみ
			<input type="radio" onclick="OnClick_kind('1')" <? If ($kind == "1") {print " checked";} ?>>解除済みのみ
			<input type="radio" onclick="OnClick_kind('2')" <? If ($kind == "2") {print " checked";} ?>>全て
			　　（※項目名（選定日/メルマガナビID/メルマガID）をクリックするとソートできます。）
			</font>
		</td>
		<td align="right">
			<input type="button" value=" 戻る " onclick="location.href='../menu.php'">
		</td>
			<table border=1 cellspacing=0 cellpadding=1 width='100%'>
				<tr bgcolor="#ffcc99">
<?php
title("選定日", 0, $sort2, $kind);
title("メルマガナビ<br>ID", 1, $sort2, $kind);
title("メルマガID", 2, $sort2, $kind);
?>
					<th align="center" rowspan="2"><nobr>オススメ理由</nobr></th>
					<th align="center" colspan="2"><nobr>有効期間</nobr></th>
					<th align="center" rowspan="2"><nobr>解除</nobr></th>
				</tr>
				<tr bgcolor="#ffcccc">
					<th align="center"><nobr>From</nobr></th>
					<th align="center"><nobr>To</nobr></th>
				</tr>
<?php
$line = 0; $i = 0;
for ($j = 0; $j < $nrow; $j++) {
	$fetch = pg_fetch_object($result, $i);
	$mag_id = $fetch->mag_id;
	$melonpai_id = $fetch->melonpai_id;
	$sel_date = $fetch->sel_date;
	$date_from = $fetch->date_from;
	$date_to = $fetch->date_to;
	$reason = $fetch->reason;

?>
				<tr class="tc<?= $line % 2; ?>">
					<input type="hidden" name="id[]" value="<?= $mag_id ?>">
					<input type="hidden" name="melonpai_id[]" value="<?= $melonpai_id ?>">
					<td align="center"><?= $sel_date ?></td>
					<td align="center"><a href='JavaScript:OnClick_melonpai("<?= $melonpai_id ?>")'><?= $melonpai_id ?></a></td>
					<td align="center"><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= $mag_id ?></a></td>
					<td align="left"><font size=-1><?= htmlspecialchars($reason) ?></font></td>
					<td align="center"><?= replace_br($date_from) ?></td>
					<td align="center"><?= replace_br($date_to) ?></td>
<?
	if ($date_to == "") {
?>
					<td align="center"><input type="checkbox" name="mag_id[<?= $i ?>]" value="<?= $mag_id ?>"></td>
<?
	} else {
?>
					<td><br></td>
<?
	}
?>
				</tr>
<?php
$line++; $i++;
}
?>
			</table>
		</td>
	</tr>
</table>
<?
if ($kind <> "1") {
?>
	<input type="hidden" name="kind" value="<?= $kind ?>">
  <input type="submit" value=" 解除 ">
  <input type="reset" value="リセット">
  <input type="button" value=" 戻る "onclick="location.href='../menu.php'">
<?
}?>
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
