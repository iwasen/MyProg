<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ���̥桼�����������ƥ��������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class PostPublicAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		$seq_no = $request->getParameter('waiwai_seq_no');
		$waiwai_public = $this->db->getPublicContents($this->room_id, $seq_no);

		switch ($this->getSubmitButton()) {
		case 'waiwai_public_post_btn':		// ��Ƥ���
			$waiwai_public = $this->db->getPublicContents($this->room_id, $seq_no);
			$this->postRemark(0, 0, $waiwai_public['title'], $waiwai_public['content'], $waiwai_public['nickname']);
			$this->db->updatePublicStatus($this->room_id, $seq_no, 2);
			$waiwai_public['msg'] = '��Ƥ��ޤ�����';
			break;
		case 'waiwai_public_refuse_btn':	// ���Ǥꤹ��
			$this->db->updatePublicStatus($this->room_id, $seq_no, 4);
			$this->sendRefuseMail($this->room_id, $seq_no);
			$waiwai_public['msg'] = '���Ǥꤷ�ޤ�����';
			break;
		case 'waiwai_public_noappr_btn':	// ��ǧ
			$this->db->updatePublicStatus($this->room_id, $seq_no, 3);
			$waiwai_public['msg'] = '��ǧ�ˤ��ޤ�����';
			break;
		case 'waiwai_public_delete_btn':	// ���
			$this->db->deletePublic($this->room_id, $seq_no);
			$waiwai_public['msg'] = '������ޤ�����';
			break;
		}

		$request->setAttribute('waiwai_public', $waiwai_public);

		return View::SUCCESS;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		$seq_no = $request->getParameter('waiwai_seq_no');
		if ($seq_no) {
			$waiwai_public = $this->db->getPublicContents($this->room_id, $seq_no);
			$request->setAttribute('waiwai_public', $waiwai_public);
		}

		return View::INPUT;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::POST;
	}

	// ��Validator��Ͽ
	public function registerValidators($validatorManager) {
		$validatorManager->registerName('waiwai_seq_no', TRUE, '����ֹ椬���ꤵ��Ƥ��ޤ���');
	}

	// �����ϥ��顼����
	public function handleError() {
		return $this->getDefaultView();
	}

	// �����Ǥ�᡼������
	private function sendRefuseMail($room_id, $seq_no) {
		$public_contents = $this->db->getPublicContents($room_id, $seq_no);

		// �᡼��ƥ�ץ졼�ȼ���
		$mail = $this->db->getMailTemplate('room_post_ng');

		// ���������ѿ�����
		$var_ary = $this->getRoomVars($room_id);
		$mail['body'] = $this->replaceRoomVars($var_ary, $mail['body']);
		$mail['body'] = str_replace('%MAIL_ADDR%', $public_contents['mail_addr'], $mail['body']);
		$mail['body'] = str_replace('%NICKNAME%', $public_contents['nickname'], $mail['body']);
		$mail['body'] = str_replace('%TITE%', $public_contents['title'], $mail['body']);
		$mail['body'] = str_replace('%CONTENT%', $public_contents['content'], $mail['body']);

		$send_mail_id = $this->db->setSendMailData($mail['subject'], $mail['from'], $mail['reply_to'], $mail['body']);
		$this->db->setSendMailList($send_mail_id, $public_contents['mail_addr']);
		$this->db->setSendMailOk($send_mail_id);
	}

	// ��ǧ�ڤ��׵�
    public function isSecure() {
        return true;
    }

	// ���ʥ��Խ��ڡ���
	protected function isNaviEditPage() {
		return true;
	}
}
?>