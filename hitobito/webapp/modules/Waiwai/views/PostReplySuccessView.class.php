<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� �ֿ���λ�ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class PostReplySuccessView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	function execute() {
		// �ƥ�ץ졼������
		$this->setTemplate('PostReplySuccess.html');

		// ������������
		$this->setBowseCommon();

		// �ֿ���ȯ����������
		$this->setContents();

		// �ֿ���������
		$this->setPost();

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}
}

?>