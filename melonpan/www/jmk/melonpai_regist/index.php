<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:メルマガナビ登録
'******************************************************/

$title_text = $TT_melonpai_regist;
$title_color = $TC_DOKUSHA;


$sql = "SELECT melonpai_id"
		. ", melonpai_name"
		. ", melonpai_m_flg"
		. ", TO_CHAR(modify_dt, 'YYYY') as modi_year"
		. ", TO_CHAR(modify_dt, 'MM') as modi_month"
		. ", TO_CHAR(modify_dt, 'DD') as modi_day"
		.	" FROM M_MELONPAI"
		. " ORDER BY melonpai_id";
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

<center>
<form method="post" name="form1" style="margin:0">
<table border=0 cellspacing=0 cellpadding=0 width='80%'>
	<tr>
		<td>■メルマガナビ登録一覧</td>
		<td align="right">
			<input type="button" value="新規登録" onclick="location.href='melonpai_regist1.php'">
			<input type="button" value=" 戻る " onclick="location.href='../menu.php'">
		</td>
	</tr>
</table>
<table border=0 cellspacing=0 cellpadding=1 width='80%'>
	<tr>
		<td>
			<table border=1 cellspacing=0 cellpadding=2 width='100%'>
				<tr bgcolor="#ffcc99">
					<th width="10%" align="center"><nobr>ＩＤ</nobr></th>
					<th width="50%" align="center"><nobr>氏名</nobr></th>
					<th width="15%" align="center"><nobr>活動状況</nobr></th>
					<th width="25%" align="center"><nobr>登録日</nobr></th>
				</tr>
<?php
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$melonpai_id = $fetch->melonpai_id;
	$melonapi_name = $fetch->melonpai_name;
	$melonpai_flg = $fetch->melonpai_m_flg;
	$modi_year = $fetch->modi_year;
	$modi_month = $fetch->modi_month;
	$modi_day = $fetch->modi_day;
	if ($melonpai_flg == '0') {
		$melonpai_flg = "活動中";
	} elseif ($melonpai_flg	 == '1'){
		$melonpai_flg = "休止中";
	} elseif ($melonpai_flg	 == '2'){
		$melonpai_flg = "停止";
	}
?>
				<tr class="tc<?= $line % 2; ?>">
					<td align="center"><a href='melonpai_regist2.php?melonpai_id=<?= $melonpai_id ?>'><?= $melonpai_id ?></a></td>
					<td align="left"><?= htmlspecialchars($melonapi_name) ?></td>
					<td align="center"><?= $melonpai_flg ?></td>
					<td align="center"><? print $modi_year . "/" . $modi_month . "/" . $modi_day; ?></td>
				</tr>
<?php
$line++;
}

?>
			</table>
		</td>
	</tr>
</table>
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
