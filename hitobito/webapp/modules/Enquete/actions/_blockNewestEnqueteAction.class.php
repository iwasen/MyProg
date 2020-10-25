<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ナビアンケート 最新アンケート表示アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseAction.class.php');

class _blockNewestEnqueteAction extends EnqueteBaseAction {
	// ■アクション実行
	public function execute() {
		return View::NONE;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		if ($this->navi_page_id) {
			// 最新アンケートID取得
			$enquete_id = $this->db->getNewestEnquete($this->navi_page_id, $this->member_id);

			// アンケート結果設定
			if ($enquete_id) {
				$enquete_data = $this->db->getEnqueteData($enquete_id);
				$enquete_data['option'] = $this->db->getAnswerOption($enquete_id);
				$enquete_data['answer_count'] = $this->db->getAnswerCount($enquete_id);
				$request->setAttribute('enquete_data', $enquete_data);

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