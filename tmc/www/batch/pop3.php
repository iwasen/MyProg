<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」バッチ処理
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

	function get_text_body($msg_no) {
		$st = imap_fetchstructure($this->mbox, $msg_no);

		$body = $this->get_part_body($msg_no, $st, 0, 'PLAIN');
		if ($body == '') {
			$body = strip_tags($this->get_part_body($msg_no, $st, 0, 'HTML'));
			if ($body == '')
				return $this->get_body($msg_no);
		}

		return $body;
	}

	function get_part_body($msg_no, &$st, $type, $subtype, $part = '') {
		if ($st->type == $type && $st->subtype == $subtype && !$st->ifdisposition) {
			$body = imap_fetchbody($this->mbox, $msg_no, $part == '' ? '1' : $part);
			switch ($st->encoding) {
			case 3:
				$body = imap_base64($body);
				break;
			case 4:
				$body = imap_qprint($body);
				break;
			}
			return mb_convert_encoding($body, 'EUC-JP', 'auto');
		}

		for ($i = 0; $i < count($st->parts); $i++) {
			$subpart = ($part != '' ? "$part." : '') . ($i + 1);
			$body = $this->get_part_body($msg_no, $st->parts[$i], $type, $subtype, $subpart);
			if ($body != '')
				return $body;
		}

		return '';
	}

	function delete($msg_no) {
		imap_delete($this->mbox, $msg_no);
	}

	function close($del = false) {
		imap_close($this->mbox, $del ? CL_EXPUNGE : 0);
	}
}
?>