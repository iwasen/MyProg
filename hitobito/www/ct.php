<?php
/**
 * ひとびと･net ユーザページ
 *
 * ひとびと便りクリックカウンター処理
 *
 *
 * @package
 * @author
 * @version
 */

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

// メイン処理
$ary = explode('-', trim($_SERVER['QUERY_STRING']));
if (count($ary) != 2)
	param_error();

$click_id = $ary[0];
$member_id = $ary[1];

if (!is_numeric($click_id))
	param_error();

if ($member_id == '%MEMBER_ID%')
	$member_id = 0;
elseif (!is_numeric($member_id))
	param_error();

$sql = "SELECT tcc_letter_id, tcc_jump_url, tcc_start_date, tcc_end_date FROM t_click_count WHERE tcc_click_id=$click_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$letter_id = $fetch->tcc_letter_id;
	$jump_url = $fetch->tcc_jump_url;

	$time = time();

	if ($fetch->tcc_start_date != null && sql_time($fetch->tcc_start_date) > $time)
		$member_id = 0;

	if ($fetch->tcc_end_date != null && sql_time($fetch->tcc_end_date) < $time - 24*60*60)
		$member_id = 0;
} else
	param_error();

if ($member_id) {
	db_begin_trans();

	// クリック履歴
	$sql = "INSERT INTO l_click_count (lcc_click_id, lcc_member_id, lcc_ip_adr)"
			. " VALUES ($click_id, $member_id, '{$_SERVER['REMOTE_ADDR']}')";
	db_exec($sql);

	// クリックカウント
	$sql = "UPDATE c_hitobito_letter_count SET hlc_click_count=hlc_click_count+1 WHERE hlc_letter_id=$letter_id";
	db_exec($sql);

	db_commit_trans();
}

redirect($jump_url);

// パラメータエラー
function param_error() {
	header("HTTP/1.0 404 Not Found");
	exit;
}
?>