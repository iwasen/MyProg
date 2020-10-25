<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �ʥӥ��󥱡��� ���󥱡����Խ��������������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseAction.class.php');

class NaviEnqueteAction extends EnqueteBaseAction {
	// �����������¹�
	public function execute() {
		$controller = $this->getContext()->getController();
		$request = $this->getContext()->getRequest();

		$params = $this->getParameters();
		switch ($params['submit']) {
		case 'enquete_new_btn':		// �����ɲ�
			$request->removeParameter('enquete_id');
			// no break
		case 'enquete_edit_btn':	// �Խ�
			$request->setMethod(REQUEST::GET);
			$controller->forward('Enquete', 'EditEnquete');
			return View::NONE;
		case 'enquete_up_btn':		// ��ذ�ư
			$this->db->moveEnquete($this->navi_page_id, $params['enquete_id'], true);
			break;
		case 'enquete_down_btn':	// ���ذ�ư
			$this->db->moveEnquete($this->navi_page_id, $params['enquete_id'], false);
			break;
		case 'enquete_delete_btn':	// ���
			$this->db->deleteEnquete($params['enquete_id']);
			break;
		case 'enquete_csv_btn':		// CSV����
			$request->setAttribute('enquete_room_code', $this->db->getRoomCode($params['enquete_id']));
			$request->setAttribute('enquete_room_mail_domain', $this->db->getConst('room_mail_domain'));
			$request->setAttribute('enquete_answer_list', $this->db->getAnswerList($params['enquete_id']));
			return 'Csv';
		}

		// ���󥱡��Ȱ�������
		$this->setListData();

		return View::INPUT;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		// ���󥱡��Ȱ�������
		$this->setListData();

		return View::INPUT;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::POST;
	}

	// ��Validator��Ͽ
	public function registerValidators($validatorManager) {
		$request = $this->getContext()->getRequest();

		$params = $this->getParameters();

		if (!$params['enquete_id']) {
			switch ($params['submit']) {
			case 'enquete_edit_btn':
			case 'enquete_up_btn':
			case 'enquete_down_btn':
			case 'enquete_delete_btn':
			case 'enquete_csv_btn':
				$validatorManager->registerName('enquete_id', TRUE, '���󥱡��Ȥ�̤����Ǥ���');
				break;
			}
		}
	}

	// �����ϥ��顼����
	public function handleError() {
		// ���󥱡��Ȱ�������
		$this->setListData();

		return View::INPUT;
	}

	// ���ѥ�᡼������
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['enquete_id'] = $request->getParameter('enquete_id');
		$params['submit'] = $this->getSubmitButton();

		return $params;
	}

	// ���ꥹ�ȥǡ�������
	private function setListData() {
		$request = $this->getContext()->getRequest();

		$request->setAttribute('enquete_list_ary', $this->db->getEnqueteList($this->navi_page_id));
		$request->setAttribute('enquete_id', $request->getParameter('enquete_id'));
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