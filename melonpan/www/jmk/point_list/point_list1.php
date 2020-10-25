<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:ポイント状況閲覧
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

//=== Main ===
switch ($kind1) {
	case 1:
		$where = "WHERE attribution_cd <> '00'";
		$extract = "全て";
		break;
	case 2:
		$where = "WHERE attribution_cd IN ('01','03','05','10','11')";
		$extract = "事務局→発行者";
		break;
	case 3:
		$where = "WHERE attribution_cd IN ('24','30','31')";
		$extract = "事務局→読者";
		break;
	case 4:
		$where = "WHERE attribution_cd IN ('01','03','05','10','11','24','30','31')";
		$extract = "事務局→発行者/読者";
		break;
	case 5:
		$where = "WHERE attribution_cd IN ('04')";
		$extract = "メルマガナビ→発行者";
		break;
	case 6:
		$where = "WHERE attribution_cd IN ('22')";
		$extract = "メルマガナビ→読者";
		break;
	case 7:
		$where = "WHERE attribution_cd IN ('04','22')";
		$extract = "メルマガナビ→発行者/読者";
		break;
}
$sub = "SELECT SUM(trans_point) as total_point, attribution_cd AS T_attribution_cd"
	. " FROM T_POINT_DETAIL WHERE to_char(trans_date, 'YYYYMM')='$yyyymm' GROUP BY attribution_cd";
$sql = "SELECT attribution_cd, attribution, total_point"
	. " FROM M_POINT_ATTRIBUTION LEFT JOIN (" . $sub . ") TP ON attribution_cd=T_attribution_cd " . $where
	. " ORDER BY attribution_cd";
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
<form method="post" name="form1" style="margin:0" action="point_list1.php?kind1=<?= $kind1 ?>" onSubmit="return OnSubmit_form1();">
<table border=0 cellspacing=0 cellpadding=0 width='80%'>
	<tr>
		<td>■ポイント状況一覧【<?= $extract ?>】　
			<select name="info_year"><? select_year(2000, '', date($info_year)); ?></select>年
			<select name="info_month" onchange="submit()"><? select_month('', date($info_month)); ?></select>月
		</td>
			<table border=1 cellspacing=0 cellpadding=1 width='80%'>
				<tr bgcolor="#ffcc99">
					<th align="center">ID</th>
					<th align="center">ポイント内容</th>
					<th align="center">合計</th>
				</tr>
<?
$line = 0; $total_point = 0;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$attribution_cd = $fetch->attribution_cd;
	$attribution = $fetch->attribution;
	$total_point = $fetch->total_point;
	if ($total_point > 0 || $total_point == "") {
		$point_total = number_format($total_point);
	} else {
		$point_total = "<font color=\"red\">" . number_format($total_point) . "</font>";
	}
?>
				<tr class="tc<?= $line % 2; ?>">
					<input type="hidden" name="id[]" value="<?= $mag_id ?>">
					<input type="hidden" name="melonpai_id[]" value="<?= $melonpai_id ?>">
					<td align="center"><a href="point_list2.php?kind1=<?= $kind1 ?>&cd=<?= $attribution_cd ?>&year=<?= $info_year ?>&month=<?= $info_month ?>"><?= $attribution_cd ?></a></td>
					<td align="left"><?= $attribution ?></td>
					<td align="right"><?= $point_total ?></td>
				</tr>
<?php
	$total = $total + $total_point;
	$line++;
}
if ($kind1 != '1') {
	if ($total > 0 || $total == "") {
		$total = number_format($total);
	} else {
		$total = "<font color=\"red\">" . number_format($total) . "</font>";
	}
?>
				<tr bgcolor="#ffffff">
					<th align="center" colspan=2>計</th>
					<th align="right"><?= $total ?></th>
				</tr>
<?
}
?>
			</table>
		</td>
	</tr>
</table>
  <input type="button" value=" 戻る "onclick="location.href='index.php'">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
