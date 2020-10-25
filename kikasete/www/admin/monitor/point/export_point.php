<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:ポイント出力CSV
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/csv.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// 入力パラメータ
$from_date = "{$_POST['from_year']}/{$_POST['from_month']}/{$_POST['from_day']}";
$to_date = "{$_POST['to_year']}/{$_POST['to_month']}/{$_POST['to_day']}";
$net_id = $_POST['net_id'];

csv_kikasete('kikasete_point.csv', $from_date, $to_date);
exit;

// きかせて・net用ポイント出力
function csv_kikasete($filename, $from_date, $to_date) {
	// ポイント付与日
	$date = date('Y/m/d');

	// CSV出力準備
	prepare_csv($filename);

	// CSVヘッダ出力
	$csv = '"ID",付与ポイント数,未払いポイント数,ポイント付与日';
	output_csv($csv);

	// CSVデータ出力
	$sb1 = "SELECT lp_member_id AS lp_monitor_id,sum(lp_point) AS sum_point"
			. " FROM l_point"
			. " WHERE lp_member_kind=1 AND lp_date::date BETWEEN '$from_date' AND '$to_date'"
			. " GROUP BY lp_member_id";
	$sql = "SELECT lp_monitor_id,sum_point,mp_curr_point"
			. " FROM ($sb1) sb1"
			. " JOIN t_monitor_point ON mp_monitor_id=lp_monitor_id"
			. " ORDER BY lp_monitor_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		set_csv($csv, $fetch->lp_monitor_id);
		set_csv($csv, $fetch->sum_point);
		set_csv($csv, $fetch->mp_curr_point);
		set_csv($csv, $date);

		output_csv($csv);
	}
}
?>