<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 一般からの投稿入力ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class PostQuestionInputView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('PostQuestionInput.html');

		// 投稿内容設定
		$this->setAttribute('waiwai_post', $request->getAttribute('waiwai_post'));

		// エラーメッセージ設定
		$this->setAttribute('waiwai_error_ary', $request->getErrors());

		// メインテンプレート
		$this->useMainTemplate();
	}
}
?>