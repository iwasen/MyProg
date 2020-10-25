<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ��������Ͽ�᡼���Խ����������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class EditGuestMailAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		switch ($this->getSubmitButton()) {
		case 'waiwai_update_btn':	// ��Ͽ���󹹿�
			// ��������Ͽ�᡼�빹��
			$guest_mail = $this->getParameters();
			$this->db->updateGuestMail($this->room_id, $guest_mail);
			break;
		case 'waiwai_preview_btn':	// �ץ�ӥ塼
			break;
		case 'waiwai_default_btn':	// �ǥե���Ȥ��᤹
			// ��������Ͽ�ǥե���ȥ᡼������
			$mail_template = $this->db->getMailTemplate('room_guest');
			$guest_mail['guest_mail'] = $mail_template['body'];
			$request->setAttribute('waiwai_guest_mail', $guest_mail);

			// ���������ѿ�����
			$request->setAttribute('waiwai_keyword', $this->getkeyword());
			return View::INPUT;
		}

		return View::SUCCESS;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		// ��������Ͽ�᡼������
		$request->setAttribute('waiwai_guest_mail', $this->db->getGuestMail($this->room_id, $this->member_id));

		// ���������ѿ�����
		$request->setAttribute('waiwai_keyword', $this->getkeyword());

		return View::INPUT;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::POST;
	}

	// ��Validator��Ͽ
	public function registerValidators($validatorManager) {
		switch ($this->getSubmitButton()) {
		case 'waiwai_update_btn':	// ��Ͽ���󹹿�
		case 'waiwai_preview_btn':	// �ץ�ӥ塼
			$validatorManager->registerName('waiwai_guest_mail', TRUE, '�᡼��ʸ��̤���ϤǤ���');
			break;
		}
	}

	// �����ϥ��顼����
	public function handleError() {
		$request = $this->getContext()->getRequest();

		// ��������Ͽ�᡼������
		$request->setAttribute('waiwai_guest_mail', $this->getParameters());

		// ���������ѿ�����
		$request->setAttribute('waiwai_keyword', $this->getkeyword());

		return View::INPUT;
	}

	// ���ѥ�᡼������
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['guest_mail'] = $request->getParameter('waiwai_guest_mail');

		return $params;
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