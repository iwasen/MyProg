<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ���ԥ᡼�����ϥӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class EditResignMailInputView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	function execute() {
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('EditResignMailInput.html');

		// ��������Ͽ�᡼���������
		$this->setAttribute('waiwai_resign_mail', $request->getAttribute('waiwai_resign_mail'));

		// ���������������
		$this->setAttribute('waiwai_keyword', $request->getAttribute('waiwai_keyword'));

		// ���顼��å���������
		$this->setAttribute('waiwai_error_ary', $request->getErrors());

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}
}

?>