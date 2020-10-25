<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * 購読メルマガ登録/解除アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseAction.class.php');

class SubscriptionListAction extends MailMagBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		$submit = $this->getSubmitButton();
		if (preg_match('/^mailmag_cancel_([0-9]+)_btn$/', $submit, $matches)) {
			$this->db->deleteMelmagaMember2($matches[1], $this->member_id);
			$request->setAttribute('mailmag_msg', 'メルマガの購読を解除しました。');
		}

		return $this->getDefaultView();
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		$mailmag_subscription_list_ary = $this->db->getSubscriptionList($this->member_id);
		$request->setAttribute('mailmag_subscription_list_ary', $mailmag_subscription_list_ary);
		$request->setAttribute('user_page_url', $this->db->getConst('user_page_url'));

		return View::SUCCESS;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::POST;
	}

	// ■認証を要求
	public function isSecure() {
		return true;
	}
}
?>