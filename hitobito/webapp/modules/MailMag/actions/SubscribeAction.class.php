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

class SubscribeAction extends MailMagBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		$mail_addr = $request->getParameter('mailmag_mail_addr');
		$member_id = $this->db->getMemberIdFromMailaddr($mail_addr);

		switch ($this->getSubmitButton()) {
		case 'mailmag_regist_btn':
			$this->db->insertMelmagaMember($this->melmaga_id, $member_id);
			$request->setAttribute('mailmag_template', 'Subscribe.html');
			break;
		case 'mailmag_release_btn':
			$this->db->deleteMelmagaMember2($this->melmaga_id, $member_id);
			$request->setAttribute('mailmag_template', 'Release.html');
			break;
		}

		$mailmag_info = $this->db->getMailMagNavi($this->melmaga_id);
		$mailmag_info['mail_addr'] = $mail_addr;
		$request->setAttribute('mailmag_info', $mailmag_info);

		$mailmag_list_ary = $this->db->getMailMagChannel($mailmag_info['channel_id'], $this->melmaga_id, $member_id);
		$request->setAttribute('mailmag_list_ary', $mailmag_list_ary);

		return View::SUCCESS;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::GET | Request::POST;
	}

	// ■Validator登録
	public function registerValidators($validatorManager) {
		$validatorManager->registerName('mailmag_mail_addr', true, 'メールアドレスが未入力です。');
	}

	// ■入力チェック
	public function validate() {
		$request = $this->getContext()->getRequest();

		$mail_addr = $request->getParameter('mailmag_mail_addr');
		$member_id = $this->db->getMemberIdFromMailaddr($mail_addr);

		switch ($this->getSubmitButton()) {
		case 'mailmag_regist_btn':
			if ($member_id) {
				if ($this->db->checkMelmagaMember($this->melmaga_id, $member_id)) {
					$request->setError('mailmag_mail_addr', 'メルマガは購読済みです。');
					return false;
				}
			} else {
				$request->setAttribute('mailmag_member_regist_flag', true);
				$request->setError('mailmag_mail_addr', 'メルマガを購読するためにはメンバー登録を行ってください。');
				return false;
			}
			break;
		case 'mailmag_release_btn':
			if (!$member_id || !$this->db->checkMelmagaMember($this->melmaga_id, $member_id)) {
				$request->setError('mailmag_mail_addr', '入力したメールアドレスはこのメルマガを購読していません。');
				return false;
			}
			break;
		}

		return true;
	}
}
?>