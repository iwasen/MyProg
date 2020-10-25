<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 発言集計ダウンロードビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class DownloadCountSuccessView extends WaiwaiBaseView {
	// ■ビュー実行
	public function execute() {
	}

	// ■CSV出力
	public function &render() {
		$request = $this->getContext()->getRequest();

		$csv_ary[] = array('ニックネーム', '総発言', '発言日数');

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