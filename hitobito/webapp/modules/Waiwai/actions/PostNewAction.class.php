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

class PostNewAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		$post = $this->getParameters();

		// ����ȯ����ɽ��
		$request->setAttribute('waiwai_post', $post);


		switch ($this->getSubmitButton()) {
		case 'contribute_btn':
			if ($user->getAttribute('waiwai_post_flag')) {
				// �������ɻߥե饰�ꥻ�å�
				$user->setAttribute('waiwai_post_flag', false);

				// ȯ������
				$this->postRemark($this->member_id, 0, $post['subject'], $post['content']);
			}

			// ������������
			$this->setBrowseCommon();

			return View::SUCCESS;
		case 'confirm_btn':		// ��ǧ

			// ������������
			$this->setBrowseCommon();

			return 'Confirm';
		case 'cancel_btn':		// ���
			return $this->getDefaultView();
		}

	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$user = $this->getContext()->getUser();

		// ������������
		$this->setBrowseCommon();

		// �������ɻߥե饰���å�
		$user->setAttribute('waiwai_post_flag', true);

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
}
?>