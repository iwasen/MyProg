<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ȯ��������ϥӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class DeleteRemarkConfirmView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	function execute() {
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('DeleteRemarkConfirm.html');

		// ������������
		$this->setBowseCommon();

		// ȯ����������
		$this->setContents();

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}
}

?>