<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 基本情報入力ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class EditBasicInputView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('EditBasicInput.html');

		// 基本情報設定
		$this->setAttribute('waiwai_basic', $request->getAttribute('waiwai_basic'));

		// エラーメッセージ設定
		$this->setAttribute('waiwai_error_ary', $request->getErrors());

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>