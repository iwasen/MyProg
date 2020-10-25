<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * メルマガ管理 代理解除アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseAction.class.php');

class DeleteMailMagMemberAction extends MailMagBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		$contents_id = $request->getParameter('mailmag_contents_id');
		$mail_addr = $request->getParameter('mailmag_mail_addr');

		$this->db->deleteMelmagaMember($this->melmaga_id, $mail_addr);

		return View::SUCCESS;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		$mail_addr = $request->getParameter('mailmag_mail_addr');
		$request->setAttribute('mail_addr', $mail_addr);


		return View::INPUT;

	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::POST;
	}

	// ■Validator登録
	public function registerValidators($validatorManager) {
		$request = $this->getContext()->getRequest();

		$validatorManager->registerName('check_mail_addr', $this->checkMailAddr($error_msg), $error_msg);
	}

	// ■入力エラー処理
	public function handleError() {
		return $this->getDefaultView();
	}

	// ■メールアドレスチェック
	public function checkMailAddr(&$msg) {
		$request = $this->getContext()->getRequest();

		$mail_addr = $request->getParameter('mailmag_mail_addr');
		if ($mail_addr == '') {
			$msg = 'メールアドレスが未入力です。';
			return true;
		} else {
			$msg = '入力したメールアドレスは登録されていません。';
			return !$this->db->checkMailAddr($this->melmaga_id, $mail_addr);
		}
	}

	// ■認証を要求
	public function isSecure() {
		return true;
	}

	// ■ナビ編集ページ
	protected function isNaviEditPage() {
		return true;
	}
}
?>