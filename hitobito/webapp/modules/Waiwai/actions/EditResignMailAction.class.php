<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ���ԥ᡼���Խ����������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class EditResignMailAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		switch ($this->getSubmitButton()) {
		case 'waiwai_update_btn':	// ��Ͽ���󹹿�
			// ���ԥ᡼�빹��
			$resign_mail = $this->getParameters();
			$this->db->updateResignMail($this->room_id, $resign_mail);
			break;
		case 'waiwai_preview_btn':	// �ץ�ӥ塼
			break;
		case 'waiwai_default_btn':	// �ǥե���Ȥ��᤹
			// ���ԥǥե���ȥ᡼������
			$mail_template = $this->db->getMailTemplate('room_resign');
			$resign_mail['resign_mail'] = $mail_template['body'];
			$request->setAttribute('waiwai_resign_mail', $resign_mail);

			// ���������ѿ�����
			$request->setAttribute('waiwai_keyword', $this->getkeyword());
			return View::INPUT;
		}

		return View::SUCCESS;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		// ���ԥ᡼������
		$resign_mail = $this->db->getResignMail($this->room_id, $this->member_id);
		$request->setAttribute('waiwai_resign_mail', $resign_mail);

		// ���������ѿ�����
		$keyword = $this->getkeyword();
		$request->setAttribute('waiwai_keyword', $keyword);

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
			$validatorManager->registerName('waiwai_resign_mail', TRUE, '�᡼��ʸ��̤���ϤǤ���');
			break;
		}
	}

	// �����ϥ��顼����
	public function handleError() {
		$request = $this->getContext()->getRequest();

		// ���ԥ᡼������
		$resign_mail = $this->getParameters();
		$request->setAttribute('waiwai_resign_mail', $resign_mail);

		// ���������ѿ�����
		$keyword = $this->getkeyword();
		$request->setAttribute('waiwai_keyword', $keyword);

		return View::INPUT;
	}

	// ���ѥ�᡼������
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['resign_mail'] = $request->getParameter('waiwai_resign_mail');

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