<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� �ֿ����ϳ�ǧ�ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class PostReplyConfirmView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	function execute(){
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('PostReplyConfirm.html');

		// ������������
		$this->setBowseCommon();

		// ȯ����������
		$this->setContents();

		// �ֿ���������
		$this->setPost();

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	 }
}
?>