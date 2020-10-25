<?
/******************************************************
' System :きかせて・net共通
' Content:ポイント関連処理
'******************************************************/

// ポイント種類
define('PT_ENQUETE', 1);		// アンケート回答
define('PT_SEISAN', 2);			// 精算
define('PT_MAIL_RECV', 3);		// アンケートメール受信
define('PT_MY_MONITOR', 4);		// Myモニター返信
define('PT_MAIL_PJT', 5);		// プロジェクトメール受信　※未使用
define('PT_MY_PARTNER', 6);		// Myパートナー
define('PT_IMPORT', 7);			// CSVからインポート
define('PT_AFFILIATE', 8);		// アフィリエイト
define('PT_KIRIN', 9);			// キリン
define('PT_JIMUKYOKU', 10);		// 事務局付与
define('PT_EXPIRE', 11);		// ポイント失効
define('PT_CAMPAIGN', 12);		// 更新キャンペーンポイント
define('PT_BIRTHDAY', 13);		// 誕生日メールによる登録情報更新
define('PT_RESIGN', 14);		// 退会時消滅ポイント
define('PT_SEISAN_CANCEL', 15);	// 精算却下

// 付与ポイント数
define('GP_AFFILIATE', 100);	// アフィリエイト

// モニターポイント加算
function monitor_point($monitor_id, $kind, $point, $valid_point=null, $date=null, $enquete_id=null) {
	db_begin_trans();

	$point = (int)$point;

	// ポイントログに追加
	if ($point != 0) {
		$sql = sprintf("INSERT INTO l_point (lp_member_kind,lp_member_id,lp_point_kind,lp_point,lp_date,lp_enquete_id,lp_expire_date) VALUES (1,%s,%s,%s,%s,%s,%s)",
				sql_number($monitor_id),
				sql_number($kind),
				sql_number($point),
				sql_date($date, 'current_timestamp'),
				sql_number($enquete_id),
				($point > 0) ? 'DEFAULT' : 'NULL');
		db_exec($sql);
	}

	// 回答履歴の更新
	switch ($kind) {
	case PT_ENQUETE:
		$history_flag = true;
		if ($enquete_id) {
			$sql = "SELECT en_enq_kind FROM t_enquete WHERE en_enquete_id=$enquete_id";
			switch (db_fetch1($sql)) {
			case 3:		// プロジェクトアンケートは除く
				$history_flag = false;
				break;
			case 6:		// Proアンケートでファインディング有りの本アンケートは除く
				$sql = "SELECT COUNT(*) FROM t_pro_enquete WHERE mep_enquete2_id=$enquete_id AND mep_finding_flag=true";
				if (db_fetch1($sql))
					$history_flag = false;
			}
		}
		if ($history_flag) {
			$sql = "SELECT mp_enq_history FROM t_monitor_point WHERE mp_monitor_id=$monitor_id";
			$enq_history = db_fetch1($sql);

			for ($i = 0; $i < strlen($enq_history); $i++) {
				if ($enq_history{$i} == '0') {
					$enq_history{$i} = '1';
					break;
				}
			}

			$set .= ",mp_enq_answer=mp_enq_answer+1,mp_enq_history='$enq_history'";
		}
		break;
	}

	if ($valid_point === null)
		$valid_point = $point;

	// はいめーるポイント取得
	$haimei_point = 0;
	if ($enquete_id) {
		$sql = "SELECT jb_category FROM t_job WHERE jb_enquete_id=$enquete_id";
		if (db_fetch1($sql) == 2)
			$haimei_point = $point;
	}

	// モニターポイント情報更新
	$add_point = ($kind == PT_SEISAN_CANCEL) ? 0 : max($point, 0);
	$sql = "UPDATE t_monitor_point SET mp_curr_point=mp_curr_point+$valid_point,mp_total_point=mp_total_point+$add_point,mp_haimail_point=mp_haimail_point+$haimei_point $set"
			. " WHERE mp_monitor_id=$monitor_id";
	db_exec($sql);

	// ポイント有効期間延長
	if ($point > 0 && $kind != PT_AFFILIATE)
		point_period_extension($monitor_id);

	db_commit_trans();
}

// ポイント有効期間延長
function point_period_extension($monitor_id) {
	// ポイント延長期間取得
	$fetch = get_system_info('sy_point_extension');
	$point_extension = $fetch->sy_point_extension + 1;

	// ポイント有効期間を設定
	$sql = "UPDATE t_monitor_point"
			. " SET mp_point_period=date_trunc('month', current_date) + interval '$point_extension month', mp_expire_notify_flag=FALSE"
			. " WHERE mp_monitor_id=$monitor_id AND mp_point_period < date_trunc('month', current_date) + interval '$point_extension month'";
	db_exec($sql);
}

// 更新キャンペーンポイント加算
function point_update_campaign($monitor_id) {
	$fetch = get_system_info('sy_point_campaign_period1,sy_point_campaign_period2,sy_point_campaign_point');
	if ($fetch->sy_point_campaign_period1 != '' && $fetch->sy_point_campaign_period2 != '' && $fetch->sy_point_campaign_point != '') {
		// キャンペーン期間チェック
		$start_period = sql_time($fetch->sy_point_campaign_period1);
		$end_period = sql_time($fetch->sy_point_campaign_period2) + 24*60*60;
		$campaign_point = $fetch->sy_point_campaign_point;
		$time = time();
		if ($time >= $start_period && $time < $end_period) {
			// 付与済みチェック
			$sql = "SELECT COUNT(*) FROM t_monitor_point WHERE mp_monitor_id=$monitor_id AND mp_campaign_flag=FALSE";
			if (db_fetch1($sql)) {
				db_begin_trans();

				// ポイント付与
				monitor_point($monitor_id, PT_CAMPAIGN, $campaign_point);

				// 付与済みフラグセット
				$sql = "UPDATE t_monitor_point SET mp_campaign_flag=TRUE WHERE mp_monitor_id=$monitor_id";
				db_exec($sql);

				db_commit_trans();
			}
		}
	}
}

// 最小精算可能ポイント取得
function get_min_exchange_point($shouhin) {
	switch ($shouhin) {
	case 1:		// VISAギフト券
		return 3000;
	case 4:		// 楽天銀行
		return 200;
	case 5:		// 義援金
		return 200;
	}
}

// 手数料取得
function get_charge_point($shouhin) {
	switch ($shouhin) {
	case 1:		// VISAギフト券
		return 200;
	case 4:		// 楽天銀行
		return 100;
	case 5:		// 義援金
		return 0;
	}
}
?>