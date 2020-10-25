<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ナビアンケート 全アンケート一覧表示アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseAction.class.php');

class ListEnqueteAction extends EnqueteBaseAction {
	// ■アクション実行（このmethodが実行されることは無い）
	public function execute() {
		return View::INPUT;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$this->setListData();

		return View::INPUT;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::NONE;
	}

	// ■入力エラー処理
	public function handleError() {
		return getDefaultView();
	}

	// ■リストデータ設定
	private function setListData() {
		$request = $this->getContext()->getRequest();

		// ページサイズ
		$page_size = 8;

		// アンケート数取得
		$enquete_count = $this->db->getOpenEnqueteCount($this->channel_id, $this->navi_page_id, $this->member_id);

		// ページ数
		$max_page = (int)(($enquete_count + $page_size - 1) / $page_size);
		if ($max_page == 0)
			$max_page = 1;

		// ページ番号取得
		$current_page = $request->getParameter('enquete_page_no');
		if (!$current_page)
			$current_page = 1;

		// 前ページ／次ページ処理
		if ($request->getParameter('enquete_page_move') != '')
			$current_page += $request->getParameter('enquete_page_move');

		// ページ情報設定
		$enquete_page['max_page'] = $max_page;
		$enquete_page['current_page'] = $current_page;
		$request->setAttribute('enquete_page', $enquete_page);

		// アンケート一覧取得
		$list_ary = $this->db->getOpenEnqueteList($this->channel_id, $this->navi_page_id, $this->member_id, $page_size, ($current_page - 1) * $page_size, '2,3');

		// 最新２件の結果を設定
		if ($current_page == 1 && is_array($list_ary)) {
			foreach ($list_ary as $i => &$list) {
				$enquete_id = $list['enquete_id'];

				// アンケート結果設定
				$list['option'] = $this->db->getAnswerOption($enquete_id);
				$list['answer_count'] = $this->db->getAnswerCount($enquete_id);

				if ($i >= 1)
					break;
			}
		}

		// アンケート一覧設定
		$request->setAttribute('enquete_list_ary', $list_ary);
	}
}
?>