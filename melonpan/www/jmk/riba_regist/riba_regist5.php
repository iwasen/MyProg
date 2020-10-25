<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:りばっチャ登録
'******************************************************/

$title_text = $TT_riba_regist;
$title_color = $TC_HAKKOUSHA;

$ok = "";
db_begin_trans();

//メルマガ廃刊処理
$sub = "SELECT publisher_id FROM M_PUBLISHER MP WHERE MM.publisher_id=MP.publisher_id";
$sql = "UPDATE M_MAGAZINE SET mag_status_flg='99', mag_pub_status_flg='99', modefy_dt='now' WHERE publisher_id=$publisher_id";
if (db_exec($sql)) {

	//発行者情報削除
	$sql = "DELETE FROM M_PUBLISHER WHERE PUBLISHER_ID=$publisher_id";
	if (db_exec($sql)) {

		//T_PUB_REASONに解除理由追加
		$sql = "SELECT publisher_id FROM T_PUB_DELREASON WHERE publisher_id=$publisher_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$msg = "既にデータが更新されているようです。ご確認ください。";
		} else {
			$pub_del_id = update_squ_key ("T_PUB_DELREASON");
			$reason = "事務局による代理解除";
			$sql = "INSERT INTO T_PUB_DELREASON (pub_del_id, publisher_id, reason, modefy_dt) VALUES ($pub_del_id, $publisher_id, '$reason', 'now')";
			if (db_exec($sql)) {
				$ok = "ok";
			}
		}
	}
}
if ($ok != "") {
	db_commit_trans();
	$msg = "削除しました。";
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

<input type="button" value=" 戻る " onclick="location.href='index.php'">

</form>
</center>
<? footer_jmk(0); ?>

</body>
</html>
