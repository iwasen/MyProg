<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� �ֿ����������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class PostReplyAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		$post = $this->getParameters();

		// ��ȯ����������
		$this->setContents();

		// ����ȯ����ɽ��
		$request->setAttribute('waiwai_post', $post);

		switch ($this->getSubmitButton()) {
		case 'confirm_btn':			// ��ǧ

			// ������������
			$this->setBrowseCommon();

			return 'Confirm';
		case 'contribute_btn':	// ����
			if ($user->getAttribute('waiwai_post_flag')) {
				// �������ɻߥե饰�ꥻ�å�
				$user->setAttribute('waiwai_post_flag', false);

				// ȯ������
				$this->postRemark($this->member_id, $this->db->getRemarkId($this->room_id, $post['seq_no']), $post['subject'], $post['content']);
			}

			// ������������
			$this->setBrowseCommon();

			return View::SUCCESS;
		case 'cancel_btn':		// ���
			return $this->getDefaultView();

		}

	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$user = $this->getContext()->getUser();

		// ������������
		$this->setBrowseCommon();

		// ȯ����������
		$this->setContents();

		// �ֿ���������
		$this->setReply();

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

		$request = $this->getContext()->getRequest();
		$validatorManager->registerName('waiwai_id', TRUE, '�ֿ�����ȯ����̤����Ǥ���');
		$validatorManager->registerName('waiwai_subject', TRUE, 'SUBJECT��̤���ϤǤ���');
		$validatorManager->registerName('waiwai_content', TRUE, '��ʸ��̤���ϤǤ���');
	}

	// �����ϥ����å�
	public function validate() {
		$request = $this->getContext()->getRequest();
		$original_content = $request->getParameter('waiwai_original_content');
		$back_flag = $request->getParameter('waiwai_back_flag');

		if ($original_content != '' && !$back_flag) {
			$input_content = $request->getParameter('waiwai_content');

			if (trim($original_content) == trim($input_content)) {
				$request->setError('waiwai_content', '��ʸ��̤�Խ��Ǥ���');
				return false;
			}
		}

		return true;
	}

	// �����ϥ��顼����
	public function handleError() {
		$request = $this->getContext()->getRequest();

		// ������������
		$this->setBrowseCommon();

		// ȯ����������
		$this->setContents();

		// �ֿ���������
		$post = $this->getParameters();
		$request->setAttribute('waiwai_post', $post);

		return View::INPUT;
	}

	// ���ѥ�᡼������
	private function getParameters() {
		$request = $this->getContext()->getRequest();
		$params['seq_no'] = $request->getParameter('waiwai_id');
		$params['subject'] = $request->getParameter('waiwai_subject');
		$params['content'] = $request->getParameter('waiwai_content');
		$params['original_content'] = $request->getParameter('waiwai_original_content');
		$params['back_flag'] = $request->getParameter('waiwai_back_flag');
		return $params;
	}

	// ��ǧ�ڤ��׵�
    public function isSecure() {
        return true;
    }
}
?>