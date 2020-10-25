<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 退会者メール入力ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class EditResignMailInputView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('EditResignMailInput.html');

		// ゲスト登録メール情報設定
		$this->setAttribute('waiwai_resign_mail', $request->getAttribute('waiwai_resign_mail'));

		// 差込キーワード設定
		$this->setAttribute('waiwai_keyword', $request->getAttribute('waiwai_keyword'));

		// エラーメッセージ設定
		$this->setAttribute('waiwai_error_ary', $request->getErrors());

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>