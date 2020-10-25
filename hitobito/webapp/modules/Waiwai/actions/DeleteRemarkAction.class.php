<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 発言削除アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class DeleteRemarkAction extends WaiwaiBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		// 発言内容設定
		$this->setContents();

		switch ($this->getSubmitButton()) {
		case 'delete_btn':		// 削除

			// 発言削除処理
			$remark_id = $this->db->getRemarkId($this->room_id, $request->getParameter('waiwai_id'));
			$this->db->deleteRemark($remark_id);

			// 閲覧共通設定
			$this->setBrowseCommon();

			return View::SUCCESS;

		case 'confirm_btn':		// 確認

			// 閲覧共通設定
			$this->setBrowseCommon();

			return 'Confirm';

		case 'cancel_btn':		// 戻る
			return $this->getDefaultView();
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
		$validatorManager->registerName('waiwai_id', TRUE, '削除する発言が未選択です。');
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