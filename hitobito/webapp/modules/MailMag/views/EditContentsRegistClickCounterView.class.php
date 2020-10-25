<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * クリックカウンタ設定完了ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class EditContentsRegixxxlickCounterView extends MailMagBaseView {
	// ■ビュー実行
	function execute(){
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('RegixxxlickCounter.html');

		// メルマガ入力データ設定
		$this->setContentsData();

		// メインテンプレート
		$this->useMainTemplate();
	 }
}
?>