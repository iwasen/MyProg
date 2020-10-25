<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 メンバーアンケート完了ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class EnqueteSuccessView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('EnqueteSuccess.html');

		// データ設定
		$data =	$request->getAttribute('waiwai_enq_data');
		$this->setAttribute('waiwai_enq_data', $data);

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>