<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� �֤���ȯ����ʬ������״�λ�ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class SendSelfSuccessView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	function execute() {
		// �ƥ�ץ졼������
		$this->setTemplate('SendSelfSuccess.html');

		// ������������
		$this->setBowseCommon();

		// ������������
		$this->setContents();

		// ��������С���������
		$this->setMemberInfo();

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}
}

?>