<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:読者登録数差分/ポイント付与
'******************************************************/

$title_text = $TT_sabun_point;
$title_color = $TC_MASTER;

//項目順ソート
function title($title, $sort, $sort2) {
	if ($sort == $sort2) {
		print "<th><font color='brown'><nobr>" . $title  . "</nobr></font></th>";
	} else {
		print "<th class='sort' onClick='location.href=\"index.php?sort2=" . $sort . "\"' align='center'><nobr>" . $title . "</nobr></th>";
	}
}

if ($sort2 == "") { $sort2 = 5; }
switch ($sort2) {
	case 0:
		$sort_name = " MM.mag_id";
		break;
	case 1:
		$sort_name = " MM.mag_nm";
		break;
	case 2:
		$sort_name = " MP.owner_nm_kana";
		break;
	case 3:
		$sort_name = " reg1_max_volume DESC";
		break;
	case 4:
		$sort_name = " mag_volume1 DESC";
		break;
	case 5:
		$sort_name = " sabun_volume DESC";
		break;}

//=== Main ===
$sub1 = "SELECT mag_id as mag_id2, SUM(reg_flg) as mag_volume1 FROM T_MAILADDR GROUP BY mag_id ORDER BY mag_volume1 DESC";
$sql = "SELECT MM.mag_id, coalesce(MM.reg1_max_volume, 0) as reg1_max_volume, mag_volume1, (mag_volume1 - coalesce(reg1_max_volume, 0)) as sabun_volume, MM.mag_nm, MM.publisher_id, MP.owner_nm_kanj"
	. " FROM M_MAGAZINE MM, M_PUBLISHER MP, (" . $sub1 . ") as vl"
	. " WHERE coalesce(reg1_max_volume, 0) < mag_volume1 AND MM.publisher_id != 0 AND MM.mag_status_flg IN ('00','01')"
	. " AND MM.mag_pub_status_flg IN ('00','01','03') AND MM.publisher_id=MP.publisher_id AND MM.mag_id=mag_id2"
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
function OnLoad_body() {
  document.form1.tanka.focus();
}
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
function OnClick_calc() {
	var n = document.form1.sabun_volume.length;
	var fm = document.form1;
	for (var i = 0; i < n; i++)
		fm("total[" + i + "]").value = Math.round(fm.sabun_volume[i].value * fm.tanka.value);
}
function OnClick_allcheak(check) {
	var n = document.form1.sabun_volume.length;
	for (var i = 0; i < n; i++)
		document.form1("chk[" + i + "]").checked = check;
}
function OnSubmit_form1() {
  return confirm("実行します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form style="margin:0" method="post" name="form1" action="sabun_point.php" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='100%'>
		<tr>
			<td>■読者登録数差分一覧　　
				<font size=-1>ポイント単価：<input type="text" size=3 DIR=rtl name=tanka maxlength=4 ></font>
				<input type="button" value="計算" OnClick='JavaScript:OnClick_calc()'></td>
			<td align="right"><font size=-1>該当件数：<span id="rec_count"></span>件 </font>
				<input type="button" value="ｵｰﾙﾁｪｯｸ" OnClick='JavaScript:OnClick_allcheak(true)'>
				<input type="button" value=" ｸﾘｱ " OnClick='JavaScript:OnClick_allcheak(false)'>
			</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=1 width='100%'>
		<tr bgcolor="#ffcc99">
<?php
title("ﾒﾙﾏｶﾞID", 0, $sort2);
title("メルマガ名", 1, $sort2);
title("発行者", 2, $sort2);
title("最大登録者数", 3, $sort2);
title("現在登録者数", 4, $sort2);
title("増加数", 5, $sort2);

?>
			<th align="center"><nobr>付与ﾎﾟｲﾝﾄ</nobr></th>
			<th align="center"><nobr>処理</nobr></th>
		</tr>
<?php
$line = 0;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$mag_id = $fetch->mag_id;
	$owner_nm_kanj = $fetch->owner_nm_kanj;
	$publisher_id = $fetch->publisher_id;
	$mag_nm = $fetch->mag_nm;
	$reg1_max_volume = $fetch->reg1_max_volume;
	$mag_volume = $fetch->mag_volume1;
	$sabun_volume = $fetch->sabun_volume;
?>
		<tr class="tc<?= $line % 2; ?>">
			<input type="hidden" name="sabun_volume" value=<?= $sabun_volume ?>>
			<input type="hidden" name="mag_volume[]" value=<?= $mag_volume ?>>
			<input type="hidden" name="publisher_id[]" value=<?= $publisher_id ?>>
			<input type="hidden" name="mag_id[]" value=<?= $mag_id ?>>
			<td align="center"><?= $mag_id ?></td>
			<td align="left"><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= htmlspecialchars($mag_nm) ?></a></td>
			<td align="left"><a href='JavaScript:OnClick_pub(<?= $publisher_id ?>)'><?= htmlspecialchars($owner_nm_kanj) ?></a></td>
			<td align="right"><font color="black"><?= number_format($reg1_max_volume) ?></font></td>
			<td align="right"><font color="black"><?= number_format($mag_volume) ?></fon></td>
			<td align="right"><font color="black"><?= number_format($sabun_volume) ?></font></td>
			<td align="center"><input type="text" name="total[<?= $line ?>]" size=10 maxlength=10 DIR=rtl value=<?= $sabun_volume ?>></td>
			<td align="center"><input type="checkbox" name="chk[<?= $line ?>]"></td>
		</tr>
<?php
	$line++;
}
?>
	</table><br>
  <input type="submit" value=" 実行 ">
  <input type="reset" value="リセット">
  <input type="button" value=" 戻る " onclick="location.href='../menu.php'">
</form>
</center>
<SCRIPT LANGUAGE=javascript>
<!--
document.all.rec_count.innerHTML = <?= $nrow; ?>;
//-->
</SCRIPT>
<? footer_jmk(0); ?>

</body>
</html>
