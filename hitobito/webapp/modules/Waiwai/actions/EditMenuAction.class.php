<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 編集メニューアクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class EditMenuAction extends WaiwaiBaseAction {
	// ■アクション実行
	public function execute() {
		return View::NONE;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		// お知らせ設定
		$notice_flag['guest_member'] = $this->db->checkGuestMember($this->room_id);
		$notice_flag['public_post'] = $this->db->checkPublicPost($this->room_id);
		$request->setAttribute('waiwai_notice_flag', $notice_flag);

		return View::INPUT;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::NONE;
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