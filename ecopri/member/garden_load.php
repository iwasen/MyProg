<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:ガーデニング
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");

$sql = "select pt_point from t_point where pt_seq_no={$_SESSION['ss_seq_no']}";
$pt_point = db_fetch1($sql);
$sql = "select ('now'::date - gd_first_date::date) as gd_play_date,"
						. "(gd_wt_date::date - gd_first_date::date) as gd_wt_date"
						. " from t_gardening where gd_mb_seq_no={$_SESSION['ss_seq_no']}";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$play_date = $fetch->gd_play_date != '' ? $fetch->gd_play_date : '-9999';
	$wt_date = $fetch->gd_wt_date != '' ? $fetch->gd_wt_date : '-9999';
} else {
	$first_date = '-9999';
	$play_date = '-9999';
	$wt_date = '-9999';
}
$pt_point = $pt_point != '' ? $pt_point : '0';

echo "openDateOfgarden=$play_date&lapsedDaysOfgarden=$play_date&waterFlowerDays=$wt_date&point_totalco2=$pt_point";
?>
