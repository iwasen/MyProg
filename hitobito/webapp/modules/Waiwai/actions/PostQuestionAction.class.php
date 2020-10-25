<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 一般からの投稿アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class PostQuestionAction extends WaiwaiBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		$post = $this->getParameters();
		if ($user->getAttribute('waiwai_post_flag')) {
			// 二重投稿防止フラグリセット
			$user->setAttribute('waiwai_post_flag', false);

			// 発言処理
			$this->db->insertRoomPublic($this->room_id, $post);

			// ナビへメールで通知
			$this->sendNotifyMail($this->room_id, $post);
		}

		// 新規発言を表示
		$request->setAttribute('waiwai_post', $post);

		return View::SUCCESS;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$user = $this->getContext()->getUser();

		// 二重投稿防止フラグセット
		$user->setAttribute('waiwai_post_flag', true);

		return View::INPUT;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::POST;
	}

	// ■Validator登録
	public function registerValidators($validatorManager) {
		$validatorManager->registerName('waiwai_nickname', true, 'ニックネームが未入力です。');
		$validatorManager->registerName('waiwai_mail_addr', true, 'メールアドレスが未入力です。');
		$validatorManager->registerName('waiwai_title', true, 'タイトルが未入力です。');
		$validatorManager->registerName('waiwai_content', true, '本文が未入力です。');
	}

	// ■入力データチェック
	public function validate() {
		$request = $this->getContext()->getRequest();

		$params = $this->getParameters();

		if (!$this->checkMailaddr($params['mail_addr'])) {
			$request->setError('room_code', "メールアドレスが有効ではありません。");
			return false;
		}

		return true;
	}

	// ■入力エラー処理
	public function handleError() {
		$request = $this->getContext()->getRequest();

		// 発言内容設定
		$post = $this->getParameters();
		$request->setAttribute('waiwai_post', $post);

		return View::INPUT;
	}

	// ■パラメータ取得
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['nickname'] = $request->getParameter('waiwai_nickname');
		$params['mail_addr'] = $request->getParameter('waiwai_mail_addr');
		$params['title'] = $request->getParameter('waiwai_title');
		$params['content'] = $request->getParameter('waiwai_content');

		return $params;
	}

	// ■ナビへメールで通知
	private function sendNotifyMail($room_id, &$post) {
		$basic_info = $this->db->getBasicInfo($room_id);
		if ($basic_info['public_user_notify_flag']) {
			$mail_addr = $this->db->getNaviMailaddr($this->navi_page_id);
			if ($mail_addr != '') {
				$template = $this->db->getMailTemplate('room_public');
				$body = $template['body'];
				$user_page_url = $this->db->getConst('user_page_url');

				$body = str_replace('%ROOM_NAME%', $basic_info['name'], $body);
				$body = str_replace('%SUBJECT%', $post['title'], $body);
				$body = str_replace('%NICKNAME%', $post['nickname'], $body);
				$body = str_replace('%BODY%', $post['content'], $body);
				$body = str_replace('%NAVIEDIT_URL%', "${user_page_url}index.php?module=Waiwai&action=ListPublic&navipage_id=$this->navi_page_id", $body);

				$send_mail_id = $this->db->setSendMailData($template['subject'], $template['from'], $template['reply_to'], $body);
				$this->db->setSendMailList($send_mail_id, $mail_addr);
				$this->db->setSendMailOk($send_mail_id);
			}
		}
	}
}
?>