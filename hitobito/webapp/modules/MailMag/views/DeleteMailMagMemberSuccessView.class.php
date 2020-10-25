<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * メルマガ管理 代理解除完了ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class DeleteMailMagMemberSuccessView extends MailMagBaseView {
	// ■ビュー実行
	function execute(){
		$this->setTemplate('DeleteComplete.html');
		$this->useMainTemplate();
	 }
}
?>