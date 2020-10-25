<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:クリックカウンタ
'******************************************************/

$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");

// 入力パラメータ
$ary = explode('-', trim($_SERVER['QUERY_STRING']));
if (count($ary) != 2)
	param_error();

// クリックカウンタIDおよびユーザIDを取得
$cc_id = $ary[0];
$user_id = $ary[1];

// クリックカウンタID数字チェック
if (!is_numeric($cc_id))
	param_error();

// テスト時(%CLICK%)はユーザIDを0
if ($user_id == '%CLICK%')
	$user_id = 0;
elseif (!is_numeric($user_id))
	param_error();

// クリックカウンタ登録情報取得
$sql = "SELECT clc_jump_url,clc_start_date,clc_end_date FROM t_click_counter WHERE clc_cc_id=$cc_id";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$time = time();

	// 開始日時チェック
	if ($fetch->clc_start_date != null && sql_time($fetch->clc_start_date) > $time)
		$user_id = 0;

	// 終了日時チェック
	if ($fetch->clc_end_date != null && sql_time($fetch->clc_end_date) < $time - 24*60*60)
		$user_id = 0;

	$jump_url = $fetch->clc_jump_url;
} else
	param_error();

if ($user_id) {
	// ユーザが登録されているかチェック
	$sql = "SELECT COUNT(*)"
			. " FROM t_user_personal"
			. " WHERE ups_user_id=$user_id";
	$result = db_exec($sql);
	if (pg_fetch_result($result, 0, 0)) {
		db_begin_trans();

		// クリックカウンタ履歴に追加
		$sql = "INSERT INTO t_click_counter_log (ccl_cc_id,ccl_user_id,ccl_ip_adr)"
				. " VALUES ($cc_id,$user_id,'{$_SERVER['REMOTE_ADDR']}')";
		db_exec($sql);

		// クリック数カウントアップ
		$sql = "UPDATE t_click_counter SET clc_click_num=clc_click_num+1 WHERE clc_cc_id=$cc_id";
		db_exec($sql);

		db_commit_trans();
	}
}

// 本来の飛び先へジャンプ
redirect($jump_url);

// パラメータエラー
function param_error() {
	header("HTTP/1.0 404 Not Found");
	exit;
}
?>