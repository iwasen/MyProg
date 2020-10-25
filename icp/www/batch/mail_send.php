<?
/******************************************************
' System :ICPバッチ処理
' Content:メール送信処理
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/batch.php");
include("$inc/auth.php");
include("$inc/decode.php");
include("$inc/status.php");
include("$inc/mail.php");
include("$inc/room.php");
include("$inc/format.php");
include("$inc/define.php");
include("$inc/variable.php");
include("$inc/remark.php");

// 二重起動チェック
if (!dup_check('lock_mail_send'))
	exit;

// 最大実行時間（１０分）
set_time_limit(600);

// 現時刻取得
$g_time = time();
$g_sql_time = sql_date(date('Y-m-d H:i:s', $g_time));

send_guide_mail();
send_start_mail();
send_end_mail();

send_all_mail();

exit;

// 案内メール送信処理
function send_guide_mail() {
	global $g_job_status, $g_sql_time;

	// 送信可能メール取得
	$sql = "SELECT jb_job_id,jb_job_cd,jb_guide_subject,jb_guide_from_name,jb_guide_content,jb_guide_add_mail,jb_room_header,jb_room_footer,pj_pjt_cd"
			. " FROM t_job"
			. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
			. " WHERE jb_guide_status=2 AND jb_status IN ({$g_job_status['待機中']},{$g_job_status['実施中']}) AND jb_guide_send_date<=$g_sql_time";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$job_id = $fetch->jb_job_id;

		// 差し込み変数処理
		$content = replace_room_var($job_id, $fetch->jb_guide_content);
		$header = replace_room_var($job_id, $fetch->jb_room_header);
		$footer = replace_room_var($job_id, $fetch->jb_room_footer);

		db_begin_trans();

		// 発言ログに追加
		$rec['rm_job_id'] = sql_number($job_id);
		$rec['rm_remark_type'] = sql_number(RT_GUIDE);
		$rec['rm_from'] = sql_char(join_mail_from(get_help_mail_addr($fetch->pj_pjt_cd, $fetch->jb_job_cd), $fetch->jb_guide_from_name));
		$rec['rm_message_id'] = sql_char(make_message_id());
		$rec['rm_subject'] = sql_char($fetch->jb_guide_subject);
		$rec['rm_content'] = sql_char($content);
		$rec['rm_room_header'] = sql_char($header);
		$rec['rm_room_footer'] = sql_char($footer);
		$rec['rm_web_mail'] = sql_number(RM_BATCH);
		$rec['rm_add_send_to'] = sql_char($fetch->jb_guide_add_mail);
		db_insert('l_remark', $rec);
		$remark_id = get_current_seq('l_remark', 'rm_remark_id');

		// 案内メールを送信済みに設定
		$rec['jb_guide_status'] = sql_number(9);
		db_update('t_job', $rec, "jb_job_id=$job_id");

		// エラーチェック
		$err_msg = db_errormessage();
		if ($err_msg) {
			db_rollback();

			// バッチログ書き込み（エラー）
			$desc = "JOBID={$job_id}の案内メールでエラーが発生しました。($err_msg)";
			batch_log(9, __FILE__, $desc);
		} else {
			// バッチログ書き込み（正常）
			$desc = "JOBID={$job_id}の案内メールを処理しました。";
			batch_log(0, __FILE__, $desc);

			db_commit_trans();
		}
	}
}

// 開始メール送信処理
function send_start_mail() {
	global $g_job_status, $g_sql_time;

	// 送信可能メール取得
	$sql = "SELECT jb_job_id,jb_job_cd,jb_start_subject,jb_start_from_name,jb_start_content,jb_start_add_mail,jb_room_header,jb_room_footer,pj_pjt_cd"
			. " FROM t_job"
			. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
			. " WHERE jb_start_status=2 AND jb_status IN ({$g_job_status['待機中']},{$g_job_status['実施中']}) AND jb_start_send_date<=$g_sql_time";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$job_id = $fetch->jb_job_id;

		// 差し込み変数処理
		$content = replace_room_var($job_id, $fetch->jb_start_content);
		$header = replace_room_var($job_id, $fetch->jb_room_header);
		$footer = replace_room_var($job_id, $fetch->jb_room_footer);

		db_begin_trans();

		// 発言ログに追加
		$rec['rm_job_id'] = sql_number($job_id);
		$rec['rm_remark_type'] = sql_number(RT_START);
		$rec['rm_disp_type'] = sql_number(DT_REMARK);
		$rec['rm_disp_member_id'] = sql_number(0);
		$rec['rm_seq_no'] = "(SELECT COALESCE(max(rm_seq_no),0)+1 FROM l_remark WHERE rm_job_id=$job_id AND rm_disp_type=1)";
		$rec['rm_parent_remark_id'] = sql_number(0);
		if ($fetch->jb_room_type == ROOM_PIR)
			$rec['rm_from'] = sql_char(join_mail_from(get_mc_mail_addr($fetch->pj_pjt_cd, $fetch->jb_job_cd), $fetch->jb_start_from_name));
		else
			$rec['rm_from'] = sql_char(join_mail_from(get_room_mail_addr($fetch->pj_pjt_cd, $fetch->jb_job_cd), $fetch->jb_start_from_name));
		$rec['rm_message_id'] = sql_char(make_message_id());
		$rec['rm_subject'] = sql_char($fetch->jb_start_subject);
		$rec['rm_content'] = sql_char($content);
		$rec['rm_room_header'] = sql_char($header);
		$rec['rm_room_footer'] = sql_char($footer);
		$rec['rm_web_mail'] = sql_number(RM_BATCH);
		$rec['rm_add_send_to'] = sql_char($fetch->jb_start_add_mail);
		db_insert('l_remark', $rec);
		$remark_id = get_current_seq('l_remark', 'rm_remark_id');

		// 開始メールを送信済みに設定
		$rec['jb_start_status'] = sql_number(9);
		db_update('t_job', $rec, "jb_job_id=$job_id");

		// エラーチェック
		$err_msg = db_errormessage();
		if ($err_msg) {
			db_rollback();

			// バッチログ書き込み（エラー）
			$desc = "JOBID={$job_id}の開始メールでエラーが発生しました。($err_msg)";
			batch_log(9, __FILE__, $desc);
		} else {
			// バッチログ書き込み（正常）
			$desc = "JOBID={$job_id}の開始メールを処理しました。";
			batch_log(0, __FILE__, $desc);

			db_commit_trans();
		}
	}
}

// 終了メール送信処理
function send_end_mail() {
	global $g_job_status, $g_sql_time;

	// 送信可能メール取得
	$sql = "SELECT jb_job_id,jb_job_cd,jb_end_subject,jb_end_from_name,jb_end_content,jb_end_add_mail,jb_room_header,jb_room_footer,pj_pjt_cd"
			. " FROM t_job"
			. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
			. " WHERE jb_end_status=2 AND jb_status IN ({$g_job_status['実施中']},{$g_job_status['休止中']},{$g_job_status['猶予中']}) AND jb_end_send_date<=$g_sql_time";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$job_id = $fetch->jb_job_id;

		// 差し込み変数処理
		$content = replace_room_var($job_id, $fetch->jb_end_content);
		$header = replace_room_var($job_id, $fetch->jb_room_header);
		$footer = replace_room_var($job_id, $fetch->jb_room_footer);

		db_begin_trans();

		// 発言ログに追加
		$rec['rm_job_id'] = sql_number($job_id);
		$rec['rm_remark_type'] = sql_number(RT_END);
		$rec['rm_from'] = sql_char(join_mail_from(get_help_mail_addr($fetch->pj_pjt_cd, $fetch->jb_job_cd), $fetch->jb_end_from_name));
		$rec['rm_message_id'] = sql_char(make_message_id());
		$rec['rm_subject'] = sql_char($fetch->jb_end_subject);
		$rec['rm_content'] = sql_char($content);
		$rec['rm_room_header'] = sql_char($header);
		$rec['rm_room_footer'] = sql_char($footer);
		$rec['rm_web_mail'] = sql_number(RM_BATCH);
		$rec['rm_add_send_to'] = sql_char($fetch->jb_end_add_mail);
		db_insert('l_remark', $rec);
		$remark_id = get_current_seq('l_remark', 'rm_remark_id');

		// 終了メールを送信済みに設定
		$rec['jb_end_status'] = sql_number(9);
		db_update('t_job', $rec, "jb_job_id=$job_id");

		// エラーチェック
		$err_msg = db_errormessage();
		if ($err_msg) {
			db_rollback();

			// バッチログ書き込み（エラー）
			$desc = "JOBID={$job_id}の終了メールでエラーが発生しました。($err_msg)";
			batch_log(9, __FILE__, $desc);
		} else {
			// バッチログ書き込み（正常）
			$desc = "JOBID={$job_id}の終了メールを処理しました。";
			batch_log(0, __FILE__, $desc);

			db_commit_trans();
		}
	}
}

// メール配信処理
function send_all_mail() {
	// 未配信メール取得
	$sql = "SELECT rm_remark_id,rm_remark_type,rm_disp_member_id,rm_add_send_to,jb_room_type"
			. " FROM l_remark"
			. " JOIN t_job ON jb_job_id=rm_job_id"
			. " WHERE rm_send_flag=false";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$remark_id = $fetch->rm_remark_id;

		// 配信先抽出条件取得
		switch ($fetch->rm_remark_type) {
		case RT_GUIDE:
		case RT_START:
		case RT_END:
			$where = '';
			break;
		case RT_REMARK:
			if ($fetch->jb_room_type == ROOM_PIR)
				$where = "(jm_member_id=$fetch->rm_disp_member_id OR " . cond_participant_auth('司会者') . ") AND " . cond_participant_auth('メール受信') . " AND " . cond_participant_auth('発言ON/OFF');
			else
				$where = cond_participant_auth('メール受信') . " AND " . cond_participant_auth('発言ON/OFF');
			break;
		case RT_HELP:
		case RT_ANSWER:
			$where = "(jm_member_id=$fetch->rm_disp_member_id OR " . cond_participant_auth('司会者') . ")";
			break;
		default:
			$where = "false";
			break;
		}

		db_begin_trans();

		// 対象者に配信
		send_remark($remark_id, $where, $fetch->rm_add_send_to);

		// 発言を配信済みにセット
		$rec['rm_send_flag'] = sql_bool(true);
		db_update('l_remark', $rec, "rm_remark_id=$remark_id");

		// エラーチェック
		$err_msg = db_errormessage();
		if ($err_msg) {
			db_rollback();

			// バッチログ書き込み（エラー）
			$desc = "REMARKID={$remark_id}の送信処理でエラーが発生しました。($err_msg)";
			batch_log(9, __FILE__, $desc);
		} else {
			// バッチログ書き込み（正常）
			$desc = "REMARKID={$remark_id}を送信処理しました。";
			batch_log(0, __FILE__, $desc);

			db_commit_trans();
		}
	}
}
?>