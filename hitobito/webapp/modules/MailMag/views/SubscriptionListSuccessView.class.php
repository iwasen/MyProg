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

class SubscriptionListSuccessView extends MailMagBaseView {
	// ■ビュー実行
	function execute(){
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('SubscriptionList.html');

		$mailmag_subscription_list_ary = array();
		$mailmag_list_ary = $request->getAttribute('mailmag_subscription_list_ary');
		if (is_array($mailmag_list_ary)) {
			$user_page_url = $request->getAttribute('user_page_url');

			$seq_no = count($mailmag_list_ary);
			foreach ($mailmag_list_ary as $mailmag_list) {
				$mailmag_list['last_publish_date'] = $this->format_date($mailmag_list['last_publish_date'], '-');
				$mailmag_list['image_url'] = "$user_page_url/image.php?id={$mailmag_list['image_id']}";
				$mailmag_list['reader_cnt'] = number_format($mailmag_list['reader_cnt']);
				$mailmag_list['seq_no'] = $seq_no--;

				$mailmag_subscription_list_ary[] = $mailmag_list;
			}
		}
		$this->setAttribute('mailmag_subscription_list_ary', $mailmag_subscription_list_ary);

		$this->setAttribute('mailmag_msg', $request->getAttribute('mailmag_msg'));

		// メインテンプレート
		$this->useMainTemplate();
	 }
}
?>