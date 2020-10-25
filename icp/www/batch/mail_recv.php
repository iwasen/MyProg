<?
/******************************************************
' System :ICPバッチ処理
' Content:メール受信処理
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/batch.php");
include("$inc/mail.php");
include("$inc/pop3.php");
include("$inc/auth.php");
include("$inc/variable.php");
include("$inc/room.php");
include("$inc/format.php");
include("$inc/decode.php");
include("$inc/status.php");
include("$inc/define.php");
include("$inc/remark.php");

// Toアドレスの種別
define('TO_NONE', 0);
define('TO_ROOM', 1);
define('TO_MEMBER', 2);
define('TO_MC', 3);
define('TO_HELP', 4);

// 二重起動チェック
if (!dup_check('lock_mail_recv'))
	exit;

// 最大実行時間（１０分）
set_time_limit(600);

// メールサーバ名取得
$server = get_system_info('server_pop3');

// メール受信アカウント／パスワード取得
$account = explode('/', get_system_info('mail_account'), 2);

mail_recv($server, $account[0], $account[1]);
exit;

// メール受信処理
function mail_recv($server, $account, $password) {
	$mail = new mail_pop3();
	$num_msg = $mail->open($server, $account, $password);
	if (!$num_msg)
		return 0;

	db_begin_trans();

	$ok_count = 0;
	$err_count = 0;

	for ($i = 1; $i <= $num_msg; $i++) {
		$mail_header = $mail->get_all_headers($i);
		$header = $mail->get_header($i);
		$body = trim($mail->get_text_body($i));
		$attachment = $mail->get_attachment($i);

		if ($body == '')
			$body = '（本文なし）';
/*
		echo '<xmp>';var_dump($header);echo '</xmp>------------------';
		$st = imap_fetchstructure($mail->mbox, $i);
		echo '<xmp>';var_dump($st);echo '</xmp>------------------';
*/
		// ToアドレスからプロジェクトID，ジョブID，メンバIDを取得
		$to_type = get_id($header, $pjt_id, $job_id, $to_member_id);

		// FromアドレスからメンバーID取得
		$member_id = get_member_id($pjt_id, $header);

		// Subjectの取得
		$subject = mb_decode_mimeheader($header->subject);
		if ($subject == '')
			$subject = '（件名なし）';

		// メッセージID取得
		$message_id = make_message_id();

		// 参照元メッセージID取得
		$reference_id = get_reference_id($header);

		// 発言受信処理
		$err_type = check_remark($to_type, $job_id, $member_id, $attachment);
		if ($err_type != 0) {
			send_err_mail($header, $body, $err_type, $pjt_id, $job_id, $member_id);
			$err_count++;
		} else {
			// 親発言IDの取得
			$sql = "SELECT rm_remark_id FROM l_remark WHERE rm_job_id=$job_id AND rm_status=1 AND rm_message_id=" . sql_char($reference_id);
			$parent_remark_id = db_fetch1($sql, 0);

			// 発言種別判定
			switch ($to_type) {
			case TO_ROOM:
				$remark_type = RT_REMARK;
				break;
			case TO_MEMBER:
				$remark_type = RT_REMARK;
				if ($parent_remark_id != 0) {
					$sql = "SELECT rm_remark_type FROM l_remark WHERE rm_remark_id=$parent_remark_id AND rm_status=1";
					$parent_remark_type = db_fetch1($sql);
					if ($parent_remark_type == RT_HELP || $parent_remark_type == RT_ANSWER)
						$remark_type = RT_ANSWER;
				}
				break;
			case TO_MC:
				$remark_type = RT_REMARK;
				break;
			case TO_HELP:
				$remark_type = RT_HELP;
				break;
			}

			// 発言処理
			set_remark($remark_type, $job_id, $member_id, $to_member_id, $message_id, $parent_remark_id, $subject, $body, RM_MAIL, $mail_header, $attachment);
			$ok_count++;
		}

		$mail->delete($i);
	}

	// エラーチェック
	$err_msg = db_errormessage();
	if ($err_msg) {
		db_rollback();
		$mail->close(false);

		// バッチログ書き込み（エラー）
		$desc = "メール受信処理でエラーが発生しました。($err_msg)";
		batch_log(9, __FILE__, $desc);
	} else {
		// バッチログ書き込み（正常）
		$desc = "メールを受信しました。（正常：{$ok_count}件，エラー：{$err_count}件）";
		batch_log(0, __FILE__, $desc);

		db_commit_trans();
		$mail->close(true);
	}
}

// ToアドレスからプロジェクトID，ジョブID，メンバIDを取得
function get_id(&$header, &$pjt_id, &$job_id, &$member_id) {
	$ary = explode('-', $header->to[0]->mailbox);

	$pjt_id = '';
	$job_id = '';
	$member_id = '';
	$to_type = TO_NONE;

	// プロジェクトID取得
	if ($ary[1] != '') {
		$sql = "SELECT pj_pjt_id FROM t_pjt WHERE pj_pjt_cd=" . sql_char($ary[1]);
		$pjt_id = db_fetch1($sql);

		// ジョブID取得
		if ($pjt_id != '' && $ary[2] != '') {
			$sql = "SELECT jb_job_id FROM t_job WHERE jb_pjt_id=" . sql_number($pjt_id) . " AND jb_job_cd=" . sql_char($ary[2]);
			$job_id = db_fetch1($sql);

			// メンバーID取得
			if ($job_id != '') {
				switch ($ary[3]) {
				case 'mc':
					$to_type = TO_MC;
					break;
				case 'help':
					$to_type = TO_HELP;
					break;
				case '':
					$to_type = TO_ROOM;
					break;
				default:
					if (strlen($ary[3]) == 6) {
						$member_cd = $ary[3];
						$sql = "SELECT jm_member_id"
								. " FROM t_job_member"
								. " WHERE jm_job_id=" . sql_number($job_id) . " AND jm_member_cd=" . sql_char($member_cd) . " AND jm_status=1";
						$member_id = db_fetch1($sql);
						if ($member_id != '')
							$to_type = TO_MEMBER;
					}
					break;
				}
			}
		}
	}

	return $to_type;
}

// FromアドレスからメンバーID取得
function get_member_id($pjt_id, &$header) {
	$mail_addr = "{$header->from[0]->mailbox}@{$header->from[0]->host}";

	$sql = "SELECT pm_member_id FROM t_pjt_member WHERE pm_pjt_id=" . sql_number($pjt_id) . " AND pm_mail_addr=" . sql_char(strtolower($mail_addr));
	return db_fetch1($sql);
}

// 発言チェック
function check_remark($to_type, $job_id, $member_id, &$attachment) {
	global $g_job_status;

	// 宛先種別がない場合はタイプ１エラー
	if ($to_type == TO_NONE)
		return 1;

	// メンバーの存在チェック
	if (!$member_id)
		return 2;

	// ジョブの状態チェック
	$sql = "SELECT jb_status,jb_attachment_file FROM t_job WHERE jb_job_id=" . sql_number($job_id);
	$result = db_exec($sql);
	$fetch = pg_fetch_object($result, 0);
	$job_status = $fetch->jb_status;
	if ($job_status == $g_job_status['休止中'] || $job_status == $g_job_status['猶予中'])
		return 7;
	elseif ($job_status != $g_job_status['実施中'])
		return 1;

	// メンバーの参加チェック
	$sql = "SELECT jm_participant_auth FROM t_job_member WHERE jm_job_id=" . sql_number($job_id) . " AND jm_member_id=" . sql_number($member_id) . " AND jm_status=1";
	$participant_auth = db_fetch1($sql);
	if ($participant_auth == '')
		return 2;

	// 送信権限チェック
	if (($to_type == TO_ROOM || $to_type == TO_MEMBER || $to_type == TO_MC) &&
			(!check_participant_auth('メール送信', $participant_auth) || !check_participant_auth('発言ON/OFF', $participant_auth)))
		return 3;

	// 司会者権限チェック
	if ($to_type == TO_MEMBER && !check_participant_auth('司会者', $participant_auth))
		return 4;

	// 会議室タイプチェック
	$sql = "SELECT jb_room_type FROM t_job WHERE jb_job_id=" . sql_number($job_id);
	$room_type = db_fetch1($sql);
	if (($room_type == ROOM_PIR && $to_type == TO_ROOM) || ($room_type != ROOM_PIR && ($to_type == TO_MEMBER || $to_type == TO_MC)))
		return 5;

	// 添付ファイル存在チェック
	if ($fetch->jb_attachment_file == DBFALSE && $attachment)
		return 6;

	return 0;
}

// エラーメール送信
function send_err_mail(&$header, &$body_org, $err_type, $pjt_id, $job_id, $member_id) {
	if (!$header->from)
		return;

	// 送信メールテンプレート取得
	get_mail_template("err_type$err_type", $subject, $from, $cc, $bcc, $repty_to, $body);

	$bcc_ary = array();

	// プロジェクト所有オペレーターのメールアドレス取得
	$sql = "SELECT op_mail_addr"
			. " FROM t_pjt"
			. " JOIN t_operator ON op_operator_id=pj_operator_id"
			. " WHERE pj_pjt_id=" . sql_number($pjt_id);
	$bcc_ary[] = db_fetch1($sql);

	// 司会者をBccで追加
	if ($err_type == 3 || $err_type == 4 || $err_type == 5 || $err_type == 6 || $err_type == 7) {
		$sql = "SELECT pm_mail_addr"
				. " FROM t_job_member"
				. " JOIN t_pjt_member ON pm_member_id=jm_member_id"
				. " WHERE jm_job_id=" . sql_number($job_id) . " AND jm_status=1 AND " . cond_participant_auth('司会者');
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$bcc_ary[] = $fetch->pm_mail_addr;
		}
	}

	// Bccをメールヘッダに追加
	$bcc = join(',', array_unique($bcc_ary));
	if ($bcc != '')
		$mail_header_ary[] = "Bcc: $bcc";

	// メールヘッダ編集
	$message_id = make_message_id();
	$reference_id = get_message_id($header);
	$mail_header_ary[] = "Message-ID: <$message_id>";
	$mail_header_ary[] = "In-Reply-To: <$reference_id>";
	$mail_header_ary[] = "References: <$reference_id>";
	$mail_header = join("\n", $mail_header_ary);

	// 問い合わせアドレス置換
	$from = replace_all_var($job_id, $member_id, $from);

	// 本文引用
	$body = str_replace('%QUATE%', make_quote($body_org), $body);

	// 差し込み変数処理
	$body = replace_all_var($job_id, $member_id, $body);

	// 送信メールデータセット
	$send_mail_id = send_mail_data($subject, $from, $repty_to, $body, $mail_header);

	// 配信先リスト設定
	send_mail_list($send_mail_id, "{$header->from[0]->mailbox}@{$header->from[0]->host}");

	send_mail_ok($send_mail_id);
}

// Message-ID取得
function get_message_id(&$header) {
	return get_id_value($header->message_id);
}

// 参照元Message-ID取得
function get_reference_id(&$header) {
	$id = get_id_value($header->in_reply_to);
	if ($id != '')
		return $id;

	return get_id_value($header->references);
}

// Message-IDの値を取得
function get_id_value($id) {
	$id_ary = split("[ \t\n\r]", $id);
	if (is_array($id_ary) && count($id_ary) >= 2)
		$id = array_pop($id_ary);

	if (ereg('<([^\>]*)\>', $id, $regs))
		return $regs[1];
	else
		return $id;
}
?>