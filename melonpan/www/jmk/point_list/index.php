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
	$info_year = date("Y");
}

//=== Main ===
$sub1 = "SELECT SUM(trans_point) as jpub_point, to_char(trans_date, 'MM') as jp_trans_month FROM T_POINT_DETAIL"
	. " WHERE to_char(trans_date, 'YYYY')='$info_year' and attribution_cd IN ('01', '03', '05', '10', '11') GROUP BY TO_CHAR(trans_date, 'MM')";
$sub2 = "SELECT SUM(trans_point) as jdoku_point, to_char(trans_date, 'MM') as jd_trans_month FROM T_POINT_DETAIL"
	. " WHERE to_char(trans_date, 'YYYY')='$info_year' and attribution_cd IN ('24', '30', '31') GROUP BY TO_CHAR(trans_date, 'MM')";
$sub3 = "SELECT SUM(trans_point) as npub_point, to_char(trans_date, 'MM') as np_trans_month FROM T_POINT_DETAIL"
	. " WHERE to_char(trans_date, 'YYYY')='$info_year' and attribution_cd = '04' GROUP BY TO_CHAR(trans_date, 'MM')";
$sub4 = "SELECT SUM(trans_point) as ndoku_point, to_char(trans_date, 'MM') as nd_trans_month FROM T_POINT_DETAIL"
	. " WHERE to_char(trans_date, 'YYYY')='$info_year' and attribution_cd = '22' GROUP BY TO_CHAR(trans_date, 'MM')";
$sql = "SELECT distinct(to_char(trans_date, 'MM')) as trans_month, jpub_point, jdoku_point, npub_point, ndoku_point"
	. " FROM T_POINT_DETAIL LEFT JOIN (" . $sub1 . ") as JP ON to_char(trans_date, 'MM') = jp_trans_month"
	. " LEFT JOIN (" . $sub2 . ") as JD ON to_char(trans_date, 'MM') = jd_trans_month"
	. " LEFT JOIN (" . $sub3 . ") as NP ON to_char(trans_date, 'MM') = np_trans_month"
	. " LEFT JOIN (" . $sub4 . ") as ND ON to_char(trans_date, 'MM') = nd_trans_month"
	. " ORDER BY trans_month DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$abc = $fetch->abc;
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
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" style="margin:0" action="index.php">
<table border=0 cellspacing=0 cellpadding=0 width='80%'>
	<tr>
		<td>■ポイント状況一覧
			<select name="info_year" onchange="submit()"><? select_year(2000, '', date($info_year)); ?></select>年
		</td>
	</tr>
</table>
<table border=1 cellspacing=0 cellpadding=1 width='80%'>
	<tr bgcolor="#ffcc99">
		<th align="center" rowspan=2 width=10%>年月</th>
		<th align="center" colspan=3 width=30%>事務局</th>
		<th align="center" colspan=3 width=30%>メルマガナビ</th>
		<th align="center" colspan=3 width=30%>合　計</th>
	</tr>
	<tr>
		<th bgcolor="#ddffff" width=10%>発行者</th>
		<th bgcolor="#ddffdd" width=10%>読　者</th>
		<th bgcolor="#ffdddd" width=10%>計</th>
		<th bgcolor="#ddffff" width=10%>発行者</th>
		<th bgcolor="#ddffdd" width=10%>読　者</th>
		<th bgcolor="#ffdddd" width=10%>計</th>
		<th bgcolor="#ddffff" width=10%>発行者</th>
		<th bgcolor="#ddffdd" width=10%>読　者</th>
		<th bgcolor="#ffdddd" width=10%>計</th>
	</tr>
<?
$line = 0;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$trans_month = $fetch->trans_month;
	$jpub_point = $fetch->jpub_point;
	$jdoku_point = $fetch->jdoku_point;
	$npub_point = $fetch->npub_point;
	$ndoku_point = $fetch->ndoku_point;
	$jim_kei = $jpub_point + $jdoku_point;
	$navi_kei = $npub_point + $ndoku_point;
	$pub_kei = $jpub_point + $npub_point;
	$doku_kei = $jdoku_point + $ndoku_point;
	$total = $pub_kei + $doku_kei;

?>
	<tr class="tc<?= $line % 2; ?>">
		<td align="center"><nobr><a href='point_list1.php?kind1=1&year=<?= $info_year ?>&month=<?= $trans_month ?>'><?= $info_year . "年" . $trans_month . "月" ?></nobr></td>
		<td align="right"><a href='point_list1.php?kind1=2&year=<?= $info_year ?>&month=<?= $trans_month ?>'><?= number_format($jpub_point) ?></a></td>
		<td align="right"><a href='point_list1.php?kind1=3&year=<?= $info_year ?>&month=<?= $trans_month ?>'><?= number_format($jdoku_point) ?></a></td>
		<td align="right"><a href='point_list1.php?kind1=4&year=<?= $info_year ?>&month=<?= $trans_month ?>'><?= number_format($jim_kei) ?></a></td>
		<td align="right"><a href='point_list1.php?kind1=5&year=<?= $info_year ?>&month=<?= $trans_month ?>'><?= number_format($npub_point) ?></a></td>
		<td align="right"><a href='point_list1.php?kind1=6&year=<?= $info_year ?>&month=<?= $trans_month ?>'><?= number_format($ndoku_point) ?></a></td>
		<td align="right"><a href='point_list1.php?kind1=7&year=<?= $info_year ?>&month=<?= $trans_month ?>'><?= number_format($navi_kei) ?></a></td>
		<td align="right"><?= number_format($pub_kei) ?></td>
		<td align="right"><?= number_format($doku_kei) ?></td>
		<td align="right"><?= number_format($total) ?></td>
	</tr>
<?
$line++;
}
?>
</table>
<?
if ($kind <> "1") {
?>
  <input type="button" value=" 戻る "onclick="location.href='../menu.php'">
<?
}?>
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
