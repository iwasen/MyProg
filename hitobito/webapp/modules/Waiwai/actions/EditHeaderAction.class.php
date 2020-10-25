<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 メールヘッダ・フッタ編集アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class EditHeaderAction extends WaiwaiBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		switch ($this->getSubmitButton()) {
		case 'waiwai_update_btn':	// 登録情報更新
			// メールヘッダ・フッタ更新
			$header = $this->getParameters();
			$this->db->updateHeaderInfo($this->room_id, $header);
			break;
		case 'waiwai_preview_btn':	// プレビュー
			break;
		case 'waiwai_default_btn':	// デフォルトに戻す
			// メールヘッダ・フッタ設定
			$request->setAttribute('waiwai_header', $this->db->getDefaultHeader($this->room_id, $this->member_id));

			// 差し込み変数設定
			$request->setAttribute('waiwai_keyword', $this->getkeyword());
			return View::INPUT;
		}

		return View::SUCCESS;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		// メールヘッダ・フッタ設定
		$request->setAttribute('waiwai_header', $this->db->getHeaderInfo($this->room_id));

		// 差し込み変数設定
		$request->setAttribute('waiwai_keyword', $this->getkeyword());

		return View::INPUT;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::POST;
	}

	// ■Validator登録
	public function registerValidators($validatorManager) {
		switch ($this->getSubmitButton()) {
		case 'waiwai_update_btn':	// 登録情報更新
		case 'waiwai_preview_btn':	// プレビュー
			$validatorManager->registerName('waiwai_header', TRUE, 'ヘッダが未入力です。');
			$validatorManager->registerName('waiwai_footer', TRUE, 'フッタが未入力です。');
			break;
		}
	}

	// ■入力エラー処理
	public function handleError() {
		$request = $this->getContext()->getRequest();

		// メールヘッダ・フッタ設定
		$request->setAttribute('waiwai_header', $this->getParameters());

		// 差し込み変数設定
		$request->setAttribute('waiwai_keyword', $this->getkeyword());

		return View::INPUT;
	}

	// ■パラメータ取得
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['header'] = $request->getParameter('waiwai_header');
		$params['footer'] = $request->getParameter('waiwai_footer');

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