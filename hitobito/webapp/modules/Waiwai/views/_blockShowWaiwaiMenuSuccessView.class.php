<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 ワイワイ会議室編集メニュービュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class _blockShowWaiwaiMenuSuccessView extends WaiwaiBaseView {
	// ■ビュー実行
	public function execute() {
		// テンプレート設定
		$this->setTemplate('_blockShowWaiwaiMenu.html');
	}
}
?>