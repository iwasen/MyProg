<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �ʥӥ��󥱡��� �»��楢�󥱡���ɽ���ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseView.class.php');

class _blockShowEnqListSuccessView extends EnqueteBaseView
{
	// ���ӥ塼�¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('_blockShowEnqList.html');

		$this->setAttribute('enquete_list_ary', $request->getAttribute('enquete_list_ary'));
	}
}
?>