<?
$inc = "inc";
include("$inc/common.php");
include("$inc/database.php");

// メイン処理
$ary = explode('-', trim($QUERY_STRING));
if (count($ary) != 2)
	param_error();

$ct_id = $ary[0];
$monitor_id = $ary[1];

if (!is_numeric($ct_id))
	param_error();

if ($monitor_id == '%CLICK%')
	$monitor_id = 0;
elseif (!is_numeric($monitor_id))
	param_error();

$sql = "SELECT cc_jump_url,cc_start_date,cc_end_date FROM t_click_counter WHERE cc_ct_id=$ct_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$time = time();

	if ($fetch->cc_start_date != null && sql_time($fetch->cc_start_date) > $time)
		$monitor_id = 0;

	if ($fetch->cc_end_date != null && sql_time($fetch->cc_end_date) < $time - 24*60*60)
		$monitor_id = 0;

	$jump_url = $fetch->cc_jump_url;
} else
	param_error();

// クリックカウント
if ($monitor_id) {
	$sql = "SELECT *"
			. " FROM t_click_counter JOIN t_job ON jb_job_id=cc_job_id JOIN t_enquete_list ON el_enquete_id=jb_enquete_id"
			. " WHERE cc_ct_id=$ct_id AND el_monitor_id=$monitor_id AND el_status=1";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		db_begin_trans();

		$sql = "INSERT INTO l_click_counter (lc_ct_id,lc_monitor_id,lc_ip_adr)"
				. " VALUES ($ct_id,$monitor_id,'$REMOTE_ADDR')";
		db_exec($sql);

		$sql = "UPDATE t_click_counter SET cc_click_num=cc_click_num+1 WHERE cc_ct_id=$ct_id";
		db_exec($sql);

		db_commit_trans();
	}
}

redirect($jump_url);

// パラメータエラー
function param_error() {
	header("HTTP/1.0 404 Not Found");
	exit;
}
?>