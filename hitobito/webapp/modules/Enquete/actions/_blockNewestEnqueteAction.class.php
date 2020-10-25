<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �ʥӥ��󥱡��� �ǿ����󥱡���ɽ�����������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseAction.class.php');

class _blockNewestEnqueteAction extends EnqueteBaseAction {
	// �����������¹�
	public function execute() {
		return View::NONE;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		if ($this->navi_page_id) {
			// �ǿ����󥱡���ID����
			$enquete_id = $this->db->getNewestEnquete($this->navi_page_id, $this->member_id);

			// ���󥱡��ȷ������
			if ($enquete_id) {
				$enquete_data = $this->db->getEnqueteData($enquete_id);
				$enquete_data['option'] = $this->db->getAnswerOption($enquete_id);
				$enquete_data['answer_count'] = $this->db->getAnswerCount($enquete_id);
				$request->setAttribute('enquete_data', $enquete_data);

				return View::SUCCESS;
			}
		}

		return View::NONE;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::NONE;
	}
}
?>