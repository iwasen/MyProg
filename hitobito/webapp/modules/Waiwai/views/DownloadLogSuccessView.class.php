<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ȯ����������ɥӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class DownloadLogSuccessView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	public function execute() {
	}

	// ��CSV����
	public function &render() {
		$request = $this->getContext()->getRequest();

		$csv_ary[] = array('ȯ��SEQ', '��������', '��̾', '��ʸ', '�˥å��͡���');

		$data_ary = $request->getAttribute('waiwai_download_data');
		if (is_array($data_ary)) {
			foreach ($data_ary as $data) {
				$csv = array();
				$csv[] = $data['seq_no'];
				$csv[] = $this->format_datetime($data['date'], '', '/');
				$csv[] = $data['subject'];
				$csv[] = $data['content'];
				$csv[] = $data['nickname'];
				$csv_ary[] = $csv;
			}
		}

		$this->outputCSV('log.csv', $csv_ary);

	    $retval = null;
		return $retval;
	}
}
?>