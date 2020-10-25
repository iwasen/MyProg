<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 返信完了ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class PostReplySuccessView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		// テンプレート設定
		$this->setTemplate('PostReplySuccess.html');

		// 閲覧共通設定
		$this->setBowseCommon();

		// 返信先発言内容設定
		$this->setContents();

		// 返信内容設定
		$this->setPost();

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>