<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� �磻�磻��ļ��Խ���˥塼�ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class _blockShowWaiwaiMenuSuccessView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	public function execute() {
		// �ƥ�ץ졼������
		$this->setTemplate('_blockShowWaiwaiMenu.html');
	}
}
?>