<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 ヘッダ・フッタ入力ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class EditHeaderInputView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('EditHeaderInput.html');

		// ヘッダ／フッタ情報設定
		$this->setAttribute('waiwai_header', $request->getAttribute('waiwai_header'));

		// 差込キーワード設定
		$this->setAttribute('waiwai_keyword', $request->getAttribute('waiwai_keyword'));

		// エラーメッセージ設定
		$this->setAttribute('waiwai_error_ary', $request->getErrors());

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>