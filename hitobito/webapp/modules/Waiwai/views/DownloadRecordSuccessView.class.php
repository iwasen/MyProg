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

class DownloadRecordSuccessView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	public function execute() {
	}

	// ��CSV����
	public function &render() {
		$request = $this->getContext()->getRequest();

		$csv_ary = $request->getAttribute('waiwai_download_data');

		$this->outputCSV('record.csv', $csv_ary);

	    $retval = null;
		return $retval;
	}
}
?>