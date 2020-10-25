<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * メルマガ管理 代理解除ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class DeleteMailMagMemberInputView extends MailMagBaseView {
	// ■ビュー実行
	function execute(){
		$this->setTemplate('DeleteMailMag.html');
		$this->setDeleteData();

		$request = $this->getContext()->getRequest();

		// エラーメッセージ設定
		$this->setAttribute('mailmag_error_ary', $request->getErrors());

		$this->useMainTemplate();
	 }
}
?>