<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * メルマガバックナンバー表示ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class _blockMailMagBacknumber2SuccessView extends MailMagBaseView {
	// ■ビュー実行
	function execute() {
		$request = $this->getContext()->getRequest();

		$this->setTemplate('_blockMailMagBacknumber2.html');

		$mailmag = $request->getAttribute('mailmag');
		$this->setAttribute('mailmag', $mailmag);
   }
}
?>