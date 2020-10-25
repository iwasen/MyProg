<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:読者情報メンテナンス
'******************************************************/

$title_text = $TT_dokusha_mainte;
$title_color = $TC_DOKUSHA;

$ok = "";

db_begin_trans();
$sql = "SELECT pt_melonpan FROM M_MELONPA WHERE reader_id=$reader_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, $i);
	$pt_melonpan = $fetch->pt_melonpan;
	//ポイント精算処理
	if ($pt_melonpan > 0) {
		$trans_id = update_squ_key ("T_POINT_DETAIL");
		$sql = "INSERT INTO T_POINT_DETAIL (trans_id, owner_kind, owner_id, attribution_cd, trans_date, trans_point, available_point, counter_id)"
			. " VALUES ($trans_id, '1', $reader_id, '30', 'now', -$pt_melonpan, -$pt_melonpan, 0)";
		db_exec($sql);
	}
	//Myめろんぱん削除
	$sql = "DELETE FROM M_MELONPA WHERE READER_ID=$reader_id";
	if (db_exec($sql)) {
		//T_MAILADDR reader_id→0
		$sql = "UPDATE T_MAILADDR SET reader_id=0 WHERE READER_ID=$reader_id";
		if (db_exec($sql)) {
			$ok = "ok";
		}
	}
} else {
	db_rollback();
	$msg = "既にデータが更新されているようです。ご確認ください。";
	$ok = "ng";
}
if ($ok == "ok") {
	db_commit_trans();
	$msg = "削除しました。";
} elseif ($ok == "") {
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
<form method="post" name="form1" method="post">

<?= $msg ?><br><br>

<input type="button" value=" 戻る " onclick="location.href='dokusha_mainte1.php'">

</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
