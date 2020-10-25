<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:エクスポートデータCSV出力インクルードファイル
'******************************************************/

// define定義
define('COLUMN_NUM_RES', 127);		// カラム数
define('COLUMN_NUM_1TO1', 18);		// カラム数

// Mailリレーション結果CSV出力
function output_res_data($user_id) {
	$data = array();

	// レイアウトデータ取得
	get_data_layout($layout, $col);

	// オリジナルデータを配列に読み込み
	$sql = "SELECT udt_column_no,udt_value FROM t_user_data WHERE udt_user_id=$user_id ORDER BY udt_column_no";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$column_no = (int)$fetch->udt_column_no;
		if ($layout[$column_no]->dtl_input == DBTRUE)
			$data[$column_no] = $fetch->udt_value;
	}

	$sql = "SELECT cmp_mail_follow_cd FROM t_user_regist JOIN t_campaign ON cmp_campaign_cd=urg_campaign_cd WHERE urg_user_id=$user_id";
	$data[$col['タイトル']['ＣＰ名称']] = db_fetch1($sql);

	// Mailフォロー結果セット
	$sql = "SELECT * FROM t_user_result WHERE urs_user_id=$user_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		$data[$col['お客様意向']['その他関心のある車']] = $fetch->urs_sonota_kanshin;
		$data[$col['お客様意向']['関心ありメーカー']] = $fetch->urs_kanshin_maker;
		$data[$col['お客様意向']['関心あり車名']] = $fetch->urs_kanshin_car;
		$data[$col['お客様意向']['次週イベント誘致']] = $fetch->urs_jishuu_enent;
		$data[$col['お客様意向']['資料送付']] = $fetch->urs_shiryou_souhu;
		$data[$col['お客様意向']['資料送付コメント']] = $fetch->urs_shiryou_comment;
		$data[$col['お客様意向']['情報提供の継続']] = $fetch->urs_teikyou_keizoku;
		$data[$col['お客様意向']['情報提供形態']] = $fetch->urs_teikyou_keitai;
		$data[$col['お客様意向']['お店への要望']] = $fetch->urs_mise_youbou;
		$data[$col['お客様意向']['コメント']] = $fetch->urs_commant;

		$data[$col['保有状況']['趣味など']] = $fetch->urs_shumi;

		$data[$col['テレコール結果']['決着区分']] = $fetch->urs_kecchaku;
		$data[$col['テレコール結果']['ランク']] = $fetch->urs_rank;

		$data[$col['その他']['コメント(1)']] = $fetch->urs_commant1;
		$data[$col['その他']['コメント(2)']] = $fetch->urs_commant2;
		$data[$col['その他']['コメント(3)']] = $fetch->urs_commant3;
		$data[$col['その他']['コメント(4)']] = $fetch->urs_commant4;
		$data[$col['その他']['コメント(5)']] = $fetch->urs_commant5;
		$data[$col['その他']['コメント(6)']] = $fetch->urs_commant6;

		$data[$col['販売店舗スタッフ']['購入状況']] = $fetch->urs_kounyuu_joukyou;
		$data[$col['販売店舗スタッフ']['購入車名']] = $fetch->urs_kounyuu_shamei;
	}

	// お客様情報変更追加情報セット
	$sql = "SELECT * FROM t_user_personal WHERE ups_user_id=$user_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$user_id = $fetch->ups_user_id;

		$col_org = &$col['お客様情報'];
		$col_set = &$col['変更追加取得情報(お客様)'];
		user_chage_info($fetch->ups_name_kana, $data[$col_org['お客様名（ｶﾅ）']], $data[$col_set['お客様名（カナ）']]);
		user_chage_info($fetch->ups_name_kanji, $data[$col_org['お客様名（漢字）']], $data[$col_set['お客様名（漢字）']]);
		user_chage_info($fetch->ups_zip_cd, $data[$col_org['郵便番号']], $data[$col_set['〒']]);
		user_chage_info($fetch->ups_address0, $data[$col_org['住所0']], $data[$col_set['住所0']]);
		user_chage_info($fetch->ups_address1, $data[$col_org['住所1']], $data[$col_set['住所1']]);
		user_chage_info($fetch->ups_address2, $data[$col_org['住所2']], $data[$col_set['住所2']]);
		user_chage_info($fetch->ups_tel_no, $data[$col_org['電話番号']], $data[$col_set['電話番号']]);
		user_chage_info($fetch->ups_mail_addr, $data[$col_org['E-mailアドレス']], $data[$col_set['Ｅ-Ｍａｉｌ']]);
	}

	// ランク'L'のOneToOne記録
	$mail_no = 1;
	$rank = $data[$col['テレコール結果']['ランク']];
	if ($rank == 'L') {
		// 最後の不達メール送信日取得
		$sql = "SELECT cml_date"
				. " FROM t_comm_log"
				. " WHERE cml_user_id=$user_id AND cml_err_mail_flag=true"
				. " ORDER BY cml_comm_id DESC"
				. " LIMIT 1";
		$date = db_fetch1($sql);

		// １通も送信していない場合は、最初の初期フォローメールの送信日を取得
		if ($date == '') {
			$sql = "SELECT urg_follow_date"
					. " FROM t_user_regist"
					. " WHERE urg_user_id=$user_id";
			$date = db_fetch1($sql);
		}

		// 取得した日付を1to1記録の送受信日にセット
		if ($date != '') {
			$data[$col['One to One記録1']['送受信日']] = date('Ymd', sql_time($date));
			$mail_no++;
		}
	}

	// ランク'E'のOneToOne記録
	if ($rank == 'E') {
		// メール解除日取得
		$sql = "SELECT ust_cancel_date"
				. " FROM t_user_status"
				. " WHERE ust_user_id=$user_id";
		$date = db_fetch1($sql);

		// メール解除でなければ最後の不達メールの送信日を取得
		if ($date == '') {
			$sql = "SELECT cml_date"
					. " FROM t_comm_log"
					. " WHERE cml_user_id=$user_id AND cml_err_mail_flag=true"
					. " ORDER BY cml_comm_id DESC"
					. " LIMIT 1";
			$date = db_fetch1($sql);
		}

		// 取得した日付を1to1記録の送受信日にセット
		if ($date != '') {
			$data[$col['One to One記録1']['送受信日']] = date('Ymd', sql_time($date));
			$mail_no++;
		}
	}

	// OneToOne記録セット（最新から最大５件）
	$sql = "SELECT cml_comm_id,cml_date,cml_from_name,cml_send_recv,cmc_subject"
			. " FROM t_comm_log"
			. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
			. " WHERE cml_user_id=$user_id AND (cml_send_recv='R' OR cmc_comm_type=4)"
			. " ORDER BY cml_comm_id DESC"
			. " LIMIT 5";
	$sql = "SELECT * FROM ($sql) sub ORDER BY cml_comm_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow && $mail_no <= 5; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$col2 = &$col['One to One記録' . $mail_no++];
		$data[$col2['送受信日']] = date('Ymd', sql_time($fetch->cml_date));
		$data[$col2['送受信時間']] = date('His', sql_time($fetch->cml_date));
		$data[$col2['送信者']] = $fetch->cml_from_name;
		$data[$col2['送受信区分']] = $fetch->cml_send_recv;
		$data[$col2['Mail件名']] = $fetch->cmc_subject;
	}

	// Mail数セット
	$sql = "SELECT COUNT(*)"
			. " FROM t_comm_log"
			. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
			. " WHERE cml_user_id=$user_id AND (cml_send_recv='R' OR cmc_comm_type=4)";
	$result = db_exec($sql);
	$data[$col['Mail数']['One to One']] = pg_fetch_result($result, 0, 0);


	return csv_data($data, COLUMN_NUM_RES);
}

// データレイアウト取得
function get_data_layout(&$layout, &$col) {
	// レイアウトデータ取得
	$sql = "SELECT * FROM t_data_layout ORDER BY dtl_column_no";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$layout[(int)$fetch->dtl_column_no] = $fetch;

		// 入力データのカラム位置
		if ($fetch->dtl_input == DBTRUE || $fetch->dtl_output == DBTRUE)
			$col[$fetch->dtl_group_name][$fetch->dtl_item_name] = $fetch->dtl_column_no;
	}
}

// お客様情報変更追加情報セット
function user_chage_info(&$data_new, &$data_org, &$data_set) {
	if ($data_new != $data_org)
		$data_set = $data_new;
}

// OneToOneメールCSV出力
function output_1to1_data($user_id) {
	$data = array();

	// オリジナルデータを配列に読み込み
	$sql = "SELECT udt_column_no,udt_value"
			. " FROM t_user_data"
			. " WHERE udt_user_id=$user_id AND udt_column_no<=8"
			. " ORDER BY udt_column_no";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$data[(int)$fetch->udt_column_no] = $fetch->udt_value;
	}

	$sql = "SELECT cmp_1to1_cd FROM t_user_regist JOIN t_campaign ON cmp_campaign_cd=urg_campaign_cd WHERE urg_user_id=$user_id";
	$data[1] = db_fetch1($sql);

	// OneToOne記録セット
	$sql = "SELECT cml_comm_id,cml_date,cml_from_addr,cml_send_recv,cml_to_addr,cmc_subject,cmc_body"
			. " FROM t_comm_log"
			. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
			. " WHERE cml_user_id=$user_id AND (cml_send_recv='R' OR cmc_comm_type=4)"
			. " ORDER BY cml_comm_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$data[9] = $i + 1;
		$data[10] = $nrow;
		$data[11] = $fetch->cml_send_recv == 'S' ? 1 : 2;
		$data[12] = $fetch->cml_from_addr;
		$data[13] = date('Ymd', sql_time($fetch->cml_date));
		$data[14] = date('His', sql_time($fetch->cml_date));
		$data[15] = $fetch->cml_to_addr;
		$data[16] = '';
		$data[17] = $fetch->cmc_subject;
		$data[18] = $fetch->cmc_body;

		$csv[] = csv_data($data, COLUMN_NUM_1TO1);
	}

	return $csv;
}

// データをCSV出力
function csv_data(&$data, $column_num) {
	for ($i = 1; $i <= $column_num; $i++)
		set_csv_export($csv, $data[$i]);

	return $csv;
}
?>