<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ���ޥ����� ����å���ɽ���ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class ShowClickSuccessView extends MailMagBaseView {
	// ���ӥ塼�¹�
	function execute(){
		$this->setTemplate('ShowClick.html');

		$request = $this->getContext()->getRequest();
		$contents = $request->getAttribute('mailmag_contents');

		$contents['send_date'] = $this->format_datetime($contents['send_date']);
		$contents['send_num'] = number_format($contents['send_num']);
		$this->setAttribute('mailmag_contents', $contents);

		$this->useMainTemplate();
	 }
}
?>