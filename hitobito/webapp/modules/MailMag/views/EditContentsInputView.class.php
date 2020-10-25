<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * メルマガ管理 配信メルマガ登録/編集ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class EditContentsInputView extends MailMagBaseView {
	// ■ビュー実行
	function execute(){
		$this->setTemplate('EditContents.html');
		$this->setContentsData();

		$request = $this->getContext()->getRequest();

		// エラーメッセージ設定
		$this->setAttribute('mailmag_error_ary', $request->getErrors());

		$this->useMainTemplate();
	 }
}
?>