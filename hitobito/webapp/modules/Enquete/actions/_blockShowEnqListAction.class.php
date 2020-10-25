<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ナビアンケート 実施中アンケート表示アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseAction.class.php');

class _blockShowEnqListAction extends EnqueteBaseAction {
	// ■アクション実行
	public function execute() {
		return View::SUCCESS;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		// 実施中アンケート一覧設定
		$list_ary = $this->db->getOpenEnqueteList($this->channel_id, $this->navi_page_id, $this->member_id, 3, 0, 2);
		if ($list_ary) {
			$request->setAttribute('enquete_list_ary', $list_ary);

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