<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」随時処理
' Content:ユーザ情報インポート処理
'******************************************************/

$inc = "../inc";
include("$inc/database.php");
include("$inc/login_id.php");
include("$inc/check.php");

// 最大実行時間（１時間）
set_time_limit(3600);

// インポートファイル名の取得
if ($_SERVER['argc'] != 0) {
	// コマンドラインから起動
	for ($i = 1; $i < $_SERVER['argc']; $i++) {
		switch ($_SERVER['argv'][$i]) {
		case '-f':
			$force = true;
			break;
		case '-c':
			$check = true;
			break;
		default:
			$import_file = $_SERVER['argv'][$i];
			break;
		}
	}
} else {
	// Apacheから起動
	$import_file = $_FILES['import_file']['tmp_name'];
	$force = $_POST['force'];
	$check = $_POST['check'];
}
if ($import_file == '') {
	message('インポートファイルの指定がありません。');
	exit;
}

db_begin_trans();

// インポート処理
$ok_count = 0;
$ng_count = 0;
$ig_count = 0;
import_user_data($import_file, $ok_count, $ng_count, $ig_count);

// 件数表示
message('');
message("　正常データ：{$ok_count}件");
message("エラーデータ：{$ng_count}件");
message("　無視データ：{$ig_count}件");

// 処理結果表示
message('');
if ($ng_count != 0 && !$force || $check) {
	db_rollback();
	err_post_proc();
	if ($check)
		message('インポートデータチェックは終了しました。');
	else
		message('入力データにエラーがあるため、インポートは行われませんでした。');
} else {
	db_commit_trans();
	message("インポートを実行しました。");
}
exit;

// データレイアウト取得
function get_data_layout(&$layout, &$col) {
	// レイアウトデータ取得
	$sql = "SELECT * FROM t_data_layout ORDER BY dtl_column_no";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$layout[] = $fetch;

		// 入力データのカラム位置
		if ($fetch->dtl_input == DBTRUE || $fetch->dtl_output == DBTRUE)
			$col[$fetch->dtl_group_name][$fetch->dtl_item_name] = $fetch->dtl_column_no - 1;
	}
}

// ユーザデータインポート
function import_user_data($import_file, &$ok_count, &$ng_count, &$ig_count) {
	if (($fp = fopen($import_file, 'r')) == false) {
		message('ファイルのオープンに失敗しました。');
		exit;
	}

	// レイアウトデータ取得
	get_data_layout($layout, $col);

	$line = 1;
	while ($data = fgetcsv($fp, 10000, ",")) {
		mb_convert_variables('EUC-JP', 'SJIS', $data);

		if ($data[0] != '') {
			if (check_user_data($data, $layout, $col, $line, $error, $campaign_cd)) {
				// 登録情報
				$user_id = insert_user_regist($error, $data, $col, $campaign_cd);

				// エラー時の決着区分とランクをセット
				if ($error) {
					$data[$col['テレコール結果']['決着区分']] = 3;
					$data[$col['テレコール結果']['ランク']] = 'L';
				}

				// インポートデータオリジナルデータ
				insert_user_data($user_id, $data);

				if (!$error) {
					// ログイン情報
					insert_user_login($user_id);

					// ステータス情報
					insert_user_status($user_id);

					// 個人情報
					insert_user_personal($user_id, $data, $col);

					// 管理情報
					insert_user_manage($user_id, $data, $col);

					// 車保有情報
					insert_user_car($user_id, $data, $col);

					// キャンペーン応募情報
					insert_user_campaign($user_id, $data, $col);

					$ok_count++;
				} else
					$ng_count++;
			} else
				$ig_count++;
		}
		$line++;
	}

	fclose($fp);
}

// ユーザデータチェック
function check_user_data(&$data, &$layout, &$col, $line, &$error, &$campaign_cd) {
	$error = false;

	// キャンペーンコードチェック
	$sql = sprintf("SELECT cmp_campaign_cd"
				. " FROM t_campaign"
				. " WHERE cmp_import_cd=%s OR cmp_mail_follow_cd=%s",
			sql_number($data[$col['タイトル']['ＣＰ名称']]),
			sql_number($data[$col['タイトル']['ＣＰ名称']]));
	$campaign_cd = db_fetch1($sql);
	if ($campaign_cd == '') {
		message("Warning: {$line}行目の\"ＣＰ名称\"（{$data[$col['タイトル']['ＣＰ名称']]}）はキャンペーンマスタに登録されていないため無視しました。");
		return false;
	}

	// 車種名、車種コード取得
	$car_model_name = trans_car_name($data[$col['お客様情報']['購入希望車名']], $data[$col['管理情報']['販売店コード']]);
	$car_model_cd = get_car_model_cd($car_model_name);
	if (!$car_model_cd) {
		if ($data[$col['お客様情報']['応募種別']] == 'C') {
			$car_model_name = trans_car_name($data[$col['お客様情報']['コメント']], $data[$col['管理情報']['販売店コード']]);
			$car_model_cd = get_car_model_cd($car_model_name);
		}
	}

	// 登録済みチェック
	$mail_addr = $data[$col['お客様情報']['E-mailアドレス']];
	$sql = sprintf("SELECT COUNT(*)"
			. " FROM t_user_regist"
			. " WHERE urg_customer_cd=%s",
			sql_char($data[$col['お客様情報']['お客様CD']]));
	if (db_fetch1($sql) != 0) {
		message("Warning: {$line}行目の\"お客様CD\"はすでに登録されているため無視しました。");
		return false;
	} else {
		// メールアドレス重複チェック
		if ($mail_addr != '') {
			$sql = sprintf("SELECT urg_campaign_cd,umg_kounyu_kibou_car"
					. " FROM t_user_personal"
					. " JOIN t_user_manage ON umg_user_id=ups_user_id"
					. " JOIN t_user_regist ON urg_user_id=ups_user_id"
					. " WHERE ups_mail_addr=%s",
					sql_char($mail_addr));
			$result = db_exec($sql);
			$nrow = pg_num_rows($result);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);

				if ($fetch->urg_campaign_cd == $campaign_cd || check_car_model($fetch->umg_kounyu_kibou_car) == check_car_model($car_model_name)) {
					message("Warning: {$line}行目の\"E-mailアドレス\"（{$mail_addr}）はすでに登録されているため無視しました。");
					return false;
				}
			}
		}
	}

	// データ存在および形式チェック
	$size = count($layout);
	for ($i = 0; $i < $size; $i++) {
		$l = &$layout[$i];
		if ($l->dtl_input == DBTRUE) {
			$index = $l->dtl_column_no - 1;
			if ($data[$index] == '') {
				if ($l->dtl_must == DBTRUE) {
					message("Error: {$line}行目の\"{$l->dtl_group_name}-{$l->dtl_item_name}\"にデータがありません。");
					$error = true;
				}
			} else {
				switch ($l->dtl_attribute) {
				case '9':
					if (!is_numeric($data[$index])) {
						message("Error: {$line}行目の\"{$l->dtl_group_name}-{$l->dtl_item_name}\"が数字ではありません。");
						$error = true;
					}
					break;
				case 'N':
				case 'X':
					break;
				}
				if (mb_strlen($data[$index]) > $l->dtl_max_length) {
					message("Error: {$line}行目の\"{$l->dtl_group_name}-{$l->dtl_item_name}\"が{$l->dtl_max_length}文字を超えています。");
					$error = true;
				}
			}
		}
	}

	// メールアドレスチェック
	if ($mail_addr != '') {
		if (!check_mail_addr($mail_addr)) {
			message("Error: {$line}行目の\"E-mailアドレス\"（{$mail_addr}）は正しくありません。");
			$error = true;
		} else if (check_keitai_domain($mail_addr)) {
			message("Error: {$line}行目の\"E-mailアドレス\"（{$mail_addr}）は携帯アドレスです。");
			$error = true;
		}
	}

	// 車種登録チェック
	if (!$car_model_cd) {
		message("Error: {$line}行目の\"購入希望車名\"（{$car_model_name}）は車種マスタに登録されていません。");
		$error = true;
	}

	// 販売店コード登録チェック
	$dealer_cd = $data[$col['管理情報']['販売店コード']];
	if ($dealer_cd != '') {
		$sql = sprintf("SELECT COUNT(*) FROM t_dealer WHERE dlr_dealer_cd=%s", sql_char($dealer_cd));
		if (db_fetch1($sql) == 0) {
			message("Error: {$line}行目の\"販売店コード\"（{$dealer_cd}）は販売店マスタに登録されていません。");
			$error = true;
		} else {
			// 車種取り扱いチェック
			if ($car_model_cd) {
				$sql = sprintf("SELECT COUNT(*) FROM t_dealer_car WHERE dlc_dealer_cd=%s AND dlc_car_model_cd=%s",
						sql_char($dealer_cd),
						sql_number($car_model_cd));
				if (db_fetch1($sql) == 0) {
					message("Error: {$line}行目の\"購入希望車名\"（{$car_model_name}）は販売店（{$dealer_cd}）の取り扱い車種ではありません。");
					$error = true;
				}
			}
		}
	}

	return true;
}

// 登録情報保存
function insert_user_regist($error, &$data, &$col, $campaign_cd) {
	$rec['urg_customer_cd'] = sql_char($data[$col['お客様情報']['お客様CD']]);
	$rec['urg_dealer_cd'] = sql_char($data[$col['管理情報']['販売店コード']]);
	$rec['urg_regist_date'] = 'current_timestamp';
	if ($error) {
		$rec['urg_handover_date'] = 'current_timestamp';
		$rec['urg_follow_date'] = '(SELECT fld_send_date FROM t_follow_date WHERE fld_send_date>current_timestamp ORDER BY fld_send_date LIMIT 1)';
	}
	$rec['urg_campaign_cd'] = sql_number($campaign_cd);
	db_insert('t_user_regist', $rec);
	return get_current_seq('t_user_regist', 'urg_user_id');
}

// ログイン情報保存
function insert_user_login($user_id) {
	$rec['ulg_user_id'] = sql_number($user_id);
	$rec['ulg_login_id'] = sql_char(create_login_id());
	$rec['ulg_password'] = sql_char(create_password());
	db_insert('t_user_login', $rec);
}

// ステータス情報保存
function insert_user_status($user_id) {
	$rec['ust_user_id'] = sql_number($user_id);
	$rec['ust_status'] = 1;
	db_insert('t_user_status', $rec);
}

// 個人情報
function insert_user_personal($user_id, &$data, &$col) {
	$col1 = &$col['お客様情報'];
	$rec['ups_user_id'] = sql_number($user_id);
	$rec['ups_update_date'] = 'current_timestamp';
	$rec['ups_name_kanji'] = sql_char($data[$col1['お客様名（漢字）']]);
	$rec['ups_name_kana'] = sql_char($data[$col1['お客様名（カナ）']]);
	$rec['ups_zip_cd'] = sql_char($data[$col1['郵便番号']]);
	$rec['ups_address0'] = sql_char($data[$col1['住所0']]);
	$rec['ups_address1'] = sql_char($data[$col1['住所1']]);
	$rec['ups_address2'] = sql_char($data[$col1['住所2']]);
	$rec['ups_tel_no'] = sql_char($data[$col1['電話番号']]);
	$rec['ups_keitai_no'] = sql_char($data[$col1['携帯電話番号']]);
	$rec['ups_mail_addr'] = sql_char($data[$col1['E-mailアドレス']]);
	$rec['ups_sex_cd'] = sql_char($data[$col1['性別区分']]);
	$rec['ups_age'] = is_numeric($data[$col1['年齢']]) ? sql_number($data[$col1['年齢']]) : 'null';
	db_insert('t_user_personal', $rec);
}

// 管理情報
function insert_user_manage($user_id, &$data, &$col) {
	$col1 = &$col['管理情報'];
	$col2 = &$col['お客様情報'];
	$col3 = &$col['ﾌｨｰﾄﾞﾊﾞｯｸ情報'];
	$rec['umg_user_id'] = sql_number($user_id);
	$rec['umg_customer_cd'] = sql_char($data[$col2['お客様CD']]);
	$rec['umg_dealer_cd'] = sql_char($data[$col1['販売店コード']]);
	$rec['umg_dealer_name'] = sql_char($data[$col1['販売店名']]);
	$rec['umg_oubo_shubetsu'] = sql_char($data[$col2['応募種別']]);
	$rec['umg_jisshi_kai'] = sql_number($data[$col2['実施回']]);
	$rec['umg_regist_ym'] = sql_char($data[$col2['初度登録年月']]);
	$rec['umg_oubo_date'] = sql_char($data[$col2['応募日（来店日）']]);
	$rec['umg_jisha_tasha_kubun'] = sql_char($data[$col2['自社・他社区分']]);
	$rec['umg_follow_kubun'] = sql_char($data[$col2['フォロー区分']]);
	$rec['umg_tantou_shop_cd'] = sql_char($data[$col3['担当店舗CD']]);
	$rec['umg_tantou_shop_name'] = sql_char($data[$col3['担当店舗名称']]);
	$rec['umg_tantou_staff_name'] = sql_char($data[$col3['担当スタッフ名']]);

	$car_model_name = trans_car_name($data[$col2['購入希望車名']], $data[$col1['販売店コード']]);
	$car_model_cd = get_car_model_cd($car_model_name);
	$rec['umg_kounyu_kibou_car'] = sql_char($car_model_name);
	if ($car_model_cd == 0) {
		$car_model_name = trans_car_name($data[$col2['コメント']], $data[$col1['販売店コード']]);
		$car_model_cd = get_car_model_cd($car_model_name);
		$rec['umg_kounyu_kibou_car'] = sql_char($car_model_name);
	}

	$rec['umg_car_model_cd'] = sql_number($car_model_cd);
	db_insert('t_user_manage', $rec);
}

// 車保有情報
function insert_user_car($user_id, &$data, &$col) {
	$col1 = &$col['保有状況'];
	$rec['ucr_user_id'] = sql_number($user_id);
	$rec['ucr_car_maker'] = sql_char($data[$col1['車両メーカー']]);
	$rec['ucr_car_model'] = sql_char($data[$col1['車種名']]);
	$rec['ucr_jikai_shaken'] = sql_char($data[$col1['次回車検']]);
	$rec['ucr_dealer_cd'] = sql_char($data[$col1['購入販売店コード']]);
	$rec['ucr_dealer_type'] = sql_char($data[$col1['購入販売店種別']]);
	$rec['ucr_dealer_name'] = sql_char($data[$col1['購入販売店名']]);
	db_insert('t_user_car', $rec);
}

// キャンペーン応募情報
function insert_user_campaign($user_id, &$data, &$col) {
	$col1 = &$col['キャンペーン応募アンケート'];
	$rec['ucm_user_id'] = sql_number($user_id);
	$rec['ucm_raiten_mokuteki'] = sql_char($data[$col1['来店目的']]);
	$rec['ucm_sonota_riyuu'] = sql_char($data[$col1['その他理由']]);
	$rec['ucm_cp_ninchi'] = sql_char($data[$col1['CP認知その他内容']]);
	$rec['ucm_campaign_ninchi'] = sql_char($data[$col1['キャンペーン認知']]);
	$rec['ucm_jouhou_teikyou_kyodaku'] = sql_char($data[$col1['情報提供許諾']]);
	db_insert('t_user_campaign', $rec);
}

// インポートデータオリジナルデータ
function insert_user_data($user_id, &$data) {
	$size = count($data);

	for ($i = 0; $i < $size; $i++) {
		if ($data[$i] != '') {
			$rec['udt_user_id'] = sql_number($user_id);
			$rec['udt_column_no'] = sql_number($i + 1);
			$rec['udt_value'] = sql_char($data[$i]);
			db_insert('t_user_data', $rec);
		}
	}
}

// メッセージ出力
function message($text) {
	echo $text, "\n";
	if ($_SERVER['argc'] == 0)
		echo '<br>';
}

// 販売チャネル別の車種名に変換
function trans_car_name($car_model_name, $dealer_cd) {
	$sql = sprintf("SELECT scc_car_model_name2 FROM t_sales_channel_car JOIN t_dealer ON dlr_sales_channel_cd=scc_sales_channel_cd WHERE scc_car_model_name=%s AND dlr_dealer_cd=%s",
			sql_char($car_model_name),
			sql_char($dealer_cd));
	return db_fetch1($sql, $car_model_name);
}

// 車種コード取得
function get_car_model_cd($car_model_name) {
	$sql = sprintf("SELECT crm_car_model_cd FROM t_car_model WHERE crm_car_model_name=%s",
			sql_char($car_model_name));
	return db_fetch1($sql, 0);
}

// 同一車種チェック
function check_car_model($car_model_name) {
	switch ($car_model_name) {
	case 'ＲＡＶ４Ｊ':
	case 'ＲＡＶ４Ｌ':
		return 'ＲＡＶ４';
	case 'アルファードＧ':
	case 'アルファードＶ':
		return 'アルファード';
	case 'エスティマＬ':
	case 'エスティマＴ':
		return 'エスティマ';
	case 'クルーガーＬ':
	case 'クルーガーＶ':
		return 'クルーガー';
	default:
		return $car_model_name;
	}
}

// 携帯アドレスチェック
function check_keitai_domain($mail_addr) {
	$sql = sprintf("SELECT COUNT(*) FROM t_keitai_domain WHERE %s LIKE '%%'||kdm_keitai_domain",
			sql_char($mail_addr));
	return db_fetch1($sql) != 0;
}

// エラー後処理
function err_post_proc() {
	// vacuum
	vacuum('t_user_data');
	vacuum('t_user_login');
	vacuum('t_user_personal');
	vacuum('t_user_manage');
	vacuum('t_user_car');
	vacuum('t_user_campaign');
	vacuum('t_user_status');

	// シーケンスを戻す
	$sql = "select setval('t_user_regist_urg_user_id_seq', coalesce(max(urg_user_id)+1,1),false) from t_user_regist";
	db_exec($sql);
}

// vacuum処理
function vacuum($table) {
	$sql = "VACUUM $table";
	db_exec($sql);
}
?>