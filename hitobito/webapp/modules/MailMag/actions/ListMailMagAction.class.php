<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * メルマガ管理 配信メルマガ一覧表示アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseAction.class.php');

class ListMailMagAction extends MailMagBaseAction {
	// ■アクション実行
	public function execute() {
		$controller = $this->getContext()->getController();
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		$user->setAttribute('mailmag_current_contents_id', $request->getParameter('contents_id'));

		switch ($this->getSubmitButton()) {
		case 'mailmag_edit_btn':
			// メルマガ編集画面へ
			$request->setMethod(REQUEST::GET);
			$controller->forward('MailMag', 'EditContents');
			return View::NONE;
		case 'mailmag_show_click_btn':
			// クリック数表示
			$controller->forward('MailMag', 'ShowClick');
			return View::NONE;
		}

		return $this->getDefaultView();;
	}


	// ■デフォルトビュー表示
	public function getDefaultView() {
		$this->getContentsList();

		return View::SUCCESS;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::POST;
	}

	// ■Validator登録
	public function registerValidators($validatorManager) {
		$validatorManager->registerName('contents_id', TRUE, 'メルマガが未選択です。');
	}

	// ■入力エラー処理
	public function handleError() {
		return $this->getDefaultView();
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