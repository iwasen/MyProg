<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ���̤������ƴ�λ�ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class PostQuestionSuccessView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	function execute() {
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('PostQuestionSuccess.html');

		// �����������
		$this->setAttribute('waiwai_post', $request->getAttribute('waiwai_post'));

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}
}
?>