<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ���ޥ����� �ۿ����ޥ���Ͽ/�Խ��ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class EditContentsInputView extends MailMagBaseView {
	// ���ӥ塼�¹�
	function execute(){
		$this->setTemplate('EditContents.html');
		$this->setContentsData();

		$request = $this->getContext()->getRequest();

		// ���顼��å���������
		$this->setAttribute('mailmag_error_ary', $request->getErrors());

		$this->useMainTemplate();
	 }
}
?>