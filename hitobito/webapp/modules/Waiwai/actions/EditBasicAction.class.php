<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 基本情報編集アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class EditBasicAction extends WaiwaiBaseAction {
	// ■アクション実行
	public function execute() {
		// 基本情報更新
		$basic = $this->getParameters();
		$this->db->updateBasicInfo($this->room_id, $this->getNaviMemberId(), $basic);

		return View::SUCCESS;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		// 基本情報設定
		$basic = $this->db->getBasicInfo($this->room_id);
		$basic['nickname'] = $this->db->getNickname($this->room_id, $this->getNaviMemberId());
		$request->setAttribute('waiwai_basic', $basic);

		return View::INPUT;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::POST;
	}

	// ■Validator登録
	public function registerValidators($validatorManager) {
		$validatorManager->registerName('waiwai_nickname', TRUE, 'ナビニックネームが未入力です。');
		$validatorManager->registerName('waiwai_name', TRUE, '会議室名が未入力です。');
//		$validatorManager->registerName('waiwai_room_code', TRUE, 'ML-NAMEが未入力です。');
		$validatorManager->registerName('waiwai_outline', TRUE, '会議室概要が未入力です。');
		$validatorManager->registerName('waiwai_status', TRUE, '公開設定が未選択です。');
		$validatorManager->registerName('waiwai_regist_mail_flag', TRUE, '入会メールが未選択です。');
		$validatorManager->registerName('waiwai_resign_mail_flag', TRUE, '退会メールが未選択です。');
		$validatorManager->registerName('waiwai_header_flag', TRUE, 'ヘッダが未選択です。');
		$validatorManager->registerName('waiwai_footer_flag', TRUE, 'フッタが未選択です。');
		$validatorManager->registerName('waiwai_guest_regist_notify_flag', TRUE, 'ゲスト登録通知が未選択です。');
		$validatorManager->registerName('waiwai_public_user_notify_flag', TRUE, '一般ユーザーからの投稿通知が未選択です。');
	}

/* ML-NAMEは編集できないように変更
	// ■入力データチェック
	public function validate() {
		$request = $this->getContext()->getRequest();

		$room_code = trim($request->getParameter('waiwai_room_code'));

		if (!preg_match('/^[0-9a-z_\-]+$/', $room_code)) {
			$request->setError('room_code', "ML-NAMEは半角小文字英数字で入力してください。");
			return false;
		}

		if ($this->db->checkRoomCode($this->room_id, $room_code)) {
			$request->setError('room_code', "入力されたML-NAMEはすでに使われています。");
			return false;
		}

		return true;
	}
*/

	// ■入力エラー処理
	public function handleError() {
		$request = $this->getContext()->getRequest();

		// 基本情報設定
		$basic = $this->getParameters();
		$request->setAttribute('waiwai_basic', $basic);

		return View::INPUT;
	}

	// ■パラメータ取得
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['nickname'] = $request->getParameter('waiwai_nickname');
		$params['name'] = $request->getParameter('waiwai_name');
//		$params['room_code'] = trim($request->getParameter('waiwai_room_code'));
		$params['outline'] = $request->getParameter('waiwai_outline');
		$params['status'] = $request->getParameter('waiwai_status');
		$params['regist_mail_flag'] = $request->getParameter('waiwai_regist_mail_flag');
		$params['resign_mail_flag'] = $request->getParameter('waiwai_resign_mail_flag');
		$params['header_flag'] = $request->getParameter('waiwai_header_flag');
		$params['footer_flag'] = $request->getParameter('waiwai_footer_flag');
		$params['guest_regist_notify_flag'] = $request->getParameter('waiwai_guest_regist_notify_flag');
		$params['public_user_notify_flag'] = $request->getParameter('waiwai_public_user_notify_flag');

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