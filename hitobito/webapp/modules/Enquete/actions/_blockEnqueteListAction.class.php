<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ナビアンケート アンケート一覧表示アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseAction.class.php');

class _blockEnqueteListAction extends EnqueteBaseAction {
	// ■アクション実行
	public function execute() {
		return View::NONE;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		if ($this->navi_page_id) {
			// アンケート一覧取得
			$list_ary = $this->db->getOpenEnqueteList($this->channel_id, $this->navi_page_id, $this->member_id, 3, 0, '2,3');
			if ($list_ary) {
				$request->setAttribute('enquete_list_ary', $list_ary);
				return View::SUCCESS;
			}
		}

		return View::NONE;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::NONE;
	}
}
?>