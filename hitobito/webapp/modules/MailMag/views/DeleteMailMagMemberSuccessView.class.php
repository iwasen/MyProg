<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ���ޥ����� ���������λ�ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class DeleteMailMagMemberSuccessView extends MailMagBaseView {
	// ���ӥ塼�¹�
	function execute(){
		$this->setTemplate('DeleteComplete.html');
		$this->useMainTemplate();
	 }
}
?>