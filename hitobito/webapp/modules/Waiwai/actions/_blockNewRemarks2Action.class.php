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

class _blockNewRemarks2Action extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		return View::SUCCESS;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		// �磻�磻��ĺǿ��������
//		$list_ary = $this->db->getNewRemarks($this->channel_id, $this->room_id, 3, $this->member_id);
		$list_ary = $this->db->getNewRemarks($this->channel_id, hitobito::getNaviPageInfo()->getWaiwaiId() ,3, $this->member_id);
		if ($list_ary) {
			$request->setAttribute('waiwai_list_ary', $list_ary);

			return View::SUCCESS;
		}

		return View::NONE;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::NONE;
	}
}
?>