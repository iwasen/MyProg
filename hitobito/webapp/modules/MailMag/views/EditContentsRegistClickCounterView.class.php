<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ����å����������괰λ�ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class EditContentsRegixxxlickCounterView extends MailMagBaseView {
	// ���ӥ塼�¹�
	function execute(){
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('RegixxxlickCounter.html');

		// ���ޥ����ϥǡ�������
		$this->setContentsData();

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	 }
}
?>