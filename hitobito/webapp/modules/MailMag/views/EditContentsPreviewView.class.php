<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * メルマガ管理 配信メルマガ登録/プレビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class EditContentsPreviewView extends MailMagBaseView {
	// ■ビュー実行
	function execute(){
		$this->setTemplate('PreviewContents.html');

		$this->setContentsData();

		$this->useMainTemplate();
	 }
}
?>