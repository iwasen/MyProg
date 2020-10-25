<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 一般ユーザーからの投稿完了ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class PostPublicSuccessView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('PostPublicSuccess.html');

		$waiwai_public = $request->getAttribute('waiwai_public');
		$waiwai_public['date'] = $this->format_date($waiwai_public['date']);
		$this->setAttribute('waiwai_public', $waiwai_public);

		// メインテンプレート
		$this->useMainTemplate();
	}
}
?>