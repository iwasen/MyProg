<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �ʥӥ��󥱡��� ���󥱡��Ȱ���ɽ�����������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseAction.class.php');

class _blockEnqueteListAction extends EnqueteBaseAction {
	// �����������¹�
	public function execute() {
		return View::NONE;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		if ($this->navi_page_id) {
			// ���󥱡��Ȱ�������
			$list_ary = $this->db->getOpenEnqueteList($this->channel_id, $this->navi_page_id, $this->member_id, 3, 0, '2,3');
			if ($list_ary) {
				$request->setAttribute('enquete_list_ary', $list_ary);
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