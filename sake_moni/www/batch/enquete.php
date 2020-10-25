<?
/******************************************************
' System :お酒と買物のアンケートモニターバッチ処理
' Content:アンケート開始終了処理
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/batch_log.php");
include("$inc/my_enquete.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/mail.php");
include("$inc/enq_mail.php");
include("$inc/encrypt.php");
include("$inc/point.php");
include("mail_report.php");

define('FILE_NAME', __FILE__);
define('DAY_SEC', 60 * 60 * 24);

// 最大実行時間（３分）
set_time_limit(180);

start_enquete();
end_enquete();
exit;

// アンケート開始処理
function start_enquete() {
	$sql = "SELECT en_enquete_id,en_enq_kind FROM t_enquete WHERE en_status=4 AND en_start_date<=CURRENT_TIMESTAMP";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$enquete_id = $fetch->en_enquete_id;

		db_begin_trans();

		switch ($fetch->en_enq_kind) {
		case 2:   // Myアンケート
			start_my_enquete($enquete_id);
			break;
		}

		$sql = "UPDATE t_enquete SET en_status=5 WHERE en_enquete_id=$enquete_id";
		db_exec($sql);

		// アンケート回答者数テーブルに追加
		$sql = "SELECT COUNT(*) FROM t_ans_count WHERE ac_enquete_id=$enquete_id";
		if (db_fetch1($sql) == 0) {
			$rec['ac_enquete_id'] = sql_number($enquete_id);
			db_insert('t_ans_count', $rec);
		}

		batch_log(0, FILE_NAME, "アンケート(ID=$enquete_id)を開始しました。");

		db_commit_trans();
	}
}

// Myアンケート開始処理
function start_my_enquete($enquete_id) {
	$sql = "SELECT me_staff_id,me_my_enq_no FROM t_my_enquete WHERE me_enquete_id=$enquete_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$staff_id = $fetch->me_staff_id;
		$my_enq_no = $fetch->me_my_enq_no;

		// Myアンケート情報読み込み
		$myenq = new my_enquete_class;
		$myenq->read_db($staff_id, $my_enq_no);
		$enquete = &$myenq->enquete;
		$search = &$myenq->search;

		// アンケートメール生成
		$header = $myenq->mail_header;
		$body = $myenq->mail_contents;
		$footer = $myenq->mail_footer;
		$mail_text = make_enq_mail($enquete, $header, $body, $footer);
		$mail_text = str_replace('%POINT%', number_format($enquete->point), $mail_text);
		if ($enquete->enq_type == 1)
			$fetch = get_system_info('sy_enq_domain,sy_enq_account,sy_enq_mail AS from_name');
		else
			$fetch = get_system_info('sy_enq_domain,sy_enq_account,sy_enq_web AS from_name');
		$enq_domain = $fetch->sy_enq_domain;
		$enq_account = strtok($fetch->sy_enq_account, '%');
		$from = "$enq_account@$enq_domain";
		$send_mail_id = send_mail_data($myenq->mail_title, $from, '', $mail_text, '', '', $fetch->from_name);

		// アンケート対象者抽出
		$sql = "SELECT mn_monitor_id,mn_mail_addr,mn_name1 FROM t_monitor WHERE " . $search->make_sql()
				. " ORDER BY RANDOM() LIMIT $myenq->send_num";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$monitor_id = $fetch->mn_monitor_id;

			// アンケート対象者リストに登録
			$sql = "INSERT INTO t_enquete_list (el_enquete_id,el_monitor_id,el_send_date,el_status)"
					. " VALUES ($enquete_id,$monitor_id,CURRENT_TIMESTAMP,1)";
			db_exec($sql);

			// アンケートパラメータ取得
			$enquete_param = encrypt_param($enquete_id, $monitor_id);

			// メール送信リストに追加
			$sub_seq = $i + 1;
			send_mail_list($send_mail_id, $fetch->mn_mail_addr, $sub_seq, $mail_header);

			// 差込データ
			send_mail_embed($send_mail_id, $sub_seq, '%MAIL_ADDR%', $fetch->mn_mail_addr);
			send_mail_embed($send_mail_id, $sub_seq, '%MONITOR_NAME%', $fetch->mn_name1);
			switch ($enquete->enq_type) {
			case 1:   // メール
				send_mail_embed($send_mail_id, $sub_seq, '%%FROM_ADDR%%', "$enq_account-$enquete_param@$enq_domain");
				break;
			case 2:   // Web
				send_mail_embed($send_mail_id, $sub_seq, '%ENQUETE_PARAM%', $enquete_param);
				break;
			}
		}

		send_mail_ok($send_mail_id);

		// 事務局に送信完了通知
		report_admin('Ｍｙアンケート', $myenq->send_num, $nrow, $myenq->mail_title, $mail_text);

		// スタッフにMyアンケート開始通知
		get_mail_template('mye_mk2', $subject, $from, $cc, $bcc, $repty_to, $body);
		if ($subject != '') {
			$sql = "SELECT st_mail_addr,st_name1 FROM t_staff WHERE st_staff_id=$staff_id";
			$result = db_exec($sql);
			if (pg_num_rows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$body = str_replace('%STAFF_NAME%', $fetch->st_name1, $body);
				$body = str_replace('%TITLE%', $enquete->title, $body);
				$body = str_replace('%START_DATE%', "$enquete->start_date_y/$enquete->start_date_m/$enquete->start_date_d", $body);
				$body = str_replace('%END_DATE%', "$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d", $body);
				$body = str_replace('%ENQ_TOTAL%', $enquete->get_question_num(), $body);
				send_mail($subject, $fetch->st_mail_addr, $from, $body, $cc, $bcc, $reply_to);
			}
		}

		// アンケートメール文保存
		$sql = sprintf("UPDATE t_enquete SET en_mail_subject=%s,en_mail_contents=%s WHERE en_enquete_id=$enquete_id",
				sql_char($myenq->mail_title),
				sql_char($mail_text));
		db_exec($sql);

		// 実配信数設定
		$sql = "UPDATE t_my_enquete SET me_real_send=$nrow WHERE me_staff_id=$staff_id AND me_my_enq_no=$my_enq_no";
		db_exec($sql);
	}
}

// アンケート終了処理
function end_enquete() {
	$sql = "SELECT en_enquete_id,en_enq_kind FROM t_enquete WHERE (en_status=5 OR en_status=6) AND en_end_date<=CURRENT_TIMESTAMP";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$enquete_id = $fetch->en_enquete_id;

		db_begin_trans();

		switch ($fetch->en_enq_kind) {
		case 2:   // Myアンケート
			end_my_enquete($enquete_id);
			break;
			break;
		}

		$sql = "UPDATE t_enquete SET en_status=7 WHERE en_enquete_id=$enquete_id";
		db_exec($sql);

		batch_log(0, FILE_NAME, "アンケート(ID=$enquete_id)を終了しました。");

		db_commit_trans();
	}
}

// Myアンケート終了処理
function end_my_enquete($enquete_id) {
	// スタッフのメールアドレス取得
	$sql = "SELECT me_enquete_id,st_staff_id,st_mail_addr"
			. " FROM t_my_enquete JOIN t_staff ON st_staff_id=me_staff_id"
			. " WHERE me_enquete_id=$enquete_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$enquete_id = $fetch->me_enquete_id;
		$staff_id = $fetch->st_staff_id;
		$staff_addr = $fetch->st_mail_addr;
	} else
		return;

	// スタッフページのURL取得
	$fetch = get_system_info('sy_url_staff');
	$staff_url  = $fetch->sy_url_staff;
	$url = $fetch->sy_url_staff;

	// 結果ページ取得
	$ary = file("${url}resdet_pv2.php?staff_id=$staff_id&enquete_id=$enquete_id");
	if (!is_array($ary))
		return;
	$html = join("", $ary);

	// 送信者CSV取得
	$ary = file("${url}enq_csv1.php?staff_id=$staff_id&enquete_id=$enquete_id&type=send");
	if (!is_array($ary))
		return;
	$csv_send = join("", $ary);

	// 受信者CSV取得
	$ary = file("${url}enq_csv1.php?staff_id=$staff_id&enquete_id=$enquete_id&type=recv");
	if (!is_array($ary))
		return;
	$csv_recv = join("", $ary);

	// 結果CSV取得
	$ary = file("${url}enq_csv2.php?staff_id=$staff_id&enquete_id=$enquete_id");
	if (!is_array($ary))
		return;
	$csv_result = join("", $ary);

	get_mail_template('mye_res', $subject, $from, $cc, $bcc, $repty_to, $body);
	separate_address($from, $from_addr, $from_name);
	$body = str_replace('%ENQUETE_RESULT%', $html, $body);

	// 送信メールセット
	$send_mail_id = send_mail_data($subject, $from_addr, $repty_to, $body, '', '', $from_name, '1');
	send_mail_file($send_mail_id, 1, "send.csv", $csv_send);
	send_mail_file($send_mail_id, 2, "receive.csv", $csv_recv);
	send_mail_file($send_mail_id, 3, "result.csv", $csv_result);

	// スタッフにメール送信
	if ($staff_addr != '')
		send_mail_list($send_mail_id, $staff_addr);

	send_mail_ok($send_mail_id);
}
?>