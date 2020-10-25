<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」バッチ処理
' Content:販売店メール送信処理
'******************************************************/

$inc = "../inc";
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/encrypt.php");
include("$inc/batch.php");
include("$inc/search.php");
include("$inc/greeting.php");

// 二重起動チェック
if (!dup_check('lock_dealer_mail'))
	exit;

// 最大実行時間（１時間）
set_time_limit(3600);

// 販売店メール送信処理
send_dealer_mail();
exit;

// 販売店メール送信開始処理
function send_dealer_mail() {
	// 開始可能ニュース取得
	$sql = "SELECT dml_dealer_mail_id,dml_subject,dml_search_id,dml_from_name,dmc_header,dmc_body,dmc_footer"
			.	" FROM t_dealer_mail"
			. " JOIN t_dealer_mail_contents ON dml_dealer_mail_id=dmc_dealer_mail_id"
			. " WHERE dml_status=2 AND (dml_send_date IS NULL OR dml_send_date<CURRENT_TIMESTAMP) AND dml_subject IS NOT NULL"
			. " ORDER BY dml_dealer_mail_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	if ($nrow > 0) {
		// ワンクリックログインURL取得
		$login_url = get_system_info('url_user_login');

		// メール購読解除URL取得
		$stop_url = get_system_info('url_mail_stop');

		// fromアドレス取得
		$from_addr = get_system_info('addr_dealer_mail');

		// 時節の挨拶文取得
		$season_greeting = get_season_greeting();

		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			$dealer_mail_id = $fetch->dml_dealer_mail_id;
			$subject =  $fetch->dml_subject;
			$contents = make_contents($fetch->dmc_header, $fetch->dmc_body, $fetch->dmc_footer);
			$from_name = $fetch->dml_from_name;

			db_begin_trans();

			// コミュニケーション内容テーブルに保存
			$rec['cmc_comm_type'] = 1;
			$rec['cmc_subject'] = sql_char($subject);
			$rec['cmc_body'] = sql_char($contents);
			db_insert('t_comm_contents', $rec);
			$contents_id = get_current_seq('t_comm_contents', 'cmc_contents_id');

			// メール送信テーブルにセット
			$end_sql = "UPDATE t_dealer_mail SET dml_status=4 WHERE dml_dealer_mail_id=$dealer_mail_id";
			$send_mail_id = send_mail_data($subject, $from_addr, '', $contents, '', '', '0', $end_sql);

			// 差込変数有無チェック
			$emb = array();
			if (strpos($contents, '%US_NAME%') !== false)
				$emb['US_NAME'] = true;
			if (strpos($contents, '%CHANNEL%') !== false)
				$emb['CHANNEL'] = true;
			if (strpos($contents, '%MAIL_ADDR%') !== false)
				$emb['MAIL_ADDR'] = true;
			if (strpos($contents, '%CAR_NAME%') !== false)
				$emb['CAR_NAME'] = true;
			if (strpos($contents, '%DEALER%') !== false)
				$emb['DEALER'] = true;
			if (strpos($contents, '%TIMES%') !== false)
				$emb['TIMES'] = true;
			if (strpos($contents, '%LINEUP%') !== false)
				$emb['LINEUP'] = true;
			if (strpos($contents, '%UID%') !== false)
				$emb['UID'] = true;
			if (strpos($contents, '%MYPASS%') !== false)
				$emb['MYPASS'] = true;
			if (strpos($contents, '%CANCEL%') !== false)
				$emb['CANCEL'] = true;
			if (strpos($contents, '%PASSWORD%') !== false)
				$emb['PASSWORD'] = true;
			if (strpos($contents, '%TANTOUSHA%') !== false)
				$emb['TANTOUSHA'] = true;
			if (strpos($contents, '%TT_EMAIL%') !== false)
				$emb['TT_EMAIL'] = true;

			// 検索条件取得
			$search = new search_class;
			$search->read_db($fetch->dml_search_id);
			$search->make_sql($from, $where);

			// コミュニケーション履歴に追加
			$sql = "SELECT ups_user_id,ups_mail_addr,ups_name_kanji,umg_kounyu_kibou_car,ulg_login_id,ulg_password,sch_sales_channel_name,dlr_dealer_name,clu_lineup,tts_tantousha_name,tts_from_name,tts_from_addr"
					. " FROM $from"
					. " JOIN t_user_login ON ulg_user_id=ups_user_id"
					. " JOIN t_car_model ON crm_car_model_cd=umg_car_model_cd"
					. " JOIN t_dealer ON dlr_dealer_cd=umg_dealer_cd"
					. " JOIN t_sales_channel ON sch_sales_channel_cd=dlr_sales_channel_cd"
					. " JOIN t_tantousha ON tts_tantousha_id=dlr_tantousha_id"
					. " LEFT JOIN t_car_lineup ON clu_sales_channel_cd=dlr_sales_channel_cd AND clu_car_category_cd=crm_car_category_cd"
					. " WHERE $where";
			$result2 = db_exec($sql);
			$nrow2 = pg_num_rows($result2);
			for ($j = 0; $j < $nrow2; $j++) {
				$fetch = pg_fetch_object($result2, $j);

				// コミュニケーション履歴に追加
				$rec['cml_thread_comm_id'] = "currval('t_comm_log_cml_comm_id_seq')";
				$rec['cml_contents_id'] = $contents_id;
				$rec['cml_user_id'] = $fetch->ups_user_id;
				$rec['cml_send_recv'] = sql_char('S');
				$rec['cml_answer_flag'] = 'false';
				$rec['cml_from_addr'] = sql_char($from_addr);
				$rec['cml_from_name'] = sql_char($from_name);
				$rec['cml_to_addr'] = sql_char($fetch->ups_mail_addr);
				db_insert('t_comm_log', $rec);
				$comm_id = get_current_seq('t_comm_log', 'cml_comm_id');

				// 送信先リスト書き込み
				$sub_seq = $j + 1;
				send_mail_list($send_mail_id, $fetch->ups_mail_addr, $sub_seq);

				// メール識別ID取得
				$mail_id = '-C' . encrypt_id($comm_id);

				// 差込変数処理
				send_mail_embed($send_mail_id, $sub_seq, '%%FROM_ADDR%%', $fetch->tts_from_name . ' <' . str_replace('@', $mail_id . '@', $fetch->tts_from_addr) . '>');
				if ($emb['US_NAME'])
					send_mail_embed($send_mail_id, $sub_seq, '%US_NAME%', $fetch->ups_name_kanji);
				if ($emb['CHANNEL'])
					send_mail_embed($send_mail_id, $sub_seq, '%CHANNEL%', $fetch->sch_sales_channel_name);
				if ($emb['MAIL_ADDR'])
					send_mail_embed($send_mail_id, $sub_seq, '%MAIL_ADDR%', $fetch->ups_mail_addr);
				if ($emb['CAR_NAME'])
					send_mail_embed($send_mail_id, $sub_seq, '%CAR_NAME%', $fetch->umg_kounyu_kibou_car);
				if ($emb['DEALER'])
					send_mail_embed($send_mail_id, $sub_seq, '%DEALER%', trim(str_replace('株式会社', '', $fetch->dlr_dealer_name)));
				if ($emb['TIMES'])
					send_mail_embed($send_mail_id, $sub_seq, '%TIMES%', $season_greeting);
				if ($emb['LINEUP'])
					send_mail_embed($send_mail_id, $sub_seq, '%LINEUP%', $fetch->clu_lineup);
				if ($emb['UID'])
					send_mail_embed($send_mail_id, $sub_seq, '%UID%', $fetch->ups_user_id);
				if ($emb['MYPASS'])
					send_mail_embed($send_mail_id, $sub_seq, '%MYPASS%', "$login_url?t=$fetch->ulg_login_id");
				if ($emb['CANCEL'])
					send_mail_embed($send_mail_id, $sub_seq, '%CANCEL%', "$stop_url?t=$fetch->ulg_login_id");
				if ($emb['PASSWORD'])
					send_mail_embed($send_mail_id, $sub_seq, '%PASSWORD%', $fetch->ulg_password);
				if ($emb['TANTOUSHA'])
					send_mail_embed($send_mail_id, $sub_seq, '%TANTOUSHA%', $fetch->tts_tantousha_name);
				if ($emb['TT_EMAIL'])
					send_mail_embed($send_mail_id, $sub_seq, '%TT_EMAIL%', str_replace('@', $mail_id . '@', $fetch->tts_from_addr));
			}

			// 販売店メールを送信中にセット
			$rec['dml_status'] = 3;
			$rec['dml_contents_id'] = $contents_id;
			$rec['dml_send_num'] = $nrow2;
			db_update('t_dealer_mail', $rec, "dml_dealer_mail_id=$dealer_mail_id");

			// ステータスを送信可
			send_mail_ok($send_mail_id);

			// エラーチェック
			$err_msg = db_errormessage();
			if ($err_msg) {
				db_rollback();

				// バッチログ書き込み（エラー）
				$desc = "販売店メール送信処理でエラーが発生しました。($err_msg)";
				batch_log(9, __FILE__, $desc);
				break;
			} else {
				// バッチログ書き込み（正常）
				$desc = "販売店メール(ID=$dealer_mail_id)を{$nrow2}通送信しました。";
				batch_log(0, __FILE__, $desc);
			}

			db_commit_trans();
		}
	}
}
?>