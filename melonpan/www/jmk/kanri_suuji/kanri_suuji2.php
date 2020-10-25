<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:管理数字表示画面
'******************************************************/

$title_text = $TT_kanri_suuji;
$title_color = $TC_MASTER;

//'=== 単位を表示 ===
function unit_name($ki_item_name) {
	$pos1 = strpos (trim($ki_item_name), "誌数");
	$pos2 = strpos (trim($ki_item_name), "者数");
	$pos3 = strpos (trim($ki_item_name), "部数");
	if ($pos1) {
		$unit_name = "誌";
	} elseif ($pos2) {
		$unit_name = "名";
	} elseif ($pos3) {
		$unit_name = "部";
	} else {
		$unit_name = "";
	}
	print $unit_name;
}

//'=== Main ====

$kanri_date = sprintf("%04d%02d%02d", $year, $month, $day);

$sql = "SELECT ki_item_no, ki_item_name, kd_number FROM T_KANRI_DATA JOIN M_KANRI_ITEM ON ki_item_no=kd_item_no"
	. " WHERE to_char(kd_date, 'YYYYMMDD')='$kanri_date'"
	. " ORDER BY ki_item_no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<form method="post" name="form1">
	<center>
<table border=0 cellspacing=0 cellpadding=0 width='70%'>
  <tr>
    <td class="m0">■<? print ($year . "/" . $month . "/" . $day ." の日計"); ?>
      <table border=0 cellspacing=2 cellpadding=3 width='80%'>
<?php
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$item_no = $fetch->ki_item_no;
	$ki_item_name = $fetch->ki_item_name;
	$kd_number = $fetch->kd_number;

	if ($item_no <= 20) {
?>
        <tr>
          <td class="m4" width="60%"><nobr><? print $item_no . "." .  htmlspecialchars($ki_item_name); ?></nobr></td>
          <td class="n4" align="right"><nobr><? print number_format($kd_number); ?> <? unit_name($ki_item_name); ?></nobr></td>
        </tr>

<?php
	}
	if ($item_no == 21) {
?>
				<tr>
					<td><br></td>
					<td><br></td>
				</tr>
				<tr>
					<td><br></td>
					<td><br></td>
				</tr>
				<tr>
					<td><br></td>
					<td><br></td>
				</tr>
				<tr>
					<td><br></td>
					<td><br></td>
				</tr>
				<tr>
					<td><br></td>
					<td><br></td>
				</tr>
				<tr>
					<td><br></td>
					<td><br></td>
				</tr>
      </table>
		</td>
    <td class="m0">■<? print ($year . "/" . $month . "/" . $day ); ?> までの累計
      <table border=0 cellspacing=2 cellpadding=3 width='90%'>
<?php
	}
	if ($item_no >= 21) {
		if ($item_no == '31' || $item_no == '35') {
			$kd_number = number_format($kd_number/100, 2);
		} else {
			$kd_number = number_format($kd_number);
		}
?>
        <tr>
          <td class="m4" width="60%"><nobr><? print $item_no . "." .  $ki_item_name; ?></nobr></td>
          <td class="n4" align="right"><nobr><?= $kd_number ?> <? unit_name($ki_item_name); ?></nobr></td>
        </tr>
<?php
	}
}
?>
      </table>
		</td>
	</tr>
</table>
	<br>
		<input type="button" value=" 戻る " onclick="history.back()">
	</center>
<form>

<? footer_jmk(0); ?>

</body>
</html>
