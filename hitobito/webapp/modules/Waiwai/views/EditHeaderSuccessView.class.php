<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� �إå����եå����괰λ�ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class EditHeaderSuccessView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	function execute() {
		// �ƥ�ץ졼������
		$this->setTemplate('EditHeaderSuccess.html');

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}
}

?>