<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� �ǿ��磻�磻�����ƥ֥�å��ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class _blockNewRemarks2SuccessView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('_blockNewRemarks2.html');

		$waiwai_list_ary = $request->getAttribute('waiwai_list_ary');
		$this->setAttribute('waiwai_list_ary', $waiwai_list_ary);
	}
}
?>