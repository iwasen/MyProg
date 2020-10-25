<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:過去のレッドカードリスト
'******************************************************/

$title_text = "過去のレッドカードリスト";
$title_color = $TC_HAKKOUSHA;

$mag_id = sprintf("%06d", $mag_id);
$ok = "";

db_begin_trans();
$sql = "SELECT mag_id FROM M_MAGAZINE WHERE mag_id='$mag_id' AND mag_status_flg!='99' AND mag_pub_status_flg!='99'";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow <= 0) {
	$msg = "データが既に更新されているようです。ご確認ください。";
} else {

	//M_MAGAZINE 廃刊処理
	$sql = "UPDATE M_MAGAZINE SET mag_status_flg='99', mag_pub_status_flg='99', modefy_dt='now' WHERE mag_id='$mag_id'";
	if (db_exec($sql)) {

		//T_MAILADDR 購読解除
		$sql = "DELETE FROM T_MAILADDR WHERE mag_id='$mag_id'";
		if (db_exec($sql)) {

			//T_MAG_DELREASONに解除理由追加
			$mag_del_id = update_squ_key ("T_MAG_DELREASON");
			$reason = "事務局による代理解除";
			$sql = "INSERT INTO T_MAG_DELREASON (mag_del_id, mag_id, reason, modefy_dt) VALUES ($mag_del_id, '$mag_id', '$reason', 'now')";
			db_exec($sql);
			$ok = "ok";
		}
	}
}
if ($ok != "") {
	db_commit_trans();
	$msg = "メルマガＩＤ：" . $mag_id . "の廃刊処理を実行しました。";
} else {
	db_rollback();
	$msg = "処理中にエラーが発生したため、処理できませんでした。";
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

<input type="button" value=" 戻る " onclick="location.href='redcard_list.php'">

</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
