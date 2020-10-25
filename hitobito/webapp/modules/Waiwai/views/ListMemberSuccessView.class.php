<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 メンバー一覧設定完了ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class ListMemberSuccessView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		// テンプレート設定
		$this->setTemplate('ListMemberSuccess.html');

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>