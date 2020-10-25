<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 入会者メール入力ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class EditRegistMailInputView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('EditRegistMailInput.html');

		// ゲスト登録メール情報設定
		$this->setAttribute('waiwai_regist_mail', $request->getAttribute('waiwai_regist_mail'));

		// 差込キーワード設定
		$this->setAttribute('waiwai_keyword', $request->getAttribute('waiwai_keyword'));

		// エラーメッセージ設定
		$this->setAttribute('waiwai_error_ary', $request->getErrors());

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>