<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * メルマガ管理 配信メルマガ一覧表示ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class ListMailMagSuccessView extends MailMagBaseView {
	// ■ビュー実行
	function execute(){
		$this->setTemplate('ListMailMag.html');
		$this->SetListData();

		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		$this->setAttribute('mailmag_current_contents_id', $user->getAttribute('mailmag_current_contents_id'));

		// エラーメッセージ設定
		$this->setAttribute('mailmag_error_ary', $request->getErrors());

		$this->useMainTemplate();
	 }
}
?>