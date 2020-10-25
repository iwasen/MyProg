<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:サーバ停止期間設定
'******************************************************/

$title_text = $TT_server_mainte;
$title_color = $TC_OTHER;

//変更後日付のチェック
function check_date($y, $m, $d, $h, $mi,$start_date,$end_date) {
	$ck_date = mktime($h, $mi, 0, $m, $d, $y);
	if ($ck_date && $ck_date > mktime('now')) {
		if (date("Y/m/d H:i", $ck_date) < $start_date || date("Y/m/d H:i", $ck_date) > $end_date) {
			return true;
		}
	}
}

//Main
$i = 0;
$flag = "";
db_begin_trans();
while ($reserve_id[$i]) {
	if (check_date($reserve_year[$i],$reserve_month[$i],$reserve_day[$i],$reserve_hour[$i],$reserve_minute[$i],$start_date,$end_date)) {
		$sql = sprintf("update t_pub_reserve set resrv_month=%s,resrv_day=%s,resrv_hour=%s where index_id=%s",
					sql_char($reserve_month[$i]),
					sql_char($reserve_day[$i]),
					sql_datetime($reserve_year[$i],$reserve_year[$i],$reserve_month[$i],$reserve_day[$i],$reserve_hour[$i],$reserve_minute[$i]),
					sql_number($reserve_id[$i]));
		db_exec($sql);
	} else {
		$flag = "ng";
		break;;
	}
	$i++;
}
if ($flag) {
	db_rollback();
	$back = "history.back()";
	$msg =  "変更後日時をご確認ください。";
} else {
	$back = "location.href='index.php'";
	$msg = "変更しました。";
	db_commit_trans();
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
<input type="button" value=" 戻る " onclick="<?=$back?>">

</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
