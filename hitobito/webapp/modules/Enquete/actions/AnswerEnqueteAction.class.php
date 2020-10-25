<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �ʥӥ��󥱡��� ���󥱡��Ȳ������������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseAction.class.php');

class AnswerEnqueteAction extends EnqueteBaseAction {
	// �����������¹�
	public function execute() {
		$context = $this->getContext();
		$request = $context->getRequest();
		$user = $context->getUser();
		$controller = $context->getController();

		$enquete_id = $request->getParameter('enquete_id');
		if ($enquete_id) {
			if ($user->getAttribute('enquete_answer_flag')) {
				// �����Ѥ�
				$user->setAttribute('enquete_answer_flag', false);

				// ������¸
				$seq_no = $this->db->insertAnswer($enquete_id, $this->member_id, $request->getParameter('enquete_option'));

				// �������������ֹ�򥻥å�������¸�ʥ����Ȥ��б��դ��뤿���
				$user->setAttribute('enquete_seq_no', $seq_no);

				// �֥��󥱡��Ȳ������꤬�Ȥ��������ޤ����פ�ɽ��
				$request->setAttribute('enquete_answer_ok', true);
			}

			// ���ɽ��
			$request->setMethod(REQUEST::GET);
			$controller->forward('Enquete', 'ShowResult');
			return View::NONE;
		}

		return View::INPUT;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$context = $this->getContext();
		$request = $context->getRequest();
		$user = $context->getUser();

		$enquete_id = $request->getParameter('enquete_id');
		if (!$this->db->checkEnqueteId($enquete_id, $this->member_id, 2)) {
			// ���󥱡���ID��̵����а���ɽ����
			$controller = $this->getContext()->getController();
			$controller->forward('Enquete', 'ListEnquete');
			return View::NONE;
		}

		// ���󥱡�����������
		$enquete_data = $this->db->getEnqueteData($enquete_id);
		$enquete_data['option'] = $this->db->getOptionData($enquete_id);
		$request->setAttribute('enquete_data', $enquete_data);

		// ¿�Ų������ɤ�����Υե饰����
		$user->setAttribute('enquete_answer_flag', true);

		return View::INPUT;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		$context = $this->getContext();
		$request = $context->getRequest();
		$user = $context->getUser();

		// ���ޥ����饯��å����줿���ν���
		if ($request->getParameter('option_no') != '') {
			$enquete_option[] = $request->getParameter('option_no');
			$request->setParameter('enquete_option', $enquete_option);
			return Request::GET | Request::POST;
		}

		return Request::POST;
	}

	// �����ϥǡ��������å�
	public function validate() {
		$request = $this->getContext()->getRequest();

		// �ҤȤĤ������Υ����å���̵����Х��顼
		if ($request->getParameter('enquete_option') == null) {
			$request->setError('enquete_option', '������̤���ϤǤ���');
			return false;
		}

		return true;
	}

	// �����ϥ��顼����
	public function handleError() {
		return $this->getDefaultView();
	}
}
?>