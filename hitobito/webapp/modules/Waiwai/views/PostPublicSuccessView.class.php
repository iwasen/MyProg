<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ���̥桼�����������ƴ�λ�ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class PostPublicSuccessView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	function execute() {
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('PostPublicSuccess.html');

		$waiwai_public = $request->getAttribute('waiwai_public');
		$waiwai_public['date'] = $this->format_date($waiwai_public['date']);
		$this->setAttribute('waiwai_public', $waiwai_public);

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}
}
?>