<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/crontab_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:バッチスケジュール
'******************************************************/

$title_text = $TT_batch_schedule;
$title_color = $TC_MASTER_MAINTE;

$sql = "SELECT * FROM M_SH_LIST";
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
	<table border=0 cellspacing=0 cellpadding=0 width='90%'>
		<tr>
			<td>■バッチ一覧</td>
			<td align="right"><input type="button" value=" 戻る " onclick="location.href='../menu.php'"></td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=1 width='90%'>
		<tr bgcolor="#ffcc99">
			<th align="center"><nobr>バッチ</nobr></th>
			<th align="center"><nobr>スケジュール</nobr></th>
			<th align="center"><nobr>機能</nobr></th>
		</tr>
<?
# crontab読込み
$cron_list = array();
read_crontab($CRONTAB_NAME, $cron_list);

# sh 一覧
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$sh_nm = $fetch->sh_name;
	$guide = $fetch->summary;
	$sh_nm = trim($sh_nm);
	$param = array();
	if (check_cron($cron_list, $sh_nm, $param)){
		$pm = "sh=$sh_nm&p1=$param[0]&p2=$param[1]&p3=$param[2]&p4=$param[3]&p5=$param[4]";
		$sch = "$param[0] $param[1] $param[2] $param[3] $param[4]";
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="left"><a href="batch_schedule1.php?<?= $pm ?>"><?= $sh_nm ?></a></td>
			<td align="left"><?= $sch ?></td>
			<td align="left"><?= $guide ?></td>
		</tr>
<?
	}
$line++;
}
?>
	</table>
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
