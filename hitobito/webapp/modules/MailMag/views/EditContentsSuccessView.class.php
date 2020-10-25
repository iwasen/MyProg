<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * メルマガ管理 配信メルマガ登録/編集完了ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class EditContentsSuccessView extends MailMagBaseView {
	// ■ビュー実行
	function execute(){
		$this->setTemplate('CompleteMailMag.html');
		$this->useMainTemplate();
	 }
}
?>