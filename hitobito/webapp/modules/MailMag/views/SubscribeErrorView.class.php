<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ���ɥ��ޥ���Ͽ/������顼�ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class SubscribeErrorView extends MailMagBaseView {
	// ���ӥ塼�¹�
	function execute(){
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('SubscribeError.html');

		// ���С���Ͽ�ե饰
		$this->setAttribute('mailmag_member_regist_flag', $request->getAttribute('mailmag_member_regist_flag'));

		// ���顼��å���������
		$this->setAttribute('mailmag_error_ary', $request->getErrors());

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	 }
}
?>