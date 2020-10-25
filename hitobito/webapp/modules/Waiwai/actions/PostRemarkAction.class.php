<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ������ƥ��������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class PostRemarkAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		$post = $this->getParameters();

		// ������������
		$this->setBrowseCommon();

		// ����ȯ����ɽ��
		$request->setAttribute('waiwai_post', $post);

		switch ($this->getSubmitButton()) {
		case 'contribute_btn':
			// ȯ������
			$this->postRemark($this->member_id, 0, $post['subject'], $post['content']);
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

		return View::INPUT;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::POST;
	}

	// ��Validator��Ͽ
	public function registerValidators($validatorManager) {
		$validatorManager->registerName('waiwai_subject', TRUE, 'SUBJECT��̤���ϤǤ���');
		$validatorManager->registerName('waiwai_content', TRUE, '��ʸ��̤���ϤǤ���');
	}

	// �����ϥ��顼����
	public function handleError() {
		$request = $this->getContext()->getRequest();

		// ������������
		$this->setBrowseCommon();

		// ȯ����������
		$post = $this->getParameters();
		$request->setAttribute('waiwai_post', $post);

		return View::INPUT;
	}

	// ���ѥ�᡼������
	private function getParameters() {
		$request = $this->getContext()->getRequest();

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