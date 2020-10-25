<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 発言ダウンロードビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class DownloadLogSuccessView extends WaiwaiBaseView {
	// ■ビュー実行
	public function execute() {
	}

	// ■CSV出力
	public function &render() {
		$request = $this->getContext()->getRequest();

		$csv_ary[] = array('発言SEQ', '受信日時', '件名', '本文', 'ニックネーム');

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