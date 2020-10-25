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

class PostRemarkConfirmView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('PostRemarkConfirm.html');

		// 閲覧共通設定
		$this->setBowseCommon();

		// 返信内容設定
		$this->setPost();

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>