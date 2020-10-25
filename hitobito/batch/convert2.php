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

// コンバート修正分
exit;	// 間違って実行しないため（もし実行する必要がある場合はコメントアウトする）
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
convert_channel_master();
convert_member();
convert_navi();
convert_waiwai_room();
convert_navi_page();

db_commit_trans();

//db_exec("VACUUM ANALYZE");

exit;


// ■チャネルマスタ
function convert_channel_master() {
	global $new_channel_id, $new_sub_channel_id;

	// チャネルマスタ
	$channel_id = 0;
	$sql = "SELECT CHANNEL_ID,CHANNEL_N,KBUN,SEQ"
			. " FROM dbo.M_CHANNEL1"
			. " ORDER BY SEQ";
	$result = mssql_query($sql);
	while ($fetch = mssql_fetch($result)) {
		$sql = "SELECT chn_channel_id FROM m_channel WHERE chn_channel_name=" . sql_char(trim($fetch->CHANNEL_N));
		$new_channel_id[$fetch->CHANNEL_ID] = db_fetch1($sql);
	}

	// サブチャネルマスタ
	$sub_channel_id = 0;
	$sql = "SELECT CHANNEL_ID,CHANNEL_SID,CHANNEL_SN,KBUN,SEQ"
			. " FROM dbo.M_CHANNELSUB1"
			. " WHERE KBUN='1'"
			. " ORDER BY CHANNEL_ID,SEQ";
	$result = mssql_query($sql);
	while ($fetch = mssql_fetch($result)) {
		$sql = "SELECT sch_subchannel_id FROM m_sub_channel WHERE sch_subchannel_name=" . sql_char(trim($fetch->CHANNEL_SN));
		$new_sub_channel_id[$fetch->CHANNEL_ID][$fetch->CHANNEL_SID] = db_fetch1($sql);
	}
}

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

// ■ナビ情報
function convert_navi() {
	global $new_member_id, $new_navi_id;

	// 都道府県マスタ取得
	$sql = "SELECT tdf_area_id,tdf_name FROM m_todofuken";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$todofuken[$fetch->tdf_name] = $fetch->tdf_area_id;
		$todofuken[mb_substr($fetch->tdf_name, 0, mb_strlen($fetch->tdf_name) - 1)] = $fetch->tdf_area_id;
	}

	// 職業マスタ取得
	$sql = "SELECT skg_shokugyo_id,skg_shokugyo_name FROM m_shokugyo";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$todofuken[$fetch->skg_shokugyo_name] = $fetch->skg_shokugyo_id;
	}

	$sql = "SELECT NAVI_ID,メンバー番号,作成日時,最終更新日時,Name,生年月日,性別,郵便番号,自宅電話番号,ＦＡＸ番号,勤務先電話番号,住所１,住所２,住所３,会社名,職業"
			. " FROM dbo.v_Navi_Guide"
			. " INNER JOIN dbo.メンバー ON メンバー番号=NAVI_ID"
			. " WHERE NAVI_ID=81"
			. " ORDER BY (CASE WHEN NAVI_ID=305 THEN 0 ELSE 1 END),NAVI_ID";
	$result = mssql_query($sql);
	while ($fetch = mssql_fetch($result)) {
		preg_match('/ *([^ ]+) *([^ ]*) *$/', str_replace('　', ' ', $fetch->Name), $name);

		$navi_id = get_next_seq('t_navi', 'nav_navi_id');
		$new_navi_id[$fetch->NAVI_ID] = $navi_id;

		$rec['nav_navi_id'] = sql_number($navi_id);
		$rec['nav_member_id'] = sql_number($new_member_id[$fetch->メンバー番号]);
		$rec['nav_status'] = sql_number(1);
		$rec['nav_regist_date'] = sql_date(date_format($fetch->作成日時, 'now'));
		$rec['nav_update_date'] = sql_date(date_format($fetch->最終更新日時));
		$rec['nav_name1'] = sql_char($name[1]);
		$rec['nav_name2'] = sql_char($name[2]);
		$rec['nav_birthday'] = sql_date(date_format($fetch->生年月日));
		$rec['nav_sex_cd'] = sql_number($fetch->性別 == 0 ? 1 : 2);
		$rec['nav_zip_cd'] = sql_char(zip_format($fetch->郵便番号));
		$rec['nav_jitaku_tel'] = sql_char($fetch->自宅電話番号);
		$rec['nav_jitaku_fax'] = sql_char($fetch->ＦＡＸ番号);
		$rec['nav_kinmu_tel'] = sql_char($fetch->勤務先電話番号);
		$rec['nav_address1'] = sql_number($todofuken[trim($fetch->住所１)]);
		$rec['nav_address2'] = sql_char($fetch->住所２);
		$rec['nav_address3'] = sql_char($fetch->住所３);
		$rec['nav_company'] = sql_char($fetch->会社名);
		$rec['nav_shokugyo_id'] = sql_number($todofuken[trim($fetch->職業)]);
		db_insert('t_navi', $rec);

		if (db_errormessage() != '')
			exit;
	}

	set_sequence('t_navi', 'nav_navi_id');

	$sql = "SELECT NAVI_ID,メンバー番号,作成日時,最終更新日時,Name,生年月日,性別,郵便番号,自宅電話番号,ＦＡＸ番号,勤務先電話番号,住所１,住所２,住所３,会社名,職業"
			. " FROM dbo.v_Navi"
			. " INNER JOIN dbo.メンバー ON メンバー番号=NAVI_ID"
			. " ORDER BY (CASE WHEN NAVI_ID=305 THEN 0 ELSE 1 END),NAVI_ID";
	$result = mssql_query($sql);
	while ($fetch = mssql_fetch($result)) {
		$sql = "SELECT nav_navi_id FROM t_navi WHERE nav_member_id=" . sql_number($new_member_id[$fetch->メンバー番号]);
		$new_navi_id[$fetch->NAVI_ID] = db_fetch1($sql);
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

// ■ナビページ情報
function convert_navi_page() {
	global $new_channel_id, $new_sub_channel_id;
	global $new_navi_id;
	global $new_room_id;

	$sql = "SELECT T1.NAVIPAGE_ID,T1.NAVI_ID,T1.CLOSED,T1.SDATE,NAVIPAGE_N,CHANNEL_ID,CHANNEL_SID,NAVIPAGE_GAIYOU,T1.PR,ML_ID"
			. " FROM dbo.N_NAVIPAGE T1"
			. " INNER JOIN (SELECT * FROM dbo.N_INDEX1 UNION SELECT * FROM dbo.N_INDEX2) T2 ON T2.NAVIPAGE_ID=T1.NAVIPAGE_ID AND T2.GRP_ID=T1.GRP_ID"
			. " LEFT JOIN K_ML T3 ON T3.NAVIPAGE_ID=T1.NAVIPAGE_ID AND T3.CLOSED<>'3'"
			. " WHERE T1.NAVIPAGE_ID IN ('talkpc_p', 'lovechoco', 'banshaku', 'yuzawa', 'tv_drama')"
			. " ORDER BY (CASE WHEN T1.NAVIPAGE_ID='hitobito' THEN 0 ELSE 1 END),T1.NAVI_ID";
	$result = mssql_query($sql);
	while ($fetch = mssql_fetch($result)) {
		if (isset($new_navi_id[$fetch->NAVI_ID])) {
			$navi_page_id = get_next_seq('t_navi_page', 'nvp_navi_page_id');

			$rec['nvp_navi_page_id'] = sql_number($navi_page_id);
			$rec['nvp_navi_id'] = sql_number($new_navi_id[$fetch->NAVI_ID]);
			$rec['nvp_status'] = sql_number(1);
			$rec['nvp_regist_date'] = sql_date(date_format($fetch->SDATE), 'current_date');
			$rec['nvp_open_date'] = sql_date(date_format($fetch->SDATE));
			$rec['nvp_theme'] = sql_char($fetch->NAVIPAGE_N);
			$rec['nvp_channel_id'] = sql_number($new_channel_id[$fetch->CHANNEL_ID]);
			$rec['nvp_subchannel_id'] = sql_number($new_sub_channel_id[$fetch->CHANNEL_ID][$fetch->CHANNEL_SID]);
			$rec['nvp_type'] = sql_number(isset($new_room_id[$fetch->ML_ID]) ? 1 : 2);
			$rec['nvp_outline'] = sql_char($fetch->NAVIPAGE_GAIYOU);
			$rec['nvp_blog_id'] = sql_number($navi_page_id);
			$rec['nvp_room_id'] = sql_number($new_room_id[$fetch->ML_ID]);
			$rec['nvp_melmaga_id'] = sql_number($navi_page_id);
			$rec['nvp_title'] = sql_char($fetch->NAVIPAGE_N);
			//$rec['nvp_copy'] =
			$rec['nvp_self_introduction'] = sql_char($fetch->PR);
			$rec['nvp_image_id'] = sql_number($fetch->NAVI_ID);
			$rec['nvp_contents_check'] = sql_number(3);
			$rec['nvp_open_flag'] = sql_number(1);
			db_insert('t_navi_page', $rec);

			$rec['blg_blog_id'] = sql_number($navi_page_id);
			$rec['blg_status'] = sql_number(1);
			$rec['blg_regist_date'] = sql_date(date_format($fetch->SDATE), 'current_date');
			$rec['blg_title'] = sql_char($fetch->NAVIPAGE_N);
			db_insert('t_blog', $rec);

			$rec['mmg_melmaga_id'] = sql_number($navi_page_id);
			$rec['mmg_status'] = sql_number(9);
			$rec['mmg_melmaga_name'] = sql_char('メルマガ名称未設定');
			$rec['mmg_backnumber'] = sql_number(1);
			$rec['mmg_notify_mail'] = sql_number(1);
			db_insert('t_melmaga', $rec);

			if (db_errormessage() != '')
				exit;
		}
	}

	set_sequence('t_navi_page', 'nvp_navi_page_id');
	set_sequence('t_blog', 'blg_blog_id');
	set_sequence('t_melmaga', 'mmg_melmaga_id');
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