<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ���С����󥱡������ϥӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class EnqueteInputView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	function execute() {
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		// ���顼�ڡ���
		if ($user->getAttribute('waiwai_enquete_id') == '') {
			$msg = '�������륢�󥱡��ȤϤ���ޤ���';
			$this->setAttribute('message', $msg);

			$this->setTemplate('error.html');

		} else {

			// �ƥ�ץ졼������
			$this->setTemplate('EnqueteInput.html');

			$data =	$request->getAttribute('waiwai_enq_ary');
			$this->setAttribute('waiwai_enq_ary', $data);

			// ���顼��å���������
			$this->setAttribute('waiwai_error_ary', $request->getErrors());
		}

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}
}

?>