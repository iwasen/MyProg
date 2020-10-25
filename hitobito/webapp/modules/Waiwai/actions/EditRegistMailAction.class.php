<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ����ԥ᡼���Խ����������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class EditRegistMailAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		switch ($this->getSubmitButton()) {
		case 'waiwai_update_btn':	// ��Ͽ���󹹿�
			// ����ԥ᡼�빹��
			$regist_mail = $this->getParameters();
			$this->db->updateRegistMail($this->room_id, $regist_mail);
			break;
		case 'waiwai_preview_btn':	// �ץ�ӥ塼
			break;
		case 'waiwai_default_btn':	// �ǥե���Ȥ��᤹
			// ����ԥǥե���ȥ᡼������
			$mail_template = $this->db->getMailTemplate('room_regist');
			$regist_mail['regist_mail'] = $mail_template['body'];
			$request->setAttribute('waiwai_regist_mail', $regist_mail);

			// ���������ѿ�����
			$request->setAttribute('waiwai_keyword', $this->getkeyword());
			return View::INPUT;
		}

		return View::SUCCESS;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		// ����ԥ᡼������
		$request->setAttribute('waiwai_regist_mail', $this->db->getRegistMail($this->room_id, $this->member_id));

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
			$validatorManager->registerName('waiwai_regist_mail', TRUE, '�᡼��ʸ��̤���ϤǤ���');
			break;
		}
	}

	// �����ϥ��顼����
	public function handleError() {
		$request = $this->getContext()->getRequest();

		// ����ԥ᡼������
		$request->setAttribute('waiwai_regist_mail', $this->getParameters());

		// ���������ѿ�����
		$request->setAttribute('waiwai_keyword', $this->getkeyword());

		return View::INPUT;
	}

	// ���ѥ�᡼������
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['regist_mail'] = $request->getParameter('waiwai_regist_mail');

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