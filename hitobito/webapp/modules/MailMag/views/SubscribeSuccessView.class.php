<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ���ɥ��ޥ���Ͽ/����ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class SubscribeSuccessView extends MailMagBaseView {
	// ���ӥ塼�¹�
	function execute(){
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate($request->getAttribute('mailmag_template'));

		// ���ɡ�������ޥ�����
		$this->setAttribute('mailmag_info', $request->getAttribute('mailmag_info'));

		// Ʊ�����ͥ���ޥ��ꥹ��
		$mailmag_list_ary = $request->getAttribute('mailmag_list_ary');
		$this->setBgColor($mailmag_list_ary, '#FFFFFF', '#F5F5F5');
		$this->setAttribute('mailmag_list_ary', $mailmag_list_ary);

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	 }
}
?>