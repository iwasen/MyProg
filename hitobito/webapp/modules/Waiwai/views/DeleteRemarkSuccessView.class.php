<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ȯ���Խ���λ�ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class DeleteRemarkSuccessView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	function execute() {
		// �ƥ�ץ졼������
		$this->setTemplate('DeleteRemarkSuccess.html');

		// ������������
		$this->setBowseCommon();

		// ȯ����������
		$this->setContents();

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}
}

?>