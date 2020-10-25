<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:読者登録数差分/ポイント付与
'******************************************************/

$title_text = $TT_sabun_point;
$title_color = $TC_MASTER;

$err_flg = "";
if (count($chk) == 0) {
	$msg = "処理するデータがありません。ご確認ください。";
	$err_flg = "1";
} else {
/* **
	$sql = "SELECT mag_id FROM M_MAGAZINE WHERE mag_id=:mm_mag_id AND reg1_max_volume>=:reg1_max_volume";
	$parse = OCIParse($conn, $sql);
	OCIBindByName($parse, ":MM_MAG_ID", &$mm_mag_id, 6);
	OCIBindByName($parse, ":REG1_MAX_VOLUME", &$reg1_max_volume, 6);
	for ($i = 0; $i < count($mag_id); $i++){
		if ($chk[$i]) {
			$mm_mag_id = $mag_id[$i];
			$reg1_max_volume = $mag_volume[$i];
		  OCIExecute($parse, OCI_DEFAULT);
			if (OCIFetch($parse)) {
				$msg = "処理済データが含まれているようです。ご確認ください。";
				$err_flg = "1";
				break;
			}
		}
	}
*/
	for ($i = 0; $i < count($mag_id); $i++){
		if ($chk[$i]) {
			$mm_mag_id = $mag_id[$i];
			$reg1_max_volume = $mag_volume[$i];
			$sql = "SELECT mag_id FROM M_MAGAZINE WHERE mag_id='$mm_mag_id' AND reg1_max_volume>=$reg1_max_volume";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$msg = "処理済データが含まれているようです。ご確認ください。";
				$err_flg = "1";
				break;
			}
		}
	}
	if ($err_flg != "1") {
		$ok = 0;
		db_begin_trans();
		for ($i = 0; $i < count($mag_id); $i++){
			if ($chk[$i] && $total[$i] > 0) {
				$trans_id = update_squ_key ("T_POINT_DETAIL");
				$owner_id = $publisher_id[$i];
				$trans_point = $total[$i];
				$available_point = $total[$i];
				$pd_mag_id = $mag_id[$i];

				//ポイント履歴追加
				$sql1 = "INSERT INTO T_POINT_DETAIL (trans_id, owner_kind, owner_id, attribution_cd, trans_date, trans_point, available_point, counter_id, mag_id)"
					. " VALUES ($trans_id, '2', $owner_id, '05',  'now', $trans_point, $available_point, 0, '$pd_mag_id')";

				//発行者ポイント加算
				$pt_melonpan = $total[$i];
				$pub_id = $publisher_id[$i];
				$sql2 = "UPDATE M_PUBLISHER SET pt_melonpan = pt_melonpan + $pt_melonpan WHERE publisher_id =$pub_id";

				//メルマガ　reg1_max_volume追加
				$reg1_max_volume = $mag_volume[$i];
				$mm_mag_id = $mag_id[$i];
				$sql3 = "UPDATE M_MAGAZINE SET reg1_max_volume = $reg1_max_volume WHERE mag_id = '$pd_mag_id'";

				if (db_exec($sql1) && db_exec($sql2) && db_exec($sql3)) {
				} else {
					$ok++;
				}
			}
		}
		if ($ok == 0) {
			db_commit_trans();
			$msg = "実行しました。";
		} else {
			db_rollback();
			$msg = "処理中にエラーが発生したため、処理できませんでした。1";
		}
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
if ($err_flg == "1") { 
?>
<input type="button" value=" 戻る " onclick="history.back()">
<?
} else {
?>
<input type="button" value=" 戻る " onclick="location.href='index.php'">
<?
}
?>
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
