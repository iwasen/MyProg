<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 ゲスト登録メール設定完了ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class EditGuestMailSuccessView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		// テンプレート設定
		$this->setTemplate('EditGuestMailSuccess.html');

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>