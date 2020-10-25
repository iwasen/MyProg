<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * メルマガ登録/解除ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class _blockRegistMailMagInputView extends MailMagBaseView {
	// ■ビュー実行
	function execute(){
		$this->setTemplate('_blockRegistMailMag.html');
	 }
}
?>