<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �ʥӥ��󥱡��� �����󥱡��Ȱ���ɽ�����������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseAction.class.php');

class ListEnqueteAction extends EnqueteBaseAction {
	// �����������¹ԡʤ���method���¹Ԥ���뤳�Ȥ�̵����
	public function execute() {
		return View::INPUT;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$this->setListData();

		return View::INPUT;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::NONE;
	}

	// �����ϥ��顼����
	public function handleError() {
		return getDefaultView();
	}

	// ���ꥹ�ȥǡ�������
	private function setListData() {
		$request = $this->getContext()->getRequest();

		// �ڡ���������
		$page_size = 8;

		// ���󥱡��ȿ�����
		$enquete_count = $this->db->getOpenEnqueteCount($this->channel_id, $this->navi_page_id, $this->member_id);

		// �ڡ�����
		$max_page = (int)(($enquete_count + $page_size - 1) / $page_size);
		if ($max_page == 0)
			$max_page = 1;

		// �ڡ����ֹ����
		$current_page = $request->getParameter('enquete_page_no');
		if (!$current_page)
			$current_page = 1;

		// ���ڡ��������ڡ�������
		if ($request->getParameter('enquete_page_move') != '')
			$current_page += $request->getParameter('enquete_page_move');

		// �ڡ�����������
		$enquete_page['max_page'] = $max_page;
		$enquete_page['current_page'] = $current_page;
		$request->setAttribute('enquete_page', $enquete_page);

		// ���󥱡��Ȱ�������
		$list_ary = $this->db->getOpenEnqueteList($this->channel_id, $this->navi_page_id, $this->member_id, $page_size, ($current_page - 1) * $page_size, '2,3');

		// �ǿ�����η�̤�����
		if ($current_page == 1 && is_array($list_ary)) {
			foreach ($list_ary as $i => &$list) {
				$enquete_id = $list['enquete_id'];

				// ���󥱡��ȷ������
				$list['option'] = $this->db->getAnswerOption($enquete_id);
				$list['answer_count'] = $this->db->getAnswerCount($enquete_id);

				if ($i >= 1)
					break;
			}
		}

		// ���󥱡��Ȱ�������
		$request->setAttribute('enquete_list_ary', $list_ary);
	}
}
?>