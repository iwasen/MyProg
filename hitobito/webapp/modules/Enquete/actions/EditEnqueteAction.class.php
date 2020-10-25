<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �ʥӥ��󥱡��� ���󥱡����Խ����������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseAction.class.php');

class EditEnqueteAction extends EnqueteBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		$enquete_data = $this->getParameters();
		if (!$enquete_data['enquete_id']) {
			// �����ɲ�
			$enquete_id = $this->db->insertEnqueteData($this->navi_page_id, $enquete_data, $enquete_data['option']);
		} else {
			// �Խ�
			$enquete_id = $this->db->updateEnqueteData($enquete_data, $enquete_data['option']);
		}

		$request->setAttribute('enquete_id', $enquete_id);

		return View::SUCCESS;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		$enquete_id = $request->getParameter('enquete_id');
		if ($enquete_id) {
			// �Խ�
			$enquete_data = $this->db->getEnqueteData($enquete_id);
			$enquete_data['option'] = $this->db->getOptionData($enquete_id);
		} else {
			// �����ɲ�
			$enquete_data['question_type'] = 1;
			$enquete_data['comment_flag'] = 1;
			$enquete_data['trackback_flag'] = 1;
			$enquete_data['option'] = array();
			$enquete_data['start_date'] = date('Y-m-d H:i:s', time() + 24 * 60 * 60);
			$enquete_data['end_date'] = date('Y-m-d H:i:s', time() + 14 * 24 * 60 * 60);
			$enquete_data['open_flag'] = 1;
		}

		// ���󥱡��ȥǡ�������
		$request->setAttribute('enquete_data', $enquete_data);

		return View::INPUT;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::POST;
	}

	// ��Validator��Ͽ
	public function registerValidators($validatorManager) {
		$validatorManager->registerName('enquete_title', TRUE, '�����ȥ뤬̤���ϤǤ���');
		$validatorManager->registerName('enquete_question_text', TRUE, '����ʸ��̤���ϤǤ���');
		$validatorManager->registerName('enquete_question_type', TRUE, '����褬̤����Ǥ���');
		$validatorManager->registerName('enquete_comment_flag', TRUE, '�����Ȥ�̤����Ǥ���');
		$validatorManager->registerName('enquete_trackback_flag', TRUE, '�ȥ�å��Хå���̤����Ǥ���');
	}

	// �����ϥǡ��������å�
	public function validate() {
		$request = $this->getContext()->getRequest();

		// ��������ϥ����å�
		$option_exist = false;
		foreach ($request->getParameter('enquete_option') as $option_text) {
			if ($option_text != '' ) {
				$option_exist = true;
				break;
			}
		}
		if (!$option_exist)
			$request->setError('enquete_option', '����褬̤���ϤǤ���');

		// �������ե����å�
		$start_date = $this->checkDate($this->getDateParameter('enquete_start_date'));
		if (!$start_date)
			$request->setError('enquete_start_date', '�������դ������Ǥ���');

		// ��λ���ե����å�
		$end_date = $this->checkDate($this->getDateParameter('enquete_end_date'));
		if (!$end_date)
			$request->setError('enquete_end_date', '��λ���դ������Ǥ���');

		if ($start_date) {
			// ��������̤��Ǥ��뤫�����å�
//			if ($start_date < time())
//				$request->setError('enquete_start_date', '�������դ�᤮�Ƥ��ޤ���');

			// �������Ƚ�λ���δط������å�
			if ($end_date) {
				if ($start_date > $end_date)
					$request->setError('enquete_end_date', '��λ���դ��������դ�����ˤʤäƤ��ޤ���');
			}
		}

		return !$request->hasErrors();
	}

	// �����ϥ��顼����
	public function handleError() {
		$request = $this->getContext()->getRequest();

		// ���󥱡��ȥǡ�������
		$enquete_data = $this->getParameters();
		$request->setAttribute('enquete_data', $enquete_data);

		return View::INPUT;
	}

	// ���ѥ�᡼������
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['enquete_id'] = $request->getParameter('enquete_id');
		$params['title'] = $request->getParameter('enquete_title');
		$params['question_text'] = $request->getParameter('enquete_question_text');
		$params['question_type'] = $request->getParameter('enquete_question_type');
		$params['comment_flag'] = $request->getParameter('enquete_comment_flag');
		$params['trackback_flag'] = $request->getParameter('enquete_trackback_flag');
		$params['start_date'] = $this->getDateParameter('enquete_start_date');
		$params['end_date'] = $this->getDateParameter('enquete_end_date');
		$params['open_flag'] = $request->getParameter('enquete_open_flag');

		// ��������
		$params['option'] = array();
		foreach ($request->getParameter('enquete_option') as $option_no => $option_text)
			$params['option'][] = array('option_no' => $option_no, 'option_text' => $option_text);

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