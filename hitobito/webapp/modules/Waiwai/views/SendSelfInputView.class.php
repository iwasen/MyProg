<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 「この発言を自分に送る」入力ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class SendSelfInputView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		// テンプレート設定
		$this->setTemplate('SendSelfInput.html');

		// 閲覧共通設定
		$this->setBowseCommon();

		// 送信内容設定
		$this->setContents();

		// ログインメンバー情報設定
		$this->setMemberInfo();

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>