<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �ʥӥ��󥱡��� ���󥱡����Խ���λ�ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseView.class.php');

class EditEnqueteSuccessView extends EnqueteBaseView {
	// ���ӥ塼�¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('EditEnqueteSuccess.html');

		// ���󥱡���ID����
		$this->setAttribute('enquete_id', $request->getAttribute('enquete_id'));

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}
}

?>