<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * 購読メルマガ登録/解除ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class SubscribeSuccessView extends MailMagBaseView {
	// ■ビュー実行
	function execute(){
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate($request->getAttribute('mailmag_template'));

		// 購読／解除メルマガ情報
		$this->setAttribute('mailmag_info', $request->getAttribute('mailmag_info'));

		// 同一チャネルメルマガリスト
		$mailmag_list_ary = $request->getAttribute('mailmag_list_ary');
		$this->setBgColor($mailmag_list_ary, '#FFFFFF', '#F5F5F5');
		$this->setAttribute('mailmag_list_ary', $mailmag_list_ary);

		// メインテンプレート
		$this->useMainTemplate();
	 }
}
?>