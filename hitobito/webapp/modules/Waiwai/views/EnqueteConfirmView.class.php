<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ���С����󥱡��ȳ�ǧ�ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class EnqueteConfirmView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	function execute() {
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('EnqueteConfirm.html');

		$data =	$request->getAttribute('waiwai_enq_ary');
		$this->setAttribute('waiwai_enq_ary', $data);


		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}
}

?>