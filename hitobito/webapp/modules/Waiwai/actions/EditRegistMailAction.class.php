<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 入会者メール編集アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class EditRegistMailAction extends WaiwaiBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		switch ($this->getSubmitButton()) {
		case 'waiwai_update_btn':	// 登録情報更新
			// 入会者メール更新
			$regist_mail = $this->getParameters();
			$this->db->updateRegistMail($this->room_id, $regist_mail);
			break;
		case 'waiwai_preview_btn':	// プレビュー
			break;
		case 'waiwai_default_btn':	// デフォルトに戻す
			// 入会者デフォルトメール設定
			$mail_template = $this->db->getMailTemplate('room_regist');
			$regist_mail['regist_mail'] = $mail_template['body'];
			$request->setAttribute('waiwai_regist_mail', $regist_mail);

			// 差し込み変数設定
			$request->setAttribute('waiwai_keyword', $this->getkeyword());
			return View::INPUT;
		}

		return View::SUCCESS;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		// 入会者メール設定
		$request->setAttribute('waiwai_regist_mail', $this->db->getRegistMail($this->room_id, $this->member_id));

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
			$validatorManager->registerName('waiwai_regist_mail', TRUE, 'メール文が未入力です。');
			break;
		}
	}

	// ■入力エラー処理
	public function handleError() {
		$request = $this->getContext()->getRequest();

		// 入会者メール設定
		$request->setAttribute('waiwai_regist_mail', $this->getParameters());

		// 差し込み変数設定
		$request->setAttribute('waiwai_keyword', $this->getkeyword());

		return View::INPUT;
	}

	// ■パラメータ取得
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['regist_mail'] = $request->getParameter('waiwai_regist_mail');

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