<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * メルマガ管理 基本設定編集アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseAction.class.php');

class SettingMailMagAction extends MailMagBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		$contents_id = $request->getParameter('mailmag_contents_id');
		$basic = $this->getParameters();

		$this->db->updateBasicInfo($this->melmaga_id, $basic);

		return View::SUCCESS;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		$this->getMelmagaBasicInfo();

		return View::INPUT;

	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::POST;
	}

	// ■Validator登録
	public function registerValidators($validatorManager) {
		$validatorManager->registerName('mailmag_name', TRUE, 'メルマガタイトルが未入力です。');
		$validatorManager->registerName('mailmag_intro', TRUE, 'メルマガ紹介文が未入力です。');
	}

	// ■入力エラー処理
	public function handleError() {
		return $this->getDefaultView();
	}

	// ■パラメータ取得
	protected function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['melmaga_name'] = $request->getParameter('mailmag_name');
		$params['melmaga_intro'] = $request->getParameter('mailmag_intro');
		$params['melmaga_footer'] = $request->getParameter('mailmag_footer');
		$params['backnumber'] = $request->getParameter('mailmag_backnumber');
		$params['notify_mail'] = $request->getParameter('mailmag_notify_mail');
		$params['status'] = $request->getParameter('mailmag_status');

		return $params;
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