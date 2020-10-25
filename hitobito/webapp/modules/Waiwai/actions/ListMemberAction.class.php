<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 会議室メンバー一覧アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class ListMemberAction extends WaiwaiBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		$member_id = $request->getParameter('waiwai_member_id');

		switch ($this->getSubmitButton()) {
		case 'waiwai_to_admin_btn':		// 管理者にする
			$this->db->updateAdminFlag($this->room_id, $member_id, 1);
			break;
		case 'waiwai_to_member_btn':	// メンバーに戻す
			$this->db->updateAdminFlag($this->room_id, $member_id, 0);
			break;
		case 'waiwai_mail_send_btn':	// 配信
			$this->db->updateMailSendFlag($this->room_id, $member_id, 1);
			break;
		case 'waiwai_mail_stop_btn':	// 停止
			$this->db->updateMailSendFlag($this->room_id, $member_id, 0);
			break;
		case 'waiwai_regist_cancel_btn':// 登録解除
			$this->db->resignMember($this->room_id, $member_id);
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

	// ■入力データチェック
	public function validate() {
		$request = $this->getContext()->getRequest();

		if (!$request->getParameter('waiwai_member_id')) {
			switch ($request->getParameter('waiwai_list_kind')) {
			case 'admin':
				$request->setError('waiwai_member_id', '管理者が未選択です。');
				break;
			case 'member':
				$request->setError('waiwai_member_id', 'メンバーが未選択です。');
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
		$sort_ary = array(1 => 'regist_date DESC', 2 => 'mail_addr', 3 => 'nickname', 4 => 'remark_num', 5 => 'last_date', 6 => 'mail_send_flag DESC', 7 => 'regist_date');

		// 管理者リストソート順
		$admin_sort = $request->getParameter('waiwai_admin_sort');
		if (!$admin_sort)
			$admin_sort = $user->getAttribute('waiwai_admin_sort');
		if (!$admin_sort)
			$admin_sort = 1;
		$user->setAttribute('waiwai_admin_sort', $admin_sort);
		$request->setAttribute('waiwai_admin_sort', $admin_sort);

		// 管理者リスト設定
		$admin_list_ary = $this->db->getMemberList($this->room_id, 1, $sort_ary[$admin_sort]);
		$request->setAttribute('waiwai_admin_list_ary', $admin_list_ary);

		// メンバーリストソート順
		$member_sort = $request->getParameter('waiwai_member_sort');
		if (!$member_sort)
			$member_sort = $user->getAttribute('waiwai_member_sort');
		if (!$member_sort)
			$member_sort = 1;
		$user->setAttribute('waiwai_member_sort', $member_sort);
		$request->setAttribute('waiwai_member_sort', $member_sort);

		// メンバーリスト設定
		$member_list_ary = $this->db->getMemberList($this->room_id, 0, $sort_ary[$member_sort]);
		$request->setAttribute('waiwai_member_list_ary', $member_list_ary);
	}

	// ■認証を要求
    public function isSecure() {
        return true;
    }

	// ■メンバー情報設定
	private function setMemberData($member_id) {
		$request = $this->getContext()->getRequest();

		$member_data = $this->db->getMemberAttribute($member_id, $this->room_id);
		$request->setAttribute('waiwai_member_data', $member_data);
	}

	// ■ナビ編集ページ
	protected function isNaviEditPage() {
		return true;
	}
}
?>