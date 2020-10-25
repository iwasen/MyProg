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

//'=== Main ===
$sql = "SELECT melonpai_point FROM M_MELONPAI WHERE melonpai_id='$melonpai_id'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$melonpai_point = $fetch->melonpai_point;
	//ポイント精算
	if ($melonpai_point > 0) {
		$trans_id = update_squ_key ("T_POINT_DETAIL");
		$sql = "INSERT INTO T_POINT_DETAIL (trans_id, owner_kind, owner_id, attribution_cd, trans_date, trans_point, available_point, counter_id)"
			. " VALUES ($trans_id, '3', $melonpai_id, '60', 'now', -$melonpai_point, -$melonpai_point, 0)";
		db_exec($sql);
	}
	//メルマガナビデータ削除
	$sql = "DELETE FROM M_MELONPAI WHERE melonpai_id='$melonpai_id'";
	db_exec($sql);

	$msg = "削除しました。";
} else {
	$msg = "データが既に更新されているようです。ご確認ください。";
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
