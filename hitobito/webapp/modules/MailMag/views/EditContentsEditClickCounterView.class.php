<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * クリックカウンタ設定ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class EditContentsEditClickCounterView extends MailMagBaseView {
	// ■ビュー実行
	function execute(){
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('EditClickCounter.html');

		// メルマガ入力データ設定
		$this->setContentsData();

		// クリックカウンタ設定
		$this->setAttribute('mailmag_click_counter_ary', $request->getAttribute('mailmag_click_counter_ary'));

		// メインテンプレート
		$this->useMainTemplate();
	 }
}
?>