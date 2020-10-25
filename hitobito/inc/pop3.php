<?php
/**
 * �ҤȤӤȎ�net ���̥ե�����
 *
 * �᡼���������
 *
 *
 * @package
 * @author
 * @version
 */

class pop3_class {
	protected $mbox;

	// ���᡼��ܥå��������ץ�
	public function open($hostname, $username, $password) {
		$this->mbox = imap_open("{" . $hostname . ":110/pop3}INBOX", $username, $password);
		if (!$this->mbox)
			return 0;

		$num_msg = imap_num_msg($this->mbox);
		if ($num_msg == 0)
			imap_close($this->mbox);

		return $num_msg;
	}

	// ���إå��������
	public function get_header($msg_no) {
		return imap_header($this->mbox, $msg_no);
	}

	// ���إå��ƥ����ȼ���
	public function get_all_headers($msg_no) {
		return imap_fetchheader($this->mbox, $msg_no, 0);
	}

	// ����ʸ����
	public function get_body($msg_no) {
		return mb_convert_encoding(imap_body($this->mbox, $msg_no), 'EUC-JP', 'auto');
	}

	// ����ʸ�Υƥ����ȼ���
	public function get_text_body($msg_no) {
		$st = imap_fetchstructure($this->mbox, $msg_no);

		$body = $this->get_part_body($msg_no, $st, 0, 'PLAIN');
		if ($body == '') {
			$body = strip_tags($this->get_part_body($msg_no, $st, 0, 'HTML'));
			if ($body == '')
				return $this->get_body($msg_no);
		}

		return $body;
	}

	// �����ꤷ���ѡ��Ȥ����
	protected function get_part_body($msg_no, &$st, $type, $subtype, $part = '') {
		if ($st->type == 1) {
			for ($i = 0; $i < count($st->parts); $i++) {
				$subpart = ($part != '' ? "$part." : '') . ($i + 1);
				$body = $this->get_part_body($msg_no, $st->parts[$i], $type, $subtype, $subpart);
				if ($body != '')
					return $body;
			}
		} else {
			if ($st->type == $type && $st->subtype == $subtype) {
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
		}
		return '';
	}

	//�� ź�եե���������
	public function get_attachment($msg_no) {
		$index = 0;
		$this->find_attachment($msg_no, imap_fetchstructure($this->mbox, $msg_no), $attachment, $index, '');

		return $attachment;
	}

	// ��ATTACHMENT�ѡ��Ȥμ���
	protected function find_attachment($msg_no, &$st, &$attachment, &$index, $part) {
		if ($st->type == 1) {
			for ($i = 0; $i < count($st->parts); $i++) {
				$subpart = ($part != '' ? "$part." : '') . ($i + 1);
				$this->find_attachment($msg_no, $st->parts[$i], $attachment, $index, $subpart);
			}
		} else {
			if ($st->disposition == 'ATTACHMENT') {
				if($st->ifdparameters){
					$dpara = &$st->dparameters;
					for ($v = 0; $v < count($dpara); $v++) {
						if (strcasecmp("filename", $dpara[$v]->attribute) == 0)
							$fname = $dpara[$v]->value;
					}
				} elseif($st->ifparameters){
					$para = $st->parameters;
					for ($v = 0; $v < count($para); $v++) {
						if (strcasecmp("name", $para[$v]->attribute) == 0)
							$fname = $para[$v]->value;
					}
				}

				if ($fname != '') {
					$attachment[$index]['filename'] = $fname;

					$body = imap_fetchbody($this->mbox, $msg_no, $part == '' ? '1' : $part);
					switch ($st->encoding) {
					case 3:
						$body = imap_base64($body);
						break;
					case 4:
						$body = imap_qprint($body);
						break;
					}
					$attachment[$index]['body'] = $body;

					$index++;
				}
			}
		}
	}

	// ���᡼����
	public function delete($msg_no) {
		imap_delete($this->mbox, $msg_no);
	}

	// ���᡼��ܥå���������
	public function close($del = false) {
		imap_close($this->mbox, $del ? CL_EXPUNGE : 0);
	}
}
?>