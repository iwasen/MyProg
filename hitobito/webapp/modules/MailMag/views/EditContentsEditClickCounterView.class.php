<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ����å�����������ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class EditContentsEditClickCounterView extends MailMagBaseView {
	// ���ӥ塼�¹�
	function execute(){
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('EditClickCounter.html');

		// ���ޥ����ϥǡ�������
		$this->setContentsData();

		// ����å�����������
		$this->setAttribute('mailmag_click_counter_ary', $request->getAttribute('mailmag_click_counter_ary'));

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	 }
}
?>