<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ナビアンケート アンケート編集アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseAction.class.php');

class EditEnqueteAction extends EnqueteBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		$enquete_data = $this->getParameters();
		if (!$enquete_data['enquete_id']) {
			// 新規追加
			$enquete_id = $this->db->insertEnqueteData($this->navi_page_id, $enquete_data, $enquete_data['option']);
		} else {
			// 編集
			$enquete_id = $this->db->updateEnqueteData($enquete_data, $enquete_data['option']);
		}

		$request->setAttribute('enquete_id', $enquete_id);

		return View::SUCCESS;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		$enquete_id = $request->getParameter('enquete_id');
		if ($enquete_id) {
			// 編集
			$enquete_data = $this->db->getEnqueteData($enquete_id);
			$enquete_data['option'] = $this->db->getOptionData($enquete_id);
		} else {
			// 新規追加
			$enquete_data['question_type'] = 1;
			$enquete_data['comment_flag'] = 1;
			$enquete_data['trackback_flag'] = 1;
			$enquete_data['option'] = array();
			$enquete_data['start_date'] = date('Y-m-d H:i:s', time() + 24 * 60 * 60);
			$enquete_data['end_date'] = date('Y-m-d H:i:s', time() + 14 * 24 * 60 * 60);
			$enquete_data['open_flag'] = 1;
		}

		// アンケートデータ設定
		$request->setAttribute('enquete_data', $enquete_data);

		return View::INPUT;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::POST;
	}

	// ■Validator登録
	public function registerValidators($validatorManager) {
		$validatorManager->registerName('enquete_title', TRUE, 'タイトルが未入力です。');
		$validatorManager->registerName('enquete_question_text', TRUE, '設問文が未入力です。');
		$validatorManager->registerName('enquete_question_type', TRUE, '選択肢が未選択です。');
		$validatorManager->registerName('enquete_comment_flag', TRUE, 'コメントが未選択です。');
		$validatorManager->registerName('enquete_trackback_flag', TRUE, 'トラックバックが未選択です。');
	}

	// ■入力データチェック
	public function validate() {
		$request = $this->getContext()->getRequest();

		// 選択肢入力チェック
		$option_exist = false;
		foreach ($request->getParameter('enquete_option') as $option_text) {
			if ($option_text != '' ) {
				$option_exist = true;
				break;
			}
		}
		if (!$option_exist)
			$request->setError('enquete_option', '選択肢が未入力です。');

		// 公開日付チェック
		$start_date = $this->checkDate($this->getDateParameter('enquete_start_date'));
		if (!$start_date)
			$request->setError('enquete_start_date', '公開日付が不正です。');

		// 終了日付チェック
		$end_date = $this->checkDate($this->getDateParameter('enquete_end_date'));
		if (!$end_date)
			$request->setError('enquete_end_date', '終了日付が不正です。');

		if ($start_date) {
			// 公開日が未来であるかチェック
//			if ($start_date < time())
//				$request->setError('enquete_start_date', '公開日付を過ぎています。');

			// 公開日と終了日の関係チェック
			if ($end_date) {
				if ($start_date > $end_date)
					$request->setError('enquete_end_date', '終了日付が公開日付より前になっています。');
			}
		}

		return !$request->hasErrors();
	}

	// ■入力エラー処理
	public function handleError() {
		$request = $this->getContext()->getRequest();

		// アンケートデータ設定
		$enquete_data = $this->getParameters();
		$request->setAttribute('enquete_data', $enquete_data);

		return View::INPUT;
	}

	// ■パラメータ取得
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['enquete_id'] = $request->getParameter('enquete_id');
		$params['title'] = $request->getParameter('enquete_title');
		$params['question_text'] = $request->getParameter('enquete_question_text');
		$params['question_type'] = $request->getParameter('enquete_question_type');
		$params['comment_flag'] = $request->getParameter('enquete_comment_flag');
		$params['trackback_flag'] = $request->getParameter('enquete_trackback_flag');
		$params['start_date'] = $this->getDateParameter('enquete_start_date');
		$params['end_date'] = $this->getDateParameter('enquete_end_date');
		$params['open_flag'] = $request->getParameter('enquete_open_flag');

		// 選択肢項目
		$params['option'] = array();
		foreach ($request->getParameter('enquete_option') as $option_no => $option_text)
			$params['option'][] = array('option_no' => $option_no, 'option_text' => $option_text);

		return $params;
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