<?
/******************************************************
' System :お酒と買物のアンケートモニターバッチ処理
' Content:督促メール配信処理
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/database.php");
include("$inc/define.php");
include("$inc/mail.php");
include("$inc/search.php");
include("$inc/batch_log.php");
include("mail_report.php");
include("$inc/encrypt.php");

define('FILE_NAME', __FILE__);

// 最大実行時間（３分）
set_time_limit(180);

send_reminder_mail();
exit;

// 全配信処理
function send_reminder_mail() {

	db_begin_trans();

	$send_num = 0;
	// 送信可能メール取得
	$sql = "SELECT rm_reminder_id,rm_search_id,rm_mail_title,rm_mail_header,rm_mail_body,rm_mail_footer,rm_send_date,me_enquete_id, en_title,en_end_date,me_ans_cond,en_enq_type,en_point" . 
			" FROM t_reminder JOIN t_my_enquete ON rm_reminder_id=me_reminder_id LEFT JOIN t_enquete ON me_enquete_id=en_enquete_id" . 
			" WHERE me_reminder_id IS NOT NULL AND rm_status=0 AND (rm_send_date IS NULL OR rm_send_date<CURRENT_TIMESTAMP)";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$sub_seq = 0;
		$reminder_id = $fetch->rm_reminder_id;
		$enquete_id = $fetch->me_enquete_id;
		$select = array();
		$end_date_y = get_datepart('Y', $fetch->en_end_date);
		$end_date_m = get_datepart('M', $fetch->en_end_date);
		$end_date_d = get_datepart('D', $fetch->en_end_date);
		$end_date_h = get_datepart('h', $fetch->en_end_date);

		// アンケートメール生成
		$header = $fetch->rm_mail_header;
		$body = $fetch->rm_mail_body;
		$footer = $fetch->rm_mail_footer;
		$mail_text = trim($header) . "\n\n" . trim($body) . "\n\n" . trim($footer);

		// send_mail_data情報
		$subject = str_replace('%TITLE%', $fetch->en_title, $fetch->rm_mail_title);

		$mail_text = str_replace('%TITLE%', $fetch->en_title, $mail_text);
		$mail_text = str_replace('%LIMIT_DATE%', mb_convert_kana("{$end_date_m}月{$end_date_d}日（" . get_week(strtotime("$end_date_y/$end_date_m/$end_date_d")) . "）{$end_date_h}時", 'N'), $mail_text);
		$mail_text = str_replace('%QUESTION%', mb_convert_kana(get_question_num($enquete_id), 'N'), $mail_text);
		$mail_text = str_replace('%SANKA_JOUKEN%', htmlspecialchars(get_ans_cond($fetch->me_ans_cond)), $mail_text);
		$mail_text = str_replace('%ENQUETE_URL%', get_enq_url(), $mail_text);
		$mail_text = str_replace('%POINT%', number_format($fetch->en_point), $mail_text);

		if ($fetch->en_enq_type == 1)
			$sys = get_system_info('sy_enq_domain,sy_enq_account,sy_enq_mail AS from_name');
		else
			$sys = get_system_info('sy_enq_domain,sy_enq_account,sy_enq_web AS from_name');
		$enq_domain = $sys->sy_enq_domain;
		$enq_account = strtok($sys->sy_enq_account, '%');
		$from = "$enq_account@$enq_domain";

		// メール送信テーブルにセット
		$send_mail_id = send_mail_data($subject, $from, '', $mail_text, '', $fetch->rm_send_date, $sys->from_name);

		// 該当者抽出
		if($fetch->rm_search_id) {
			$search = new search_class;
			$search->read_db($fetch->rm_search_id);
			$add_where = ' AND ' . $search->make_sql();
		} else {
			$add_where = ' AND mn_status=0';
		}

		$sub2 = "SELECT an_monitor_id FROM t_answer WHERE an_enquete_id= $enquete_id";
		$sql = "SELECT $send_mail_id, mn_mail_addr,mn_monitor_id,mn_name1 FROM t_monitor JOIN t_enquete_list ON mn_monitor_id=el_monitor_id WHERE el_enquete_id= $enquete_id AND mn_monitor_id NOT IN ($sub2)"
				. $add_where;
		$result2 = db_exec($sql);
		$nrow2 = pg_num_rows($result2);

		for ($n = 0; $n < $nrow2; $n++) {
			$fetch2 = pg_fetch_object($result2, $n);

			// アンケートパラメータ取得
			$enquete_param = encrypt_param($enquete_id, $fetch2->mn_monitor_id);

			// メール送信リストに追加
			$sub_seq = $n + 1;
			send_mail_list($send_mail_id, $fetch2->mn_mail_addr, $sub_seq, $mail_header);
			reminder_list($enquete_id,$reminder_id,$fetch2->mn_monitor_id);


			// 差込データ
			send_mail_embed($send_mail_id, $sub_seq, '%MAIL_ADDR%', $fetch2->mn_mail_addr);
			send_mail_embed($send_mail_id, $sub_seq, '%MONITOR_NAME%', $fetch2->mn_name1);
			if ($fetch->en_enq_type == 1)
				send_mail_embed($send_mail_id, $sub_seq, '%%FROM_ADDR%%', "$enq_account-$enquete_param@$enq_domain");
			else
				send_mail_embed($send_mail_id, $sub_seq, '%ENQUETE_PARAM%', $enquete_param);
		}

		send_mail_ok($send_mail_id);

		// 事務局に送信完了通知
		report_admin('条件指定督促メール', $nrow2, $nrow, $subject, $mail_text);

		// エラーチェック
		$err_msg = db_errormessage();
		if ($err_msg) {
			db_rollback();

			// バッチログ書き込み（エラー）
			$desc = "条件指定督促メール処理でエラーが発生しました。($err_msg)";
			batch_log(9, FILE_NAME, $desc);
			exit;
		} else {
			// バッチログ書き込み（正常）
			if ($nrow > 0) {
				$desc = "条件指定督促メール配信(ID=$reminder_id,ENQUETE_ID=$enquete_id)を{$nrow2}通送信しました。";
				batch_log(0, FILE_NAME, $desc);
			}
		}

		$send_num += $nrow2;
	

	// メールを配信済みにセット
	if ($reminder_id) {
		$sql = "UPDATE t_reminder SET rm_status=1,rm_real_send_date=CURRENT_TIMESTAMP,rm_real_send=$send_num WHERE rm_reminder_id=$reminder_id";
		db_exec($sql);
	}
    }
	db_commit_trans();
    
}

function reminder_list($enquete_id,$reminder_id,$monitor_id){
	$sql = "INSERT INTO t_reminder_list (rl_enquete_id,rl_reminder_id,rl_monitor_id,rl_send_date,rl_status)"
			. "VALUES ($enquete_id,$reminder_id,$monitor_id,CURRENT_TIMESTAMP,1) ";
	db_exec($sql);
}

// 質問数取得
function get_question_num($enq_id) {

	$sql = "SELECT COUNT(*) FROM t_enq_question WHERE eq_enquete_id=$enq_id";
	return db_fetch1($sql);
}

function get_ans_cond($ans_cond) {
	$ans_cond_ary = explode("\n", trim($ans_cond));
	foreach ($ans_cond_ary as $ans_cond)
			$ans_cond_str .= "■ {$ans_cond}\n";
	return $ans_cond_str;
}

function get_enq_url() {
	$fetch = get_system_info('sy_url_monitor');
	$url = "{$fetch->sy_url_monitor}index.php?id=%ENQUETE_PARAM%";

	return $url;
}

// 曜日取得
function get_week($date) {
	$week = array('日', '月', '火', '水', '木', '金', '土');
	return $week[date('w', $date)];
}

?>
