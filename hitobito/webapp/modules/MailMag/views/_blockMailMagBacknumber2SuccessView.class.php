<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ���ޥ��Хå��ʥ�С�ɽ���ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class _blockMailMagBacknumber2SuccessView extends MailMagBaseView {
	// ���ӥ塼�¹�
	function execute() {
		$request = $this->getContext()->getRequest();

		$this->setTemplate('_blockMailMagBacknumber2.html');

		$mailmag = $request->getAttribute('mailmag');
		$this->setAttribute('mailmag', $mailmag);
   }
}
?>