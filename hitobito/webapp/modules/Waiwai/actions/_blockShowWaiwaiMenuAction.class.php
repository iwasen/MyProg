<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� �磻�磻��ļ��Խ���˥塼ɽ�����������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class _blockShowWaiwaiMenuAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		return View::SUCCESS;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		return View::SUCCESS;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::NONE;
	}
}
?>