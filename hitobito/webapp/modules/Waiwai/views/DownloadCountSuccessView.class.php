<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ȯ�����ץ�������ɥӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class DownloadCountSuccessView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	public function execute() {
	}

	// ��CSV����
	public function &render() {
		$request = $this->getContext()->getRequest();

		$csv_ary[] = array('�˥å��͡���', '��ȯ��', 'ȯ������');

		$data_ary = $request->getAttribute('waiwai_download_data');
		if (is_array($data_ary)) {
			foreach ($data_ary as $data) {
				$csv = array();
				$csv[] = $data['nickname'];
				$csv[] = $data['count1'];
				$csv[] = $data['count2'];
				$csv_ary[] = $csv;
			}
		}

		$this->outputCSV('count.csv', $csv_ary);

	    $retval = null;
		return $retval;
	}
}
?>