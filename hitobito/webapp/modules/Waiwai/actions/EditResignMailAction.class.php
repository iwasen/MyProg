<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 退会者メール編集アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class EditResignMailAction extends WaiwaiBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		switch ($this->getSubmitButton()) {
		case 'waiwai_update_btn':	// 登録情報更新
			// 退会者メール更新
			$resign_mail = $this->getParameters();
			$this->db->updateResignMail($this->room_id, $resign_mail);
			break;
		case 'waiwai_preview_btn':	// プレビュー
			break;
		case 'waiwai_default_btn':	// デフォルトに戻す
			// 退会者デフォルトメール設定
			$mail_template = $this->db->getMailTemplate('room_resign');
			$resign_mail['resign_mail'] = $mail_template['body'];
			$request->setAttribute('waiwai_resign_mail', $resign_mail);

			// 差し込み変数設定
			$request->setAttribute('waiwai_keyword', $this->getkeyword());
			return View::INPUT;
		}

		return View::SUCCESS;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		// 退会者メール設定
		$resign_mail = $this->db->getResignMail($this->room_id, $this->member_id);
		$request->setAttribute('waiwai_resign_mail', $resign_mail);

		// 差し込み変数設定
		$keyword = $this->getkeyword();
		$request->setAttribute('waiwai_keyword', $keyword);

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
			$validatorManager->registerName('waiwai_resign_mail', TRUE, 'メール文が未入力です。');
			break;
		}
	}

	// ■入力エラー処理
	public function handleError() {
		$request = $this->getContext()->getRequest();

		// 退会者メール設定
		$resign_mail = $this->getParameters();
		$request->setAttribute('waiwai_resign_mail', $resign_mail);

		// 差し込み変数設定
		$keyword = $this->getkeyword();
		$request->setAttribute('waiwai_keyword', $keyword);

		return View::INPUT;
	}

	// ■パラメータ取得
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['resign_mail'] = $request->getParameter('waiwai_resign_mail');

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