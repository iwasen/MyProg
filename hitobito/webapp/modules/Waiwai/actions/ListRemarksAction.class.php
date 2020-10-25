<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� �磻�磻��ĺǿ���ƥ֥�å�ɽ�����������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class ListRemarksAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		return View::SUCCESS;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		// ��Ƭ���ե��åȼ���
		$list_offset = (int)$request->getParameter('list_offset');
		if ($list_offset < 0)
			$list_offset = 0;

		// �磻�磻��ĺǿ��������
		$list_ary = $this->db->getNewRemarks($this->channel_id, $this->room_id, 11, $this->member_id, $list_offset);
		$request->setAttribute('waiwai_list_ary', $list_ary);
		$request->setAttribute('waiwai_list_offset', $list_offset);

		return View::SUCCESS;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::NONE;
	}
}
?>