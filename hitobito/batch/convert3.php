<?php
/**
 * ひとびと･net
 *
 * データベースコンバート処理
 *
 * @package
 * @author
 * @version
 */

// コンバート修正分２
//===== 実行する前にこの中を設定してください=====
$mssql_user = 'hitobito_user';                // SQLServerアカウント名
$mssql_pass = 'hitobito_pass';                // SQLServerパスワード
$_ENV['HITOBITO_DB_HOST'] = "localhost";      // PostgreSQLサーバIPアドレス
$test_domain = 'hitobito.xxxxx-xxxx.co.jp';   // テスト用メールドメイン（本番はこの行はコメントアウト）
//===============================================

$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/member_cd.php");

set_time_limit(600);

// SQLServerに接続
$odbc_id = odbc_conxxxt('hitobito', $mssql_user, $mssql_pass);
if (!$odbc_id) {
	echo "SQLServerに接続できません。";
	exit;
}

db_begin_trans();

// データ変換
convert_member();
convert_waiwai_room();
convert_room_member();

db_commit_trans();

//db_exec("VACUUM ANALYZE");

exit;

// ■メンバー情報
function convert_member() {
	global $new_member_id, $test_domain;

	$sql = "SELECT メンバー番号,作成日時,最終更新日時,メールアドレス,パスワード,SEND"
			. " FROM dbo.メンバー"
			. " LEFT JOIN dbo.T_MAIL ON MAILID=メールアドレス AND SEND='1'"
			. " ORDER BY メンバー番号";
	$result = mssql_query($sql);
	while ($fetch = mssql_fetch($result)) {
		$sql = "SELECT mbr_member_id FROM t_member WHERE mbr_mail_addr_low=" . sql_char(strtolower($fetch->メールアドレス));
		$new_member_id[$fetch->メンバー番号] = db_fetch1($sql);
	}
}

// ■ワイワイ会議室情報
function convert_waiwai_room() {
	global $new_room_id;

	$sql = "SELECT 作成日時,最終更新日時,ML_N,GAIYOU,ＭＬ名,入会メッセージ属性,退会メッセージ属性,ヘッダー挿入フラグ,フッター挿入フラグ,"
				. "ゲスト登録許可,ゲスト登録報告属性,ヘッダー,フッター,入会メッセージ,退会メッセージ,ML_ID"
			. " FROM dbo.ＭＬ情報"
			. " INNER JOIN dbo.K_ML ON ML_ID=ＭＬ番号"
			. " WHERE CLOSED<>'3'"
			. " ORDER BY ＭＬ番号";
	$result = mssql_query($sql);
	while ($fetch = mssql_fetch($result)) {
		$sql = "SELECT rom_room_id FROM t_room WHERE rom_name=" . sql_char(trim($fetch->ML_N));
		$new_room_id[$fetch->ML_ID] = db_fetch1($sql);
	}
}

// ■ワイワイ会議メンバー情報
function convert_room_member() {
	global $new_member_id, $new_room_id;

	$sql = "SELECT ＭＬ番号,メンバー番号,配信停止フラグ"
			. " FROM dbo.ＭＬ＿メンバー"
			. " ORDER BY メンバー番号";
	$result = mssql_query($sql);
	while ($fetch = mssql_fetch($result)) {
		if ($new_room_id[$fetch->ＭＬ番号] && $new_member_id[$fetch->メンバー番号]) {
			$mail_send_flag = $fetch->配信停止フラグ == 0 ? 1 : 0;

			$sql = "UPDATE t_room_member SET rmm_mail_send_flag=$mail_send_flag WHERE rmm_room_id={$new_room_id[$fetch->ＭＬ番号]} AND rmm_member_id={$new_member_id[$fetch->メンバー番号]} AND rmm_mail_send_flag<>$mail_send_flag";
			db_exec($sql);

			if (db_errormessage() != '')
				exit;
		}
	}
}

// ■シーケンス設定
function set_sequence($table, $column) {
	$sql = "select setval('{$table}_{$column}_seq', coalesce(max($column)+1,1),false) from $table";
	db_exec($sql);
}

// ■日付整形
function date_format($date, $default = '') {
	$date = trim($date);
	if ($date != '') {
		sscanf($date, '%d/%d/%d %d:%d:%d', &$year, &$month, &$day, &$hour, &$minute, &$second);

		if ($year < 100) {
			if ($year < 50)
				$year += 2000;
			else
				$year += 1900;
		}

		if ($month == 0)
			$month = 1;

		if ($day == 0)
			$day = 1;

		$date = sprintf('%d-%d-%d %d:%d:%d', $year, $month, $day, $hour, $minute, $second);
	} else
		$date = $default;

	return $date;
}

// ■郵便番号整形
function zip_format($zip) {
	return str_replace('-', '', trim($zip));
}

// ■SQLServerでクエリ実行
function mssql_query($sql) {
	global $odbc_id;

	return odbc_exec($odbc_id, mb_convert_encoding($sql, 'SJIS', 'EUC-JP'));
}

// ■SQLServerからフェッチ
function mssql_fetch($result) {
	$fetch = odbc_fetch_object($result);
	if (!$fetch)
		return $fetch;

	foreach ($fetch as $key => $val) {
		$key = mb_convert_encoding($key, 'EUC-JP', 'SJIS');
		$fetch2->$key = mb_convert_encoding($val, 'EUC-JP', 'SJIS');
	}

	return $fetch2;
}
?>