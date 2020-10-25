<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * メルマガ管理 バックナンバーコンテンツ表示アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseAction.class.php');
require_once MO_WEBAPP_DIR .'/modules/Navi/lib/notifyMail.class.php';

class BacknumberContentsAction extends MailMagBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		$contents_id = $request->getParameter('contents_id');
		$user_name = $request->getParameter('user_name');
		$comment = $request->getParameter('comment');

		$comment_set = false;

		switch ($this->getSubmitButton()) {
		case 'post_comment_btn':
			if ($user->getAttribute('mailmag_comment_time') == $request->getParameter('mailmag_comment_time')) {
				// コメント書き込み
				if ($this->db->insertComment($contents_id, $user_name, $comment)) {
					// 「コメント投稿ありがとうございました」を表示
					$request->setAttribute('mailmag_comment_ok', true);

		            // コメント通知
					$mailmag_contents = $this->db->getMailMagContents($contents_id);
		            $notifyValues = array(
		            'POSTER' => $user_name,
		            'BODY' => $comment,
					'ENTRY_TITLE' => $mailmag_contents['subject'],
					'ENTRY_URL' => hitobito::getUrl() . "index.php?module=MailMag&action=BacknumberContents&contents_id=$contents_id"
		            );
		            $notifer = new NaviNotifyMail();
		            $notifer->notify(hitobito::getNaviPageInfo(), 'comment', $notifyValues);
				} else {
					// 「投稿に際し入力をご遠慮いただきたい言葉がはいっているため、ご投稿を反映できませんでした」を表示
					$request->setAttribute('mailmag_comment_ng', true);
				}
			}
			break;
		case 'key_change_btn':
			$comment_set = true;
			break;
		}

		return $this->setBacknumberContents(false, $comment_set);
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		return $this->setBacknumberContents(true, false);
	}

	// ■バックナンバーコンテンツ設定
	private function setBacknumberContents($page_view_count_flag, $comment_set) {
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		$contents_id = $request->getParameter('contents_id');

		if ($this->melmaga_id) {
			$mailmag = $this->db->getBacknumberInfo($this->melmaga_id);
			if ($mailmag['status'] == 1) {
				if ($mailmag['backnumber'] == 1 || $mailmag['backnumber'] == 3) {
					$mailmag['contents'] = $this->db->getBacknumberContents($contents_id);
					$mailmag['comment_ary'] = $this->db->getBacknumberComment($contents_id);
					$mailmag['trackback_ary'] = $this->db->getBacknumberTrackback($contents_id);
					$mailmag['trackback_url'] = $this->db->getConst('user_page_url') . "?module=MailMag&action=ReceiveTrackback&contents_id=$contents_id";

					// ページビューカウント
					if ($page_view_count_flag)
						$this->db->countPageView($mailmag['contents']['pv_id']);
				}
			}

			// コメント情報設定
			if ($comment_set) {
				$mailmag['user_name'] = $request->getParameter('user_name');
				$mailmag['comment'] = $request->getParameter('comment');
			}

			$request->setAttribute('mailmag', $mailmag);
		}

		// 多重投稿を防止フラグ設定
		$time = time();
		$user->setAttribute('mailmag_comment_time', $time);
		$request->setAttribute('mailmag_comment_time', $time);

		return View::SUCCESS;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::POST;
	}

	// ■入力エラー処理
	public function handleError() {
		return $this->setBacknumberContents(false, true);
	}

	// ■Validator登録
	public function registerValidators($validatorManager) {
		if ($this->getSubmitButton() == 'post_comment_btn') {
			$validatorManager->registerName('user_name', TRUE, '名前を入力してください。');
			$validatorManager->registerName('comment', TRUE, 'コメントを入力してください。');
			$validatorManager->registerName('key_string', TRUE, '画像内の文字列を入力してください。');
		}
	}

	// ■入力データチェック
	public function validate() {
		$request = $this->getContext()->getRequest();

		if ($this->getSubmitButton() == 'post_comment_btn') {
			$key_string = $request->getParameter('key_string');
			if (strcasecmp(mb_convert_kana($key_string, 'a'), $_SESSION['captcha_keystring']) != 0) {
				$request->setError('key_string', '画像内の文字列が正しくありません。');
				return false;
			}
		}

		unset($_SESSION['captcha_keystring']);

		return true;
	}
}
?>