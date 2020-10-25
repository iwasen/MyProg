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

$ok = "";

//'=== Main ===
if (count($mag_id) > 0) {
db_begin_trans();
$i = 0;
	while ($id[$i]) {
		if ($id[$i] == $mag_id[$i]) {
			$sql = "UPDATE T_OSUSUME_LIST SET date_to='now' WHERE mag_id='$mag_id[$i]' AND date_to IS NULL AND melonpai_id=$melonpai_id[$i]";
			if (db_exec($sql)) {
				$sql = "UPDATE M_MAGAZINE SET osusume_flg=0, osusume_reason='null' WHERE mag_id='$mag_id[$i]'";
				if ($result = db_exec($sql)) {
					$ok = "ok";
				}
			}
		}
		$i++;
	}
}
if ($ok == "") {
	db_rollback();
	$msg = "処理中にエラーが発生したため、処理できませんでした。";
} else {
	db_commit_trans();
	$msg = "解除しました。";
}
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
<form method="post" name="form1">

<?= $msg ?><br><br>

<input type="button" value=" 戻る " onclick="location.href='index.php?kind=<?= $kind ?>'">
</form>
</center>
<? footer_jmk(0); ?>

</body>
</html>
