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

class DownloadRecordSuccessView extends WaiwaiBaseView {
	// ■ビュー実行
	public function execute() {
	}

	// ■CSV出力
	public function &render() {
		$request = $this->getContext()->getRequest();

		$csv_ary = $request->getAttribute('waiwai_download_data');

		$this->outputCSV('record.csv', $csv_ary);

	    $retval = null;
		return $retval;
	}
}
?>