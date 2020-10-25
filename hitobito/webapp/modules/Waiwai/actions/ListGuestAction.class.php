<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 会議室ゲストメンバー一覧アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class ListGuestAction extends WaiwaiBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		$member_id = $request->getParameter('waiwai_member_id');

		switch ($this->getSubmitButton()) {
		case 'waiwai_admit_btn':		// 承認
			$this->db->registMember($this->room_id, $member_id);
			$this->sendRegistMail($this->room_id, $member_id);
			break;
		case 'waiwai_mail_send_btn':	// 配信
			$this->db->updateMailSendFlag($this->room_id, $member_id, 1);
			break;
		case 'waiwai_mail_stop_btn':	// 停止
			$this->db->updateMailSendFlag($this->room_id, $member_id, 0);
			break;
		case 'waiwai_regist_cancel_btn':// 登録解除
			$this->db->deleteMember($this->room_id, $member_id);
			break;
		}

		$this->setListData();

		return View::INPUT;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();
		$member_id = $request->getParameter('member_id');

		if ($member_id)
			$this->setMemberData($member_id);
		else
			$this->setListData();

		return View::INPUT;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::POST;
	}

	// ■入力チェック
	public function validate() {
		$request = $this->getContext()->getRequest();

		if (!$request->getParameter('waiwai_member_id')) {
			switch ($request->getParameter('waiwai_list_kind')) {
			case 'guest':
				$request->setError('waiwai_member_id', 'ゲストメンバーが未選択です。');
				break;
			}
			return false;
		}

		return true;
	}

	// ■入力エラー処理
	public function handleError() {
		$this->setListData();

		return View::INPUT;
	}

	// ■リストデータ設定
	private function setListData() {
		$context = $this->getContext();
		$request = $context->getRequest();
		$user = $context->getUser();
		$sort_ary = array(1 => 'regist_date DESC', 2 => 'mail_addr', 3 => 'nickname', 4 => 'mail_send_flag DESC', 5 => 'regist_date');

		// ゲストメンバーリストソート順
		$guest_sort = $request->getParameter('waiwai_guest_sort');
		if (!$guest_sort)
			$guest_sort = $user->getAttribute('waiwai_guest_sort');
		if (!$guest_sort)
			$guest_sort = 1;
		$user->setAttribute('waiwai_guest_sort', $guest_sort);
		$request->setAttribute('waiwai_guest_sort', $guest_sort);

		// ゲストメンバーリスト設定
		$guest_list_ary = $this->db->getguestList($this->room_id, $sort_ary[$guest_sort]);
		$request->setAttribute('waiwai_guest_list_ary', $guest_list_ary);
	}

	// ■入会メール送信
	private function sendRegistMail($room_id, $member_id) {
		// 会議室情報取得
		$basic_info = $this->db->getBasicInfo($room_id);
		if (!$basic_info['regist_mail_flag'])
			return;

		// メールテンプレート取得
		$mail_template = $this->db->getMailTemplate('room_regist');

		// 入会者メール取得
		$regist_mail = $this->db->getRegistMail($room_id);

		// 差し込み変数処理
		$var_ary = $this->getRoomVars($room_id);
		$mail_template['body'] = $this->replaceRoomVars($var_ary, $regist_mail['regist_mail'] != '' ? $regist_mail['regist_mail'] : $mail_template['body']);

		// 入会メール送信
		$this->sendMailTemplate($room_id, $member_id, $mail_template);
	}

	// ■メールテンプレート送信
	private function sendMailTemplate($room_id, $member_id_ary, $mail) {
		// 送信メールデータセット
		$send_mail_id = $this->db->setSendMailData($mail['subject'], $mail['from'], $mail['reply_to'], $mail['body']);

		if (is_array($member_id_ary)) {
			foreach ($member_id_ary as $member_id) {
				// 配信先メンバー情報取得
				$member_info = $this->db->getMemberInfo($room_id, $member_id);
				if ($member_info) {
					// 配信先リスト設定
					$this->db->setSendMailList($send_mail_id, $member_info['mail_addr']);
				}
			}
		}

		$this->db->setSendMailOk($send_mail_id);
	}

	// ■メンバー情報設定
	private function setMemberData($member_id) {
		$request = $this->getContext()->getRequest();

		$member_data = $this->db->getMemberAttribute($member_id, $this->room_id);
		$request->setAttribute('waiwai_member_data', $member_data);
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