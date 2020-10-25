<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * メルマガ管理 クリック数表示ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class ShowClickSuccessView extends MailMagBaseView {
	// ■ビュー実行
	function execute(){
		$this->setTemplate('ShowClick.html');

		$request = $this->getContext()->getRequest();
		$contents = $request->getAttribute('mailmag_contents');

		$contents['send_date'] = $this->format_datetime($contents['send_date']);
		$contents['send_num'] = number_format($contents['send_num']);
		$this->setAttribute('mailmag_contents', $contents);

		$this->useMainTemplate();
	 }
}
?>