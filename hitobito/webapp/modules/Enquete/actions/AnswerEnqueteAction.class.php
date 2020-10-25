<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ナビアンケート アンケート回答アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseAction.class.php');

class AnswerEnqueteAction extends EnqueteBaseAction {
	// ■アクション実行
	public function execute() {
		$context = $this->getContext();
		$request = $context->getRequest();
		$user = $context->getUser();
		$controller = $context->getController();

		$enquete_id = $request->getParameter('enquete_id');
		if ($enquete_id) {
			if ($user->getAttribute('enquete_answer_flag')) {
				// 回答済み
				$user->setAttribute('enquete_answer_flag', false);

				// 回答保存
				$seq_no = $this->db->insertAnswer($enquete_id, $this->member_id, $request->getParameter('enquete_option'));

				// 回答シーケンス番号をセッションに保存（コメントと対応付けるため）
				$user->setAttribute('enquete_seq_no', $seq_no);

				// 「アンケート回答ありがとうございました」を表示
				$request->setAttribute('enquete_answer_ok', true);
			}

			// 結果表示
			$request->setMethod(REQUEST::GET);
			$controller->forward('Enquete', 'ShowResult');
			return View::NONE;
		}

		return View::INPUT;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$context = $this->getContext();
		$request = $context->getRequest();
		$user = $context->getUser();

		$enquete_id = $request->getParameter('enquete_id');
		if (!$this->db->checkEnqueteId($enquete_id, $this->member_id, 2)) {
			// アンケートIDが無ければ一覧表示へ
			$controller = $this->getContext()->getController();
			$controller->forward('Enquete', 'ListEnquete');
			return View::NONE;
		}

		// アンケート内容設定
		$enquete_data = $this->db->getEnqueteData($enquete_id);
		$enquete_data['option'] = $this->db->getOptionData($enquete_id);
		$request->setAttribute('enquete_data', $enquete_data);

		// 多重回答を防ぐためのフラグ設定
		$user->setAttribute('enquete_answer_flag', true);

		return View::INPUT;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		$context = $this->getContext();
		$request = $context->getRequest();
		$user = $context->getUser();

		// メルマガからクリックされた時の処理
		if ($request->getParameter('option_no') != '') {
			$enquete_option[] = $request->getParameter('option_no');
			$request->setParameter('enquete_option', $enquete_option);
			return Request::GET | Request::POST;
		}

		return Request::POST;
	}

	// ■入力データチェック
	public function validate() {
		$request = $this->getContext()->getRequest();

		// ひとつも選択肢のチェックが無ければエラー
		if ($request->getParameter('enquete_option') == null) {
			$request->setError('enquete_option', '回答が未入力です。');
			return false;
		}

		return true;
	}

	// ■入力エラー処理
	public function handleError() {
		return $this->getDefaultView();
	}
}
?>