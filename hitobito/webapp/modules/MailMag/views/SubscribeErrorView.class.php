<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * 購読メルマガ登録/解除エラービュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class SubscribeErrorView extends MailMagBaseView {
	// ■ビュー実行
	function execute(){
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('SubscribeError.html');

		// メンバー登録フラグ
		$this->setAttribute('mailmag_member_regist_flag', $request->getAttribute('mailmag_member_regist_flag'));

		// エラーメッセージ設定
		$this->setAttribute('mailmag_error_ary', $request->getErrors());

		// メインテンプレート
		$this->useMainTemplate();
	 }
}
?>