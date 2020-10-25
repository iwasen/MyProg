<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 スレッド移動完了ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class MoveThreadSuccessView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		// テンプレート設定
		$this->setTemplate('MoveThreadSuccess.html');

		// 閲覧共通設定
		$this->setBowseCommon();

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>