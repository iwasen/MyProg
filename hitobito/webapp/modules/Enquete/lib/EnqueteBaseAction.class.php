<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ナビアンケート ビュー基本クラス
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteDatabase.class.php');

abstract class EnqueteBaseAction extends Action {
	protected $db;
	protected $channel_id;
	protected $navi_page_id;
	protected $member_id;

	// ■初期化
	public function initialize($context) {
		parent::initialize($context);

		$user = $context->getUser();

		// ナビページID正当性チェック
		if ($this->isNaviEditPage())
			Navi::GuardNaviEdit($user);

		// データベース接続
		$this->db = new EnqueteDatabase();

		// ナビページID取得
		$request = $context->getRequest();
		$this->navi_page_id = $request->getParameter('navipage_id');
		$request->setAttribute('navi_page_id', $this->navi_page_id);

		// チャネルID取得
		$this->channel_id = $request->getParameter('category_id');
		$request->setAttribute('channel_id', $this->channel_id);

		// メンバーID取得
		$this->member_id = $user->isAuthenticated() ? $user->getAttribute('mbr_member_id') : null;

		return true;
	}

	// ■ナビのメンバーID取得
	protected function getNaviMemberId() {
		return $this->db->getMemberIdFromNaviPageId($this->navi_page_id);
	}

	// ■submitボタンチェック
	protected function getSubmitButton() {
		$request = $this->getContext()->getRequest();

		// 名前が"_btn_x"で終わっている場合はsubmitボタンとみなす
		$buttons = $request->getParameterNames();
		if (is_array($buttons)) {
			foreach ($buttons as $button) {
				if (preg_match('/^(.+_btn)_x$/', $button, $matches))
					return $matches[1];
			}
		}

		return null;
	}

	// ■日付のパラメータを取得
	protected function getDateParameter($name) {
		$request = $this->getContext()->getRequest();

		return $request->getParameter($name . '_year') . '-' . $request->getParameter($name . '_month') . '-' . $request->getParameter($name . '_day');
	}

	// ■日付の正当性チェック
	protected function checkDate($date) {
		$time = strtotime($date);

		sscanf($date, "%d-%d-%d", &$y, &$m, &$d);

		if ($y != date('Y', $time) || $m != date('n', $time) || $d != date('j', $time))
			return 0;

		return $time;
	}

	// ■ナビ編集ページ
	protected function isNaviEditPage() {
		return false;
	}
}
?>