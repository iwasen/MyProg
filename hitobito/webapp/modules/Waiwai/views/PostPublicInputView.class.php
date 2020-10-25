<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 一般ユーザーからの投稿入力ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class PostPublicInputView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('PostPublicInput.html');

		// 一般ユーザーからの投稿データ設定
		$waiwai_public = $request->getAttribute('waiwai_public');
		$waiwai_public['date'] = $this->format_date($waiwai_public['date']);
		$this->setAttribute('waiwai_public', $waiwai_public);

		// エラーメッセージ設定
		$this->setAttribute('waiwai_error_ary', $request->getErrors());

		// メインテンプレート
		$this->useMainTemplate();
	}
}
?>