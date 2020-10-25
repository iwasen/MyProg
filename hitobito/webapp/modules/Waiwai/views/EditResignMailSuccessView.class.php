<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 退会者メール設定完了ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class EditResignMailSuccessView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		// テンプレート設定
		$this->setTemplate('EditResignMailSuccess.html');

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>