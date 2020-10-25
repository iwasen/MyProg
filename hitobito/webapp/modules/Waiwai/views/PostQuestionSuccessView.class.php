<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 一般からの投稿完了ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class PostQuestionSuccessView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('PostQuestionSuccess.html');

		// 投稿内容設定
		$this->setAttribute('waiwai_post', $request->getAttribute('waiwai_post'));

		// メインテンプレート
		$this->useMainTemplate();
	}
}
?>