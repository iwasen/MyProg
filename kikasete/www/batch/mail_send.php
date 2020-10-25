<?
/******************************************************
' System :きかせて・netバッチ処理
' Content:アンケートメール送信処理
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/database.php");
include("$inc/batch_log.php");
include("$inc/mail.php");
include("$inc/encrypt.php");
include("$inc/point.php");
include("$inc/str_replace.php");
include("mail_report.php");

define('FILE_NAME', __FILE__);

// 二重起動チェック
if (!dup_check('lock_mail_send'))
	exit;

// 最大実行時間（３分）
set_time_limit(180);

click_response();
send_pjt_mail();
exit;

// クリックレスポンス処理
function click_response() {
	$sql = "SELECT jb_enquete_id,jb_send_timing,jb_send_timing_h,jb_send_timing_d,jb_enquete_id,jb_start_date,jb_sql"
			. " FROM t_job JOIN t_enquete ON en_enquete_id=jb_enquete_id"
			. " WHERE jb_send_status=1 AND jb_job_type=2 AND en_status=5";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	if ($nrow) {
		db_begin_trans();

		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$enquete_id = $fetch->jb_enquete_id;
			$where = $fetch->jb_sql;
			if ($where != '') {
				$sql = "SELECT mn_monitor_id FROM t_monitor WHERE $where"
						. " AND NOT EXISTS (SELECT * FROM t_enquete_list WHERE el_enquete_id=$enquete_id AND el_monitor_id=mn_monitor_id)";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++) {
					$fetch2 = pg_fetch_object($result2, $j);

					switch ($fetch->jb_send_timing) {
					case 1:
						$send_date = date('Y/m/d H:i:s', time() + $fetch->jb_send_timing_h * 60 * 60);
						break;
					case 2:
						$send_date = date('Y/m/d H:i:s', time() + $fetch->jb_send_timing_d * 60 * 60 * 24);
						break;
					default:
						$send_date = 'now';
						break;
					}

					$sql = "INSERT INTO t_enquete_list (el_enquete_id,el_monitor_id,el_send_date)"
							. " VALUES ($enquete_id,$fetch2->mn_monitor_id,'$send_date')";
					db_exec($sql);
				}
			}
		}

		db_commit_trans();
	}
}

// プロジェクトアンケートメール送信
function send_pjt_mail() {
	$sql = "SELECT jb_job_id,jb_job_type,jb_enquete_id,jb_point_jushin,jb_virgin_mail,jb_send_num,jb_real_send,jb_mail_format,jb_category,jb_search_id,en_mail_subject,en_mail_contents,en_enq_type"
			. " FROM t_job JOIN t_enquete ON en_enquete_id=jb_enquete_id"
			. " WHERE jb_send_status=1 AND CURRENT_TIMESTAMP BETWEEN jb_start_date AND jb_end_date AND en_status=5";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		send_projet_mail($fetch->jb_job_id, $fetch->jb_job_type, $fetch->jb_enquete_id, $fetch->jb_point_jushin, $fetch->jb_virgin_mail, $fetch->jb_send_num, $fetch->jb_real_send, $fetch->en_mail_subject, $fetch->en_mail_contents, $fetch->en_enq_type, $fetch->jb_mail_format, $fetch->jb_category, $fetch->jb_search_id);
	}
}

function send_projet_mail($job_id, $job_type, $enquete_id, $point_jushin, $virgin_mail, $send_num, $real_send, $subject, $body, $enq_type, $mail_format, $category, $search_id) {
	$kyoumi_genre = get_kyoumi_genre($search_id);

	$sql = "SELECT mn_monitor_id,mn_mail_addr,mn_name1,mn_status"
			. " FROM t_enquete_list JOIN t_monitor ON mn_monitor_id=el_monitor_id"
			. " WHERE el_enquete_id=$enquete_id AND el_status=0 AND el_send_date<=CURRENT_TIMESTAMP"
			. " LIMIT 1000";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	if ($nrow) {
		$send_status = 1;

		db_begin_trans();

		$sql = "LOCK t_monitor_point IN SHARE MODE";
		db_exec($sql);

		// アンケートメール生成
		switch ($category) {
		case 1:		// きかせて
			if ($enq_type == 1)
				$fetch = get_system_info('sy_enq_domain,sy_enq_account,sy_enq_mail AS from_name');
			else
				$fetch = get_system_info('sy_enq_domain,sy_enq_account,sy_enq_web AS from_name');
			$enq_domain = $fetch->sy_enq_domain;
			$enq_account = strtok($fetch->sy_enq_account, '%');
			$from = "$enq_account@$enq_domain";
			break;
		case 2:		// はいめーる
			if ($enq_type == 1)
				$fetch = get_system_info('sy_haimail_addr,sy_haimail_domain,sy_haimail_account,sy_haimail_mail AS from_name');
			else
				$fetch = get_system_info('sy_haimail_addr,sy_haimail_domain,sy_haimail_account,sy_haimail_web AS from_name');
			$enq_domain = $fetch->sy_haimail_domain;
			$enq_account = strtok($fetch->sy_haimail_account, '%');
			$from = $fetch->sy_haimail_addr;
			break;
		}

		replace_kyoumi_genre($mail_format, $search_id, $body);

		$send_mail_id = send_mail_data($subject, $from, '', $body, '', '', $fetch->from_name, $mail_format);

		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$monitor_id = $fetch->mn_monitor_id;

			// 退会者を除く
			if ($fetch->mn_status == 9) {
				$sql = "DELETE FROM t_enquete_list WHERE el_enquete_id=$enquete_id AND el_monitor_id=$monitor_id";
				db_exec($sql);
				continue;
			}

			// アンケートパラメータ取得
			$enquete_param = encrypt_param($enquete_id, $monitor_id);

			if ($enq_type == 1)
				$mail_header = "Reply-To: $enq_account-$enquete_param@$enq_domain";
			else
				$mail_header = '';

			// メール送信リストに追加
			$sub_seq = $i + 1;
			send_mail_list($send_mail_id, $fetch->mn_mail_addr, $sub_seq, $mail_header);

			// 差込データ
			send_mail_embed($send_mail_id, $sub_seq, '%MAIL_ADDR%', $fetch->mn_mail_addr);
			send_mail_embed($send_mail_id, $sub_seq, '%MONITOR_NAME%', $fetch->mn_name1);
			send_mail_embed($send_mail_id, $sub_seq, '%CLICK%', $monitor_id);
			send_mail_embed($send_mail_id, $sub_seq, '%JOB_ID%', $job_id);
			switch ($enq_type) {
			case 1:		// メール
//				send_mail_embed($send_mail_id, $sub_seq, '%%FROM_ADDR%%', "$enq_account-$enquete_param@$enq_domain");
				break;
			case 2:		// Web
				send_mail_embed($send_mail_id, $sub_seq, '%ENQUETE_PARAM%', $enquete_param);
				break;
			}

			// 送信済みにセット
			$sql = "UPDATE t_enquete_list SET el_status=1,el_send_date=CURRENT_TIMESTAMP WHERE el_enquete_id=$enquete_id AND el_monitor_id=$monitor_id";
			db_exec($sql);

			// モニターポイント情報更新
			monitor_point($monitor_id, PT_MAIL_RECV, $point_jushin, null, null, $enquete_id);

			// 終了チェック
			if (++$real_send >= $send_num) {
				$send_status = 9;
				break;
			}
		}

		send_mail_ok($send_mail_id);

		// 終了チェック
		if ($job_type == 0 && $virgin_mail == 0) {
			$sql = "SELECT COUNT(*) FROM t_enquete_list WHERE el_enquete_id=$enquete_id AND el_status=0";
			if (db_fetch1($sql) == 0)
				$send_status = 9;
		}

		// 実配信数設定
		$sql = "UPDATE t_job SET jb_send_status=$send_status,jb_real_send=$real_send WHERE jb_job_id=$job_id";
		db_exec($sql);

		// 送信レポート
		if ($send_status == 9) {
			report_pjt_mail($job_id);

			// アンケート無しの場合は終了日時セット
			if ($enq_type == 0) {
				$sql = "UPDATE t_enquete SET en_end_date=CURRENT_TIMESTAMP WHERE en_enquete_id=$enquete_id AND en_end_date IS NULL";
				db_exec($sql);
			}
		}

		db_commit_trans();
	}
}

// 検索条件の興味ジャンルを取得
function get_kyoumi_genre($search_id) {
	$name = '';

	if ($search_id) {
		$sql = "SELECT sr_genre FROM t_search WHERE sr_search_id=$search_id";
		$genre = db_fetch1($sql);

		if ($genre != '') {
			$sql = "SELECT kg_name FROM m_genre WHERE kg_genre_cd IN ($genre) ORDER BY kg_order";
			$result = db_exec($sql);
			$nrow = pg_numrows($result);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_row($result, $i);
				$name .= "「{$fetch[0]}」";
			}
		}
	}

	return $name;
}
?>