<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ����åɰ�ư���ϥӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class MoveThreadInputView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	function execute() {
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('MoveThreadInput.html');

		// ������������
		$this->setBowseCommon();

		// ȯ��ID����
		$this->setAttribute('waiwai_move', $request->getAttribute('waiwai_move'));

		// ���顼��å���������
		$this->setAttribute('waiwai_error_ary', $request->getErrors());

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}
}

?>