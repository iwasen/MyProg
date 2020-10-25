<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:エクスポートデータCSV出力
'******************************************************/

$top = "..";
$inc = "$top/../inc";
//include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/csv.php");
include("csv_inc.php");

// 入力パラメータ
$dealer_cd = $_GET['dealer_cd'];
$from_date = $_GET['from_date'];
$to_date = $_GET['to_date'];

// CSV出力準備
$ymd = date('Ymd');
prepare_csv("$dealer_cd$ymd.csv");

// 指定された日付のデータを取得
$sql = "SELECT urg_user_id"
		. " FROM t_user_regist"
		. " WHERE urg_dealer_cd='$dealer_cd' AND urg_handover_date>='$from_date' AND urg_handover_date<'$to_date'"
		. " ORDER BY urg_user_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$user_id = pg_fetch_result($result, $i, 0);

	// Mailリレーション結果出力
	$data = output_res_data($user_id);
	output_csv($data);

	// 1to1メール出力
	$data = output_1to1_data($user_id);
	if (is_array($data)) {
		foreach (array_keys($data) as $key)
			output_csv($data[$key]);
	}
}
exit;
?>