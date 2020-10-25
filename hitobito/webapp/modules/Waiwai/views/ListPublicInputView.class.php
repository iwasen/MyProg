<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ���̥桼�����������ƥꥹ�ȥӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class ListPublicInputView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	function execute() {
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('ListPublicInput.html');

		// ���̥桼�����������ƥꥹ������
		$public_list_ary = $request->getAttribute('waiwai_public_list_ary');
		$this->setAttribute('waiwai_public_list_ary', $this->formatList($public_list_ary));

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}

	// ���ꥹ�Ȥ�ɽ���Ѥ˥ե����ޥå�
	private function formatList($list_ary) {
		$format_list_ary = array();
		if (is_array($list_ary)) {
			foreach ($list_ary as $list) {
				switch ($list['status']) {
				case 1:
					$list['status'] = '̤���';
					break;
				case 2:
					$list['status'] = '��ƺ�';
					break;
				case 3:
					$list['status'] = '��ǧ';
					break;
				case 4:
					$list['status'] = '���Ǥ�';
					break;
				}

				$list['date'] = $this->format_date($list['date'], '-');

				$format_list_ary[] = $list;
			}
		}

		return $format_list_ary;
	}
}

?>