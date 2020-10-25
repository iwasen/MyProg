<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 新規投稿アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class PostRemarkAction extends WaiwaiBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		$post = $this->getParameters();

		// 閲覧共通設定
		$this->setBrowseCommon();

		// 新規発言を表示
		$request->setAttribute('waiwai_post', $post);

		switch ($this->getSubmitButton()) {
		case 'contribute_btn':
			// 発言処理
			$this->postRemark($this->member_id, 0, $post['subject'], $post['content']);
			return View::SUCCESS;
		case 'confirm_btn':		// 確認
			return 'Confirm';
		case 'cancel_btn':		// 戻る
			return View::INPUT;
		}

	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		// 閲覧共通設定
		$this->setBrowseCommon();

		return View::INPUT;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::POST;
	}

	// ■Validator登録
	public function registerValidators($validatorManager) {
		$validatorManager->registerName('waiwai_subject', TRUE, 'SUBJECTが未入力です。');
		$validatorManager->registerName('waiwai_content', TRUE, '本文が未入力です。');
	}

	// ■入力エラー処理
	public function handleError() {
		$request = $this->getContext()->getRequest();

		// 閲覧共通設定
		$this->setBrowseCommon();

		// 発言内容設定
		$post = $this->getParameters();
		$request->setAttribute('waiwai_post', $post);

		return View::INPUT;
	}

	// ■パラメータ取得
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['subject'] = $request->getParameter('waiwai_subject');
		$params['content'] = $request->getParameter('waiwai_content');

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