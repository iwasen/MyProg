<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ���С����󥱡��ȴ�λ�ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class EnqueteSuccessView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	function execute() {
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('EnqueteSuccess.html');

		// �ǡ�������
		$data =	$request->getAttribute('waiwai_enq_data');
		$this->setAttribute('waiwai_enq_data', $data);

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}
}

?>