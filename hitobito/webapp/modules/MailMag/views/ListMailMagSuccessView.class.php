<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ���ޥ����� �ۿ����ޥ�����ɽ���ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class ListMailMagSuccessView extends MailMagBaseView {
	// ���ӥ塼�¹�
	function execute(){
		$this->setTemplate('ListMailMag.html');
		$this->SetListData();

		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		$this->setAttribute('mailmag_current_contents_id', $user->getAttribute('mailmag_current_contents_id'));

		// ���顼��å���������
		$this->setAttribute('mailmag_error_ary', $request->getErrors());

		$this->useMainTemplate();
	 }
}
?>