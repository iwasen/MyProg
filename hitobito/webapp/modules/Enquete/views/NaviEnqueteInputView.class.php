<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �ʥӥ��󥱡��� ���󥱡����Խ��������ϥӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseView.class.php');

class NaviEnqueteInputView extends EnqueteBaseView {
	// ���ӥ塼�¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('NaviEnqueteInput.html');

		// ���󥱡��ȥꥹ������
		$enquete_list_ary = $request->getAttribute('enquete_list_ary');
		$this->setAttribute('enquete_list_ary', $this->formatList($enquete_list_ary));

		// ������Υ��󥱡���ID����
		$this->setAttribute('enquete_id', $request->getAttribute('enquete_id'));

		// ���顼��å���������
		$this->setAttribute('enquete_error_ary', $request->getErrors());

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}

	// ���ꥹ�Ȥ�ɽ���Ѥ˥ե����ޥå�
	private function formatList($list_ary) {
		$format_list_ary = array();
		if (is_array($list_ary)) {
			$no = 1;
			foreach ($list_ary as $list) {
				$list['no'] = $no++;
				$list['start_date'] = $this->format_date($list['start_date'], '-');

				switch ($list['status']) {
				case 1:
					$list['status'] = '̤�»�';
					break;
				case 2:
					$list['status'] = '�»���';
					break;
				case 3:
					$list['status'] = '��λ';
					break;
				}

				$format_list_ary[] = $list;
			}
		}

		return $format_list_ary;
	}
}

?>