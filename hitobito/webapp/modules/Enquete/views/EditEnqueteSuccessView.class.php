<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ナビアンケート アンケート編集完了ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseView.class.php');

class EditEnqueteSuccessView extends EnqueteBaseView {
	// ■ビュー実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('EditEnqueteSuccess.html');

		// アンケートID設定
		$this->setAttribute('enquete_id', $request->getAttribute('enquete_id'));

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>