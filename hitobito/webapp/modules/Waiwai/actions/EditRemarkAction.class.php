<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ȯ���Խ����������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class EditRemarkAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();
		$contents = $this->getParameters();

		// ������������
		$this->setBrowseCommon();

		// �Խ���������
		$request->setAttribute('waiwai_contents', $contents);

		switch ($this->getSubmitButton()) {
		case 'contribute_btn':	// ����

			// ȯ���Խ�����
			$this->db->updateRemark($this->db->getRemarkId($this->room_id, $contents['seq_no']), $contents['subject'], $contents['content']);

			return View::SUCCESS;

		case 'confirm_btn':		// ��ǧ
			return 'Confirm';
		case 'cancel_btn':		// ���
			return View::INPUT;
		}
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		// ������������
		$this->setBrowseCommon();

		// ȯ����������
		$this->setContents();

		return View::INPUT;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::POST;
	}

	// ��Validator��Ͽ
	public function registerValidators($validatorManager) {
		$validatorManager->registerName('waiwai_id', TRUE, '�Խ�����ȯ����̤����Ǥ���');
		$validatorManager->registerName('waiwai_subject', TRUE, 'SUBJECT��̤���ϤǤ���');
		$validatorManager->registerName('waiwai_content', TRUE, '��ʸ��̤���ϤǤ���');
	}

	// �����ϥ��顼����
	public function handleError() {
		$request = $this->getContext()->getRequest();

		// ������������
		$this->setBrowseCommon();

		// �Խ���������
		$contents = $this->getParameters();
		$request->setAttribute('waiwai_contents', $contents);

		return View::INPUT;
	}

	// ���ѥ�᡼������
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['seq_no'] = $request->getParameter('waiwai_id');
		$params['subject'] = $request->getParameter('waiwai_subject');
		$params['content'] = $request->getParameter('waiwai_content');

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