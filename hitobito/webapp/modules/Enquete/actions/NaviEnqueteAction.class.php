<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ナビアンケート アンケート編集一覧アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseAction.class.php');

class NaviEnqueteAction extends EnqueteBaseAction {
	// ■アクション実行
	public function execute() {
		$controller = $this->getContext()->getController();
		$request = $this->getContext()->getRequest();

		$params = $this->getParameters();
		switch ($params['submit']) {
		case 'enquete_new_btn':		// 新規追加
			$request->removeParameter('enquete_id');
			// no break
		case 'enquete_edit_btn':	// 編集
			$request->setMethod(REQUEST::GET);
			$controller->forward('Enquete', 'EditEnquete');
			return View::NONE;
		case 'enquete_up_btn':		// 上へ移動
			$this->db->moveEnquete($this->navi_page_id, $params['enquete_id'], true);
			break;
		case 'enquete_down_btn':	// 下へ移動
			$this->db->moveEnquete($this->navi_page_id, $params['enquete_id'], false);
			break;
		case 'enquete_delete_btn':	// 削除
			$this->db->deleteEnquete($params['enquete_id']);
			break;
		case 'enquete_csv_btn':		// CSV取得
			$request->setAttribute('enquete_room_code', $this->db->getRoomCode($params['enquete_id']));
			$request->setAttribute('enquete_room_mail_domain', $this->db->getConst('room_mail_domain'));
			$request->setAttribute('enquete_answer_list', $this->db->getAnswerList($params['enquete_id']));
			return 'Csv';
		}

		// アンケート一覧設定
		$this->setListData();

		return View::INPUT;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		// アンケート一覧設定
		$this->setListData();

		return View::INPUT;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::POST;
	}

	// ■Validator登録
	public function registerValidators($validatorManager) {
		$request = $this->getContext()->getRequest();

		$params = $this->getParameters();

		if (!$params['enquete_id']) {
			switch ($params['submit']) {
			case 'enquete_edit_btn':
			case 'enquete_up_btn':
			case 'enquete_down_btn':
			case 'enquete_delete_btn':
			case 'enquete_csv_btn':
				$validatorManager->registerName('enquete_id', TRUE, 'アンケートが未選択です。');
				break;
			}
		}
	}

	// ■入力エラー処理
	public function handleError() {
		// アンケート一覧設定
		$this->setListData();

		return View::INPUT;
	}

	// ■パラメータ取得
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['enquete_id'] = $request->getParameter('enquete_id');
		$params['submit'] = $this->getSubmitButton();

		return $params;
	}

	// ■リストデータ設定
	private function setListData() {
		$request = $this->getContext()->getRequest();

		$request->setAttribute('enquete_list_ary', $this->db->getEnqueteList($this->navi_page_id));
		$request->setAttribute('enquete_id', $request->getParameter('enquete_id'));
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