<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 発言編集アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class EditRemarkAction extends WaiwaiBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();
		$contents = $this->getParameters();

		// 閲覧共通設定
		$this->setBrowseCommon();

		// 編集内容設定
		$request->setAttribute('waiwai_contents', $contents);

		switch ($this->getSubmitButton()) {
		case 'contribute_btn':	// 送信

			// 発言編集処理
			$this->db->updateRemark($this->db->getRemarkId($this->room_id, $contents['seq_no']), $contents['subject'], $contents['content']);

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

		// 発言内容設定
		$this->setContents();

		return View::INPUT;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::POST;
	}

	// ■Validator登録
	public function registerValidators($validatorManager) {
		$validatorManager->registerName('waiwai_id', TRUE, '編集する発言が未選択です。');
		$validatorManager->registerName('waiwai_subject', TRUE, 'SUBJECTが未入力です。');
		$validatorManager->registerName('waiwai_content', TRUE, '本文が未入力です。');
	}

	// ■入力エラー処理
	public function handleError() {
		$request = $this->getContext()->getRequest();

		// 閲覧共通設定
		$this->setBrowseCommon();

		// 編集内容設定
		$contents = $this->getParameters();
		$request->setAttribute('waiwai_contents', $contents);

		return View::INPUT;
	}

	// ■パラメータ取得
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['seq_no'] = $request->getParameter('waiwai_id');
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