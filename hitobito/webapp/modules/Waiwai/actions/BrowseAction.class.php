<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室閲覧アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class BrowseAction extends WaiwaiBaseAction {
	// ■初期化
	public function initialize($context) {
		if (!parent::initialize($context))
			return false;

		$this->checkRoomStatus();

		return true;
	}

	// ■アクション実行
	public function execute() {
		return View::NONE;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$controller = $this->getContext()->getController();
		$request = $this->getContext()->getRequest();

		// ナビIDチェック
		if (!$this->checkNaviPageId() || !$this->room_id) {
			$controller->forward('Navi', 'ShowNaviPage');
			return View::NONE;
		}

		$seq_no = $request->getParameter('waiwai_id');
		$view = $request->getParameter('view');

		// 投稿内容のみ表示
		if ($seq_no && !$view) {

			// 発言内容設定
			$this->setContents(true);

			// 前後発言リンク設定
			$this->setRemarkLink($seq_no);

		// 投稿リストのみ表示
		} else {

			// 閲覧共通設定
			$this->setBrowseCommon();
		}

		return View::SUCCESS;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::NONE;
	}

	// ■前後投稿remark_id 設定
	private function setRemarkLink($seq_no) {
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();
		$display_type = $user->getAttribute('waiwai_display_type');

		$remark_id = $this->db->getRemarkId($this->room_id, $seq_no);

		$remark_ary = array();

		// スレッド表示
		if ($display_type == 'thread')
			$this->getThreadRemarkID($remark_ary, $remark_id);

		// 投稿順表示
		else
			$this->getTimeRemarkID($remark_ary, $remark_id);

		$current = array_search($remark_id, $remark_ary);

		if (in_array($remark_id, $remark_ary)) {
			$links['befor_remark_id'] = $remark_ary[$current - 1];
			$links['next_remark_id'] = $remark_ary[$current + 1];
		}

		$request->setAttribute('waiwai_links', $links);

		// 現在のページ設定
		$page_size = $user->getAttribute('waiwai_page_size');
		$page_size =  $page_size ? $page_size : 50;
		$remark_order = $current + 1;
		$current_page = ceil($remark_order / $page_size);
		$user->setAttribute('waiwai_current_page', $current_page);

	}

	// ■スレッド順remark_id取得
	private function getThreadRemarkID(&$ary, $remark_id, $break_flag = false, $parent_remark_id = 0, $count = 0) {

		$rec_ary = $this->db->getChildrenList($this->room_id, $parent_remark_id);

		$nrow = count($rec_ary);
		for ($i = 0; $i < $nrow;$i++) {

			$rec = &$rec_ary[$i];

			if ($rec['open_flag'] == 1) {

				$ary[] = $rec['remark_id'];

				if ($break_flag)
					break;

				if ($remark_id == $rec['remark_id']) {
					$break_flag = true;
				}

				$count++;
			}

			if ($rec['child_num']) {
					$count = $this->getThreadRemarkID($ary, $remark_id, $break_flag, $rec['remark_id'], $count);
			}
		}
		return $count;
	}

	// ■投稿順remark_id取得
	protected function getTimeRemarkID (&$ary, $remark_id) {
		$rec_ary = $this->db->getTimeRemarkID($this->room_id);

		$break_flag = false;
		foreach ($rec_ary as $rec) {
				$ary[] = $rec['remark_id'];

			if ($break_flag)
				break;

			if ($remark_id == $rec['remark_id']) {
				$break_flag = true;
			}
		}
	}
	
}
?>