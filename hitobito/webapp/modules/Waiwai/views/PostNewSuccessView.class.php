<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ������ƴ�λ�ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class PostNewSuccessView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	function execute() {
		// �ƥ�ץ졼������
		$this->setTemplate('PostNewSuccess.html');

		// ������������
		$this->setBowseCommon();

		// ȯ����������
		$this->setPost();

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}
}

?>