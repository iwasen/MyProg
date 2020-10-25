<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ナビアンケート アンケート結果表示アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseAction.class.php');
require_once MO_WEBAPP_DIR .'/modules/Navi/lib/notifyMail.class.php';

class ShowResultAction extends EnqueteBaseAction {
	// ■アクション実行
	public function execute() {
		$user = $this->getContext()->getUser();
		$request = $this->getContext()->getRequest();

		$enquete_id = $request->getParameter('enquete_id');

		$comment_set = false;

		switch ($this->getSubmitButton()) {
		case 'enquete_post_comment_btn':
			if ($user->getAttribute('enquete_comment_flag') == $request->getParameter('enquete_comment_flag')) {
				// コメント書き込み
				$ok = $this->db->insertComment($enquete_id, $request->getParameter('enquete_user_name'), $request->getParameter('enquete_comment'), $user->getAttribute('enquete_seq_no'));
				$user->removeAttribute('enquete_seq_no');

				if ($ok) {
					// 「コメント投稿ありがとうございました」を表示
					$request->setAttribute('enquete_comment_ok', true);

	                // コメント通知
					$enquete_data = $this->db->getEnqueteData($enquete_id);
	                $notifyValues = array(
	                'POSTER' => $request->getParameter('enquete_user_name'),
	                'BODY' => $request->getParameter('enquete_comment'),
					'ENTRY_TITLE' => $enquete_data['title'],
					'ENTRY_URL' => hitobito::getUrl() . "index.php?module=Enquete&action=ShowResult&enquete_id=$enquete_id"
	                );
	                $notifer = new NaviNotifyMail();
	                $notifer->notify(hitobito::getNaviPageInfo(), 'comment', $notifyValues);
				} else {
					// 「投稿に際し入力をご遠慮いただきたい言葉がはいっているため、ご投稿を反映できませんでした」を表示
					$request->setAttribute('enquete_comment_ng', true);
				}
			}
			break;
		case 'key_change_btn':
			$comment_set = true;
			break;
		}

		// アンケート結果設定
		$this->setEnqueteResult($comment_set);

		return View::INPUT;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		$enquete_id = $request->getParameter('enquete_id');

		if (!$this->db->checkEnqueteId($enquete_id, $this->member_id, '2,3')) {
			// アンケートIDが無ければ一覧表示へ
			$controller = $this->getContext()->getController();
			$controller->forward('Enquete', 'ListEnquete');
			return View::NONE;
		}

		// アンケート結果設定
		$this->setEnqueteResult(false);

		return View::INPUT;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::POST;
	}

	// ■Validator登録
	public function registerValidators($validatorManager) {
		$request = $this->getContext()->getRequest();

		if ($this->getSubmitButton() == 'enquete_post_comment_btn') {
			$validatorManager->registerName('enquete_user_name', TRUE, '名前を入力してください。');
			$validatorManager->registerName('enquete_comment', TRUE, 'コメントを入力してください。');
			$validatorManager->registerName('key_string', TRUE, '画像内の文字列を入力してください。');
		}
	}

	// ■入力データチェック
	public function validate() {
		$request = $this->getContext()->getRequest();

		if ($this->getSubmitButton() == 'enquete_post_comment_btn') {
			$key_string = $request->getParameter('key_string');
			if (strcasecmp(mb_convert_kana($key_string, 'a'), $_SESSION['captcha_keystring']) != 0) {
				$request->setError('key_string', '画像内の文字列が正しくありません。');
				return false;
			}
		}

		unset($_SESSION['captcha_keystring']);

		return true;
	}

	// ■入力エラー処理
	public function handleError() {
		// アンケート結果設定
		$this->setEnqueteResult(true);

		return View::INPUT;
	}

	// ■アンケート結果設定
	private function setEnqueteResult($comment_set) {
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		$enquete_id = $request->getParameter('enquete_id');
		if (!$enquete_id) {
			// アンケートIDが無ければ一覧表示へ
			$controller = $this->getContext()->getController();
			$controller->forward('Enquete', 'ListEnquete');
			return View::NONE;
		}

		// アンケート結果設定
		$enquete_data = $this->db->getEnqueteData($enquete_id);
		$enquete_data['option'] = $this->db->getAnswerOption($enquete_id);
		$enquete_data['answer_count'] = $this->db->getAnswerCount($enquete_id);
		$request->setAttribute('enquete_data', $enquete_data);

		// コメント情報設定
		if ($comment_set) {
			$enquete_comment['user_name'] = $request->getParameter('enquete_user_name');
			$enquete_comment['comment'] = $request->getParameter('enquete_comment');
		}
		$enquete_comment['log'] = $this->db->getCommentLog($enquete_id);
		$request->setAttribute('enquete_comment', $enquete_comment);

		// トラックバック情報設定
		$enquete_trackback['url'] = $this->db->getConst('user_page_url') . "?module=Enquete&action=ReceiveTrackback&enquete_id=$enquete_id";
		$enquete_trackback['log'] = $this->db->getTrackbackLog($enquete_id);
		$request->setAttribute('enquete_trackback', $enquete_trackback);

		// 多重投稿防止フラグ設定
		$time = time();
		$user->setAttribute('enquete_comment_flag', $time);
		$request->setAttribute('enquete_comment_flag', $time);
	}
}
?>