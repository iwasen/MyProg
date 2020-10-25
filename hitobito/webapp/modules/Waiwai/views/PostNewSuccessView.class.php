<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 新規投稿完了ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class PostNewSuccessView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		// テンプレート設定
		$this->setTemplate('PostNewSuccess.html');

		// 閲覧共通設定
		$this->setBowseCommon();

		// 発言内容設定
		$this->setPost();

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>