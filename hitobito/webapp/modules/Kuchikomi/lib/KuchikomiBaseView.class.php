<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ��������WEB �ӥ塼���ܥ��饹
 *
 *
 * @package
 * @author
 * @version
 */

abstract class KuchikomiBaseView extends SmartyView {
	// �������
	public function initialize($context) {
		parent::initialize($context);

		// �ʥӥڡ���ID
		$request = $context->getRequest();
		$this->setAttribute('navi_page_id', $request->getAttribute('navi_page_id'));

		return true;
	}
}
?>