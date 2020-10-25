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

class _blockNewRemarksAction extends WaiwaiBaseAction {
	// ■アクション実行
	public function execute() {
		return View::SUCCESS;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		// ワイワイ会議最新投稿設定
		$disp_num = $this->db->getConst('max_new_remarks');
		$list_ary = $this->db->getNewRemarks($this->channel_id, $this->room_id, $disp_num, $this->member_id);
		if ($list_ary) {
			$request->setAttribute('waiwai_list_ary', $list_ary);

			return View::SUCCESS;
		}

		return View::NONE;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::NONE;
	}
}
?>