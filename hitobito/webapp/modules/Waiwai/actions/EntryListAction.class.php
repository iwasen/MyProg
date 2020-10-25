<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 確認／解除リスト表示アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class EntryListAction extends WaiwaiBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		$submit = $this->getSubmitButton();
		switch($submit) {
		case 'waiwai_nickname_update_btn':
			$this->db->updateNickname($this->member_id, $request->getParameter('waiwai_nickname'));
			$request->setAttribute('waiwai_msg', 'ニックネームを更新しました。');
			break;
		default:
			if (preg_match('/^waiwai_cancel_([0-9]+)_btn$/', $submit, $matches)) {
				$this->db->deleteRoomMember($matches[1], $this->member_id);
				$request->setAttribute('waiwai_msg', 'ワイワイ会議室を解除しました。');
			} elseif (preg_match('/^waiwai_stop_([0-9]+)_btn$/', $submit, $matches)) {
				$this->db->updateMailSendFlag($matches[1], array($this->member_id), 0);
				$request->setAttribute('waiwai_msg', 'メール配信状態を停止に設定しました。');
			} elseif (preg_match('/^waiwai_send_([0-9]+)_btn$/', $submit, $matches)) {
				$this->db->updateMailSendFlag($matches[1], array($this->member_id), 1);
				$request->setAttribute('waiwai_msg', 'メール配信状態を配信に設定しました。');
			}
			break;
		}

		return $this->getDefaultView();
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		$entry_list_ary = $this->db->getEntryList($this->member_id);
		$request->setAttribute('waiwai_entry_list_ary', $entry_list_ary);
		$request->setAttribute('user_page_url', $this->db->getConst('user_page_url'));

		return View::SUCCESS;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::POST;
	}

	// ■入力データチェック
	public function validate() {
		$request = $this->getContext()->getRequest();

		$valid = true;

		$submit = $this->getSubmitButton();
		switch($submit) {
		case 'waiwai_nickname_update_btn':
			// ニックネーム重複チェック
			$nickname_ary = $request->getParameter('waiwai_nickname');
			if (is_array($nickname_ary)) {
				$err = false;
				foreach ($nickname_ary as $room_id => $nickname) {
					if ($this->db->checkNickname($room_id, $nickname, $this->member_id)) {
						$basic_info = $this->db->getBasicInfo($room_id);
						$request->setError("nockanme_$room_id", "ワイワイ会議室「{$basic_info['name']}」のニックネーム「{$nickname}」は他の方が使われているため変更できません。");
						$valid = false;
					}
				}
			}
			break;
		}

		return $valid;
	}

	// ■入力エラー処理
	public function handleError() {
		$request = $this->getContext()->getRequest();

		$entry_list_ary = $this->db->getEntryList($this->member_id);

		if (is_array($entry_list_ary)) {
			$nickname_ary = $request->getParameter('waiwai_nickname');
			foreach ($entry_list_ary as &$entry_list)
				$entry_list['nickname'] = $nickname_ary[$entry_list['room_id']];
		}

		$request->setAttribute('waiwai_entry_list_ary', $entry_list_ary);
		$request->setAttribute('user_page_url', $this->db->getConst('user_page_url'));

		return View::SUCCESS;
	}

	// ■認証を要求
	public function isSecure() {
		return true;
	}
}
?>