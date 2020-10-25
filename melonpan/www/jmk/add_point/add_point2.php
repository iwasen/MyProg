<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");


/******************************************************
' System :めろんぱん事務局用ページ
' Content:発行者・読者/ポイント付与
'******************************************************/
$title_text = $TT_add_point;
$title_color = $TC_MASTER;

$today = date("Ymd");
$reserve_date = sprintf("%04d%02d%02d", $reserve_year, $reserve_month, $reserve_day);

if (trim($mag_id) != "") {
	$mag_id = sprintf("%06d", $mag_id);
	$sql = "SELECT mag_id FROM M_MAGAZINE WHERE mag_id='$mag_id'";
	$result = db_exec($sql);
	if (!pg_numrows($result)) {
		$msg = "メルマガIDが正しくないようです。ご確認ください。";
		$err_flg = "1";
	}
}
if ($err_flg != "1") {
	switch ($kind) {
		case "1":
				switch ($invest) {
					case "1":
						if (($trans_id = update_squ_key ("T_POINT_DETAIL")) != "-1") {
							$sql = "insert INTO T_POINT_DETAIL (trans_id, owner_kind, owner_id, attribution_cd, trans_date, trans_point, available_point, counter_id, mag_id)"
								. " VALUES ($trans_id, '$kind', $id, '$attribution', 'now', $add_point, $add_point, 0, '$mag_id')";
							db_exec($sql);
							$sql = "UPDATE M_MELONPA SET pt_melonpan=(pt_melonpan+$add_point) WHERE reader_id=$id";
							db_exec($sql);
							$msg = "実行しました。";
						} else {
							$msg = "実行できませんでした。";
							$err_flg = "1";
						}
						break;
					case "2":
						if ($today >= $reserve_date) {
							$msg = "予約日の指定が正しくないようです。ご確認ください。";
							$err_msg = "1";
						} else {
							$conn = db_conxxxt();
							if (($RP_trans_id = update_squ_key ("T_RESERVE_PT_DETAIL")) != "-1") {
								$sql = "INSERT INTO T_RESERVE_PT_DETAIL (RP_trans_id, RP_owner_kind, RP_owner_id, RP_attribution_cd, RP_trans_date"
									. ", RP_trans_point, RP_available_point, RP_counter_id, RP_mag_id, RP_reserve_flg)"
									. " VALUES ($RP_trans_id, '$kind', $id, '$attribution', to_date('$reserve_date','YYYYMMDD'), $add_point, $add_point, 0, '$mag_id', '0')";
								db_exec($sql);
								$msg = "実行しました。";
							} else {
								$msg = "実行できませんでした。";
								$err_flg = "1";
							}
						}
						break;
			}
			break;
		case "2":
				switch ($invest) {
					case "1":
						if (($trans_id = update_squ_key ("T_POINT_DETAIL")) != "-1") {
							$sql = "insert INTO T_POINT_DETAIL (trans_id, owner_kind, owner_id, attribution_cd, trans_date, trans_point, available_point, counter_id, mag_id)"
								. " VALUES ($trans_id, '$kind', $id, '$attribution', 'now', $add_point, $add_point, 0, '$mag_id')";
							db_exec($sql);
							$sql = "UPDATE M_PUBLISHER SET pt_melonpan=(pt_melonpan+$add_point) WHERE publisher_id=$id";
							db_exec($sql);
							$msg = "実行しました。";
						} else {
							$msg = "実行できませんでした。";
							$err_flg = "1";
						}
						break;
					case "2":
						if ($today >= $reserve_date) {
							$msg = "予約日の指定が正しくないようです。ご確認ください。";
							$err_msg = "1";
						} else {
							if (($RP_trans_id = update_squ_key ("T_RESERVE_PT_DETAIL")) != "-1") {
								$sql = "INSERT INTO T_RESERVE_PT_DETAIL (RP_trans_id, RP_owner_kind, RP_owner_id, RP_attribution_cd, RP_trans_date"
									. ", RP_trans_point, RP_available_point, RP_counter_id, RP_mag_id, RP_reserve_flg)"
									. " VALUES ($RP_trans_id, '$kind', $id, '$attribution', to_date('$reserve_date','YYYYMMDD'), $add_point, $add_point, 0, '$mag_id', '0')";
								db_exec($sql);
								$msg = "実行しました。";
							} else {
								$msg = "実行できませんでした。";
								$err_flg = "1";
							}
						}
						break;
			}
			break;
		case "3":
				switch ($invest) {
					case "1":
						if (($trans_id = update_squ_key ("T_POINT_DETAIL")) != "-1") {
							$sql = "insert INTO T_POINT_DETAIL (trans_id, owner_kind, owner_id, attribution_cd, trans_date, trans_point, available_point, counter_id, mag_id)"
								. " VALUES ($trans_id, '$kind', $id, '$attribution', 'now', $add_point, $add_point, 0, '$mag_id')";
							db_exec($sql);
							$sql = "UPDATE M_MELONPAI SET melonpai_point=(melonpai_point+$add_point) WHERE melonpai_id=$id";
							db_exec($sql);
							$msg = "実行しました。";
						} else {
							$msg = "実行できませんでした。";
							$err_flg = "1";
						}
						break;
					case "2":
						if ($today >= $reserve_date) {
							$msg = "予約日の指定が正しくないようです。ご確認ください。";
							$err_msg = "1";
						} else {
							if (($RP_trans_id = update_squ_key ("T_RESERVE_PT_DETAIL")) != "-1") {
								$sql = "INSERT INTO T_RESERVE_PT_DETAIL (RP_trans_id, RP_owner_kind, RP_owner_id, RP_attribution_cd, RP_trans_date"
									. ", RP_trans_point, RP_available_point, RP_counter_id, RP_mag_id, RP_reserve_flg)"
									. " VALUES ($RP_trans_id, '$kind', $id, '$attribution', to_date('$reserve_date','YYYYMMDD'), $add_point, $add_point, 0, '$mag_id', '0')";
								db_exec($sql);
								$msg = "実行しました。";
							} else {
								$msg = "実行できませんでした。";
								$err_flg = "1";
							}
						}
						break;
			}
			break;
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

<form method="post" name="form1" action="hon_touroku3.php">

<center>
<?= $msg ?><br><br>
<?
if ($err_flg != "1") {
?>
<input type="button" value=" 戻る " onclick="location.href='index.php'">
<?
} else {
?>
<input type="button" value=" 戻る " onclick="history.back()">
<?
}
?>
</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
