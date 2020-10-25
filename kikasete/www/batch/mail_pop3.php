<?
/******************************************************
' System :きかせて・netバッチ処理
' Content:POP3メール受信クラス
'******************************************************/

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
?>