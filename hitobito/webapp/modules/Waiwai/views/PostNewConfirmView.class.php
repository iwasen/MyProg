<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ����������ϥӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class PostNewConfirmView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	function execute() {
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('PostNewConfirm.html');

		// ������������
		$this->setBowseCommon();

		// ȯ����������
		$this->setPost();

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}
}

?>