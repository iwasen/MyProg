<?
/******************************************************
' System :きかせて・netバッチ処理
' Content:不達メール処理
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/database.php");
include("inc.php");

// 受信メールクラス
class mail_pop3 {
	var $mbox;

	function open($hostname, $username, $password) {
		$this->mbox = imap_open("{{$hostname}:110/pop3}INBOX", $username, $password);
		if (!$this->mbox) {
			echo "imap_open error";
			return 0;
		}

		$num_msg = imap_num_msg($this->mbox);
		if ($num_msg == 0)
			imap_close($this->mbox);

		return $num_msg;
	}

	function get_header($msg_no) {
		return imap_header($this->mbox, $msg_no);
	}

	function get_all_headers($msg_no) {
		return imap_fetchheader($this->mbox, $msg_no, 0);
	}

	function get_body($msg_no) {
		return mb_convert_encoding(imap_body($this->mbox, $msg_no), 'EUC-JP', 'auto');
	}

	function delete($msg_no) {
		imap_delete($this->mbox, $msg_no);
	}

	function close($del = false) {
		imap_close($this->mbox, $del ? CL_EXPUNGE : 0);
	}
}

// 二重起動チェック
if (!dup_check('lock_mail_recv_sm'))
	exit;

// 最大実行時間（３分）
set_time_limit(180);

recv_err_mail();
exit;

// 不達メール受信処理
function recv_err_mail() {
	$fetch = get_system_info("sy_pop_server,sy_envelope_addr,sy_envelope_pwd");

	$mail = new mail_pop3();
	$num_msg = $mail->open($fetch->sy_pop_server, $fetch->sy_envelope_addr, $fetch->sy_envelope_pwd);

	if (!$num_msg)
		return 0;

	$count = 0;
	$num_msg = min($num_msg, 500);
	for ($i = 1; $i <= $num_msg; $i++) {
		// メール本文取得
		$body = $mail->get_body($i);

		$send_mail_id = '';
		$sub_seq = '';
		$warning = false;

		// X-SMAgent-Id: を探す
		$lines = explode("\n", $body);
		if (is_array($lines)) {

			foreach ($lines as $line) {
				if ($send_mail_id == '' && ereg('^X-SMAgent-Id: ([0-9]+)-([0-9]+)', $line, $regs)) {
					$send_mail_id = $regs[1];
					$sub_seq = $regs[2];
				} elseif (strpos($line, '(warning-timeout)') !== false)
					$warning = true;
			}
		}

		// あったら不達メール処理を行う
		if ($send_mail_id != '' && $sub_seq != '' && !$warning)
			error_mail($send_mail_id, $sub_seq);

		// メールサーバ上のメールを削除
		$mail->delete($i);
	}
	$mail->close(true);

	return $count;
}

function error_mail($send_mail_id, $sub_seq) {
	$sql = "SELECT * FROM t_mail_list WHERE ml_send_mail_id=$send_mail_id AND ml_sub_seq=$sub_seq AND ml_status=2";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$sql = "UPDATE t_mail_list SET ml_status=3 WHERE ml_send_mail_id=$send_mail_id AND ml_sub_seq=$sub_seq";
		db_exec($sql);

		$sql = "UPDATE t_mail_count SET mc_count2=mc_count2-1,mc_count3=mc_count3+1 WHERE mc_send_mail_id=$send_mail_id";
		db_exec($sql);
	}
}
?>