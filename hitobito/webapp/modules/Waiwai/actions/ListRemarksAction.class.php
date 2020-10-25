<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 ワイワイ会議最新投稿ブロック表示アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class ListRemarksAction extends WaiwaiBaseAction {
	// ■アクション実行
	public function execute() {
		return View::SUCCESS;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		// 先頭オフセット取得
		$list_offset = (int)$request->getParameter('list_offset');
		if ($list_offset < 0)
			$list_offset = 0;

		// ワイワイ会議最新投稿設定
		$list_ary = $this->db->getNewRemarks($this->channel_id, $this->room_id, 11, $this->member_id, $list_offset);
		$request->setAttribute('waiwai_list_ary', $list_ary);
		$request->setAttribute('waiwai_list_offset', $list_offset);

		return View::SUCCESS;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::NONE;
	}
}
?>