<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 新規投稿入力ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class PostNewConfirmView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('PostNewConfirm.html');

		// 閲覧共通設定
		$this->setBowseCommon();

		// 発言内容設定
		$this->setPost();

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>