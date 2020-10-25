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

class _blockNewRemarksSuccessView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('_blockNewRemarks.html');

		$waiwai_list_ary = array();
		$list_ary = $request->getAttribute('waiwai_list_ary');
		if (is_array($list_ary)) {
			foreach ($list_ary as $i => $list) {
				$list['date'] = $this->format_datetime($list['date']);
				$list['content'] = $this->truncate_string($list['content'], 40, 20);
				$list['bgcolor'] = $i % 2 ? '#F0FFE8' : '#FFFFFF';
				$waiwai_list_ary[] = $list;
			}
		}
		$this->setAttribute('waiwai_list_ary', $waiwai_list_ary);
	}
}
?>