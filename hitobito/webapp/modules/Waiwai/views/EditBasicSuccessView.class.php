<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 基本情報設定完了ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class EditBasicSuccessView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		// テンプレート設定
		$this->setTemplate('EditBasicSuccess.html');

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>