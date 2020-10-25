<?
/******************************************************
' System :きかせて・netバッチ処理
' Content:メールニュース送信処理
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/batch_log.php");
include("mail_report.php");

define('FILE_NAME', __FILE__);

// 二重起動チェック
if (!dup_check('lock_mail_news'))
	exit;

// 最大実行時間（３分）
set_time_limit(180);

// メールニュース送信処理
// 送信可能ニュース取得
$sql = "SELECT ns_news_id,ns_target,ns_subject,ns_body,ns_send_date,ns_send_assign,ns_bunkatsu_flag" .
		" FROM t_mail_news" .
		" WHERE ns_status=0 AND (ns_send_date IS NULL OR ns_send_date<CURRENT_TIMESTAMP)";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$news_id = $fetch->ns_news_id;
	$send_num = 0;
	$mail_header = "X-Kikasete-News: $news_id";

	// 送信元アドレス取得
	switch ($fetch->ns_target) {
	case 1:		// モニター
		$fetch2 = get_system_info('sy_news1_from,sy_news1_name');
		$from = $fetch2->sy_news1_from;
		$personal = $fetch2->sy_news1_name;
		$sql2 = "SELECT DISTINCT %SEND_MAIL_ID%,mn_mail_addr AS mail_addr FROM t_monitor WHERE mn_status=0";
		if ($fetch->ns_send_assign != 0)
			$sql2 = "SELECT * FROM ($sql2) s1 ORDER BY random() LIMIT {$fetch->ns_send_assign}";
		$mail_kind = 'モニターメールニュース';
		break;
	default:		// マーケター
		$fetch2 = get_system_info('sy_news2_from,sy_news2_name');
		$from = $fetch2->sy_news2_from;
		$personal = $fetch2->sy_news2_name;
		$sql2 = "SELECT DISTINCT %SEND_MAIL_ID%,mr_mail_addr AS mail_addr FROM t_marketer WHERE mr_status=0";
		switch ($fetch->ns_target) {
		case 3:		// Myページ登録ユーザ
			$sql2 .= " AND mr_type IN (3,4)";
			break;
		case 4:		// メルマガ登録ユーザ
			$sql2 .= " AND mr_type=1";
			break;
		}
		$mail_kind = 'マーケターメールニュース';
		break;
	}

	db_begin_trans();

	if ($fetch->ns_bunkatsu_flag == DBFALSE) {
		// メール送信テーブルにセット
		$send_mail_id = send_mail_data($fetch->ns_subject, $from, '', $fetch->ns_body, $mail_header, $fetch->ns_send_date, $personal);

		// 送信先リスト書き込み
		$sql2 = str_replace('%SEND_MAIL_ID%', $send_mail_id, "INSERT INTO t_send_mail_list (sl_send_mail_id,sl_to) $sql2");
		$result2 = db_exec($sql2);
		$send_num = pg_cmdtuples($result2);

		// ステータスを送信可
		send_mail_ok($send_mail_id);

		// ニュースを配信済みにセット
		$sql2 = "UPDATE t_mail_news SET ns_status=1,ns_send_date=CURRENT_TIMESTAMP,ns_send_num=$send_num WHERE ns_news_id=$news_id";
		db_exec($sql2);
	} else {
		$sql2 = str_replace('%SEND_MAIL_ID%', '0', $sql2);
		$result2 = db_exec($sql2);
		$nrow2 = pg_numrows($result2);
		$send_num = 0;

		$sql3 = "SELECT mns_seq_no,mns_send_num FROM t_mail_news_send WHERE mns_news_id=$news_id ORDER BY mns_seq_no";
		$result3 = db_exec($sql3);
		$nrow3 = pg_numrows($result3);
		for ($j = 0; $j < $nrow3; $j++) {
			$fetch3 = pg_fetch_object($result3, $j);

			// メール送信テーブルにセット
			$send_mail_id = send_mail_data($fetch->ns_subject, $from, '', $fetch->ns_body, $mail_header, '', $personal);

			for ($k = 0; ($k < $fetch3->mns_send_num || $j == $nrow3 - 1) && $send_num < $nrow2; $k++, $send_num++) {
				$fetch2 = pg_fetch_object($result2, $send_num);
				send_mail_list($send_mail_id, $fetch2->mail_addr);
			}

			// ニュースを配信中にセット
			$sql2 = "UPDATE t_mail_news SET ns_status=2,ns_send_date=CURRENT_TIMESTAMP,ns_send_num=$send_num WHERE ns_news_id=$news_id";
			db_exec($sql2);

			// 分割送信テーブルにメール送信ID設定
			$sql2 = "UPDATE t_mail_news_send SET mns_send_mail_id=$send_mail_id WHERE mns_news_id=$news_id AND mns_seq_no={$fetch3->mns_seq_no}";
			db_exec($sql2);
		}
	}

	// 事務局に送信完了通知
	report_admin($mail_kind, $send_num, $send_num, $fetch->ns_subject, $fetch->ns_body);

	// エラーチェック
	$err_msg = db_errormessage();
	if ($err_msg) {
		db_rollback();

		// バッチログ書き込み（エラー）
		$desc = "メールニュース送信処理でエラーが発生しました。($err_msg)";
		batch_log(9, FILE_NAME, $desc);
		break;
	} else {
		// バッチログ書き込み（正常）
		if ($send_num > 0) {
			$desc = "メールニュース(ID=$news_id)を{$send_num}通送信しました。";
			batch_log(0, FILE_NAME, $desc);
		}
	}

	db_commit_trans();
}

// 分割送信処理
db_begin_trans();
$sql = "SELECT ns_news_id FROM t_mail_news WHERE ns_status=2";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$news_id = $fetch->ns_news_id;

	$sql2 = "SELECT mns_seq_no,mns_send_mail_id FROM t_mail_news_send WHERE mns_news_id=$news_id AND mns_status=0 AND mns_send_date<CURRENT_TIMESTAMP ORDER BY mns_seq_no";
	$result2 = db_exec($sql2);
	$nrow2 = pg_numrows($result2);
	for ($j = 0; $j < $nrow2; $j++) {
		$fetch2 = pg_fetch_object($result2, $j);

		// ステータスを送信可
		send_mail_ok($fetch2->mns_send_mail_id);

		// 分割送信済み
		$sql2 = "UPDATE t_mail_news_send SET mns_status=1 WHERE mns_news_id=$news_id AND mns_seq_no={$fetch2->mns_seq_no}";
		db_exec($sql2);
	}

	// 分割送信終了チェック
	$sql2 = "SELECT COUNT(*) FROM t_mail_news_send WHERE mns_news_id=$news_id AND mns_status=0";
	if (db_fetch1($sql2) == 0) {
		// ニュースを配信済みにセット
		$sql2 = "UPDATE t_mail_news SET ns_status=1 WHERE ns_news_id=$news_id";
		db_exec($sql2);
	}
}
db_commit_trans();
?>