<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �ʥӥ��󥱡��� �Хå��ʥ�С�ɽ���ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseView.class.php');

class _blockEnqueteBacknumberSuccessView extends EnqueteBaseView
{
	// ���ӥ塼�¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('_blockEnqueteBacknumber.html');

		// ���󥱡��ȥꥹ������
		$navi_page_id = $request->getAttribute('navi_page_id');
		$enquete_list_ary = $request->getAttribute('enquete_list_ary');
		$this->setAttribute('enquete_list_ary', $enquete_list_ary);
	}
}
?>