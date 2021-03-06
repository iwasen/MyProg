<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 発言編集入力ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class EditRemarkInputView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('EditRemarkInput.html');

		// 閲覧共通設定
		$this->setBowseCommon();

		// 発言内容設定
		$this->setContents();

		// エラーメッセージ設定
		$this->setAttribute('waiwai_error_ary', $request->getErrors());

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>