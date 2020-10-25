<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 返信アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class PostReplyAction extends WaiwaiBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		$post = $this->getParameters();

		// 元発言内容設定
		$this->setContents();

		// 新規発言を表示
		$request->setAttribute('waiwai_post', $post);

		switch ($this->getSubmitButton()) {
		case 'confirm_btn':			// 確認

			// 閲覧共通設定
			$this->setBrowseCommon();

			return 'Confirm';
		case 'contribute_btn':	// 送信
			if ($user->getAttribute('waiwai_post_flag')) {
				// 二重投稿防止フラグリセット
				$user->setAttribute('waiwai_post_flag', false);

				// 発言処理
				$this->postRemark($this->member_id, $this->db->getRemarkId($this->room_id, $post['seq_no']), $post['subject'], $post['content']);
			}

			// 閲覧共通設定
			$this->setBrowseCommon();

			return View::SUCCESS;
		case 'cancel_btn':		// 戻る
			return $this->getDefaultView();

		}

	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$user = $this->getContext()->getUser();

		// 閲覧共通設定
		$this->setBrowseCommon();

		// 発言内容設定
		$this->setContents();

		// 返信内容設定
		$this->setReply();

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

		$request = $this->getContext()->getRequest();
		$validatorManager->registerName('waiwai_id', TRUE, '返信する発言が未選択です。');
		$validatorManager->registerName('waiwai_subject', TRUE, 'SUBJECTが未入力です。');
		$validatorManager->registerName('waiwai_content', TRUE, '本文が未入力です。');
	}

	// ■入力チェック
	public function validate() {
		$request = $this->getContext()->getRequest();
		$original_content = $request->getParameter('waiwai_original_content');
		$back_flag = $request->getParameter('waiwai_back_flag');

		if ($original_content != '' && !$back_flag) {
			$input_content = $request->getParameter('waiwai_content');

			if (trim($original_content) == trim($input_content)) {
				$request->setError('waiwai_content', '本文が未編集です。');
				return false;
			}
		}

		return true;
	}

	// ■入力エラー処理
	public function handleError() {
		$request = $this->getContext()->getRequest();

		// 閲覧共通設定
		$this->setBrowseCommon();

		// 発言内容設定
		$this->setContents();

		// 返信内容設定
		$post = $this->getParameters();
		$request->setAttribute('waiwai_post', $post);

		return View::INPUT;
	}

	// ■パラメータ取得
	private function getParameters() {
		$request = $this->getContext()->getRequest();
		$params['seq_no'] = $request->getParameter('waiwai_id');
		$params['subject'] = $request->getParameter('waiwai_subject');
		$params['content'] = $request->getParameter('waiwai_content');
		$params['original_content'] = $request->getParameter('waiwai_original_content');
		$params['back_flag'] = $request->getParameter('waiwai_back_flag');
		return $params;
	}

	// ■認証を要求
    public function isSecure() {
        return true;
    }
}
?>