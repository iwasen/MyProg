<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 属性入力ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class RegistMemberSubitemInputView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('RegistMemberSubitemInput.html');

		// 閲覧共通設定
		$this->setBowseCommon();

		// 属性情報設定
		$this->setSubitem();

		// 住所1情報設定
		$this->setAddress1();

		// 生年月日情報設定
		$this->setBirth();

		// エラーメッセージ設定
		$this->setAttribute('waiwai_error_ary', $request->getErrors());

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>