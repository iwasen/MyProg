<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:読者検索/代理解除処理
'******************************************************/

$title_text = $TT_addr_search;
$title_color = $TC_DOKUSHA;

$ok == "";

//'=== Main ===
if (count($mail_id) <= 0) {
	$msg = "データが選択されていないようです。ご確認ください。";
	$flg = 1;
} else {
	db_begin_trans();
	$mail_id_list = implode (",", $mail_id);
	$sql = "SELECT mag_id, reader_id, mail_id FROM T_MAILADDR WHERE mail_id IN (" . $mail_id_list . ")";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mag_id = $fetch->mag_id;
		$reader_id = $fetch->reader_id;
		$mail_id = $fetch->mail_id;

		//T_MAILADDRから削除
		$sql = "DELETE FROM T_MAILADDR WHERE mail_id=$mail_id";
		if (db_exec($sql)) {
			if ($reader_id == 0 || $reader_id == "") { $reader_id = ""; }
			if (($delete_id = update_squ_key ("T_DELREASON")) != "-1") {
				$reason = "事務局による代理解除";
				//解除理由を追加
				$sql = "INSERT INTO T_DELREASON (delete_id, mag_id, reader_id, reason, modify_dt)"
					. " VALUES ($delete_id, '$mag_id', " . sql_number($reader_id) . ", '$reason', 'now')";
				if (!db_exec($sql)) {
					exit;
				} else {
					$ok = "ok";
				}
			}
		}
	}
}
if ($flg != 1) {
	if ($ok == "") {
		db_rollback();
		$msg = "処理中にエラーが発生したため、処理できませんでした。";
	} else {
		db_commit_trans();
		$msg = "削除しました。";
	}
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

<?
if ($flg == 0) {
?>
<input type="button" value=" 戻る " onclick="location.href='addr_search1.php'">
<?
} elseif ($flg == 1) {
?>
<input type="button" value=" 戻る " onclick="history.back()">

<?
}
?>
</form>
</center>
<? footer_jmk(0); ?>


</body>
</html>
