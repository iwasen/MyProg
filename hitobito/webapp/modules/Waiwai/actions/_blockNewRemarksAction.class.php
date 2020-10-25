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

class _blockNewRemarksAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		return View::SUCCESS;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		// �磻�磻��ĺǿ��������
		$disp_num = $this->db->getConst('max_new_remarks');
		$list_ary = $this->db->getNewRemarks($this->channel_id, $this->room_id, $disp_num, $this->member_id);
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