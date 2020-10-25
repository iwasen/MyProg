<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 ゲスト登録メール編集アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class EditGuestMailAction extends WaiwaiBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		switch ($this->getSubmitButton()) {
		case 'waiwai_update_btn':	// 登録情報更新
			// ゲスト登録メール更新
			$guest_mail = $this->getParameters();
			$this->db->updateGuestMail($this->room_id, $guest_mail);
			break;
		case 'waiwai_preview_btn':	// プレビュー
			break;
		case 'waiwai_default_btn':	// デフォルトに戻す
			// ゲスト登録デフォルトメール設定
			$mail_template = $this->db->getMailTemplate('room_guest');
			$guest_mail['guest_mail'] = $mail_template['body'];
			$request->setAttribute('waiwai_guest_mail', $guest_mail);

			// 差し込み変数設定
			$request->setAttribute('waiwai_keyword', $this->getkeyword());
			return View::INPUT;
		}

		return View::SUCCESS;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		// ゲスト登録メール設定
		$request->setAttribute('waiwai_guest_mail', $this->db->getGuestMail($this->room_id, $this->member_id));

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
			$validatorManager->registerName('waiwai_guest_mail', TRUE, 'メール文が未入力です。');
			break;
		}
	}

	// ■入力エラー処理
	public function handleError() {
		$request = $this->getContext()->getRequest();

		// ゲスト登録メール設定
		$request->setAttribute('waiwai_guest_mail', $this->getParameters());

		// 差し込み変数設定
		$request->setAttribute('waiwai_keyword', $this->getkeyword());

		return View::INPUT;
	}

	// ■パラメータ取得
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['guest_mail'] = $request->getParameter('waiwai_guest_mail');

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