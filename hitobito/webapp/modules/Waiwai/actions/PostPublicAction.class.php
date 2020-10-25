<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 一般ユーザーからの投稿アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class PostPublicAction extends WaiwaiBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		$seq_no = $request->getParameter('waiwai_seq_no');
		$waiwai_public = $this->db->getPublicContents($this->room_id, $seq_no);

		switch ($this->getSubmitButton()) {
		case 'waiwai_public_post_btn':		// 投稿する
			$waiwai_public = $this->db->getPublicContents($this->room_id, $seq_no);
			$this->postRemark(0, 0, $waiwai_public['title'], $waiwai_public['content'], $waiwai_public['nickname']);
			$this->db->updatePublicStatus($this->room_id, $seq_no, 2);
			$waiwai_public['msg'] = '投稿しました。';
			break;
		case 'waiwai_public_refuse_btn':	// お断りする
			$this->db->updatePublicStatus($this->room_id, $seq_no, 4);
			$this->sendRefuseMail($this->room_id, $seq_no);
			$waiwai_public['msg'] = 'お断りしました。';
			break;
		case 'waiwai_public_noappr_btn':	// 非承認
			$this->db->updatePublicStatus($this->room_id, $seq_no, 3);
			$waiwai_public['msg'] = '非承認にしました。';
			break;
		case 'waiwai_public_delete_btn':	// 削除
			$this->db->deletePublic($this->room_id, $seq_no);
			$waiwai_public['msg'] = '削除しました。';
			break;
		}

		$request->setAttribute('waiwai_public', $waiwai_public);

		return View::SUCCESS;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		$seq_no = $request->getParameter('waiwai_seq_no');
		if ($seq_no) {
			$waiwai_public = $this->db->getPublicContents($this->room_id, $seq_no);
			$request->setAttribute('waiwai_public', $waiwai_public);
		}

		return View::INPUT;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::POST;
	}

	// ■Validator登録
	public function registerValidators($validatorManager) {
		$validatorManager->registerName('waiwai_seq_no', TRUE, '投稿番号が指定されていません。');
	}

	// ■入力エラー処理
	public function handleError() {
		return $this->getDefaultView();
	}

	// ■お断りメール送信
	private function sendRefuseMail($room_id, $seq_no) {
		$public_contents = $this->db->getPublicContents($room_id, $seq_no);

		// メールテンプレート取得
		$mail = $this->db->getMailTemplate('room_post_ng');

		// 差し込み変数処理
		$var_ary = $this->getRoomVars($room_id);
		$mail['body'] = $this->replaceRoomVars($var_ary, $mail['body']);
		$mail['body'] = str_replace('%MAIL_ADDR%', $public_contents['mail_addr'], $mail['body']);
		$mail['body'] = str_replace('%NICKNAME%', $public_contents['nickname'], $mail['body']);
		$mail['body'] = str_replace('%TITE%', $public_contents['title'], $mail['body']);
		$mail['body'] = str_replace('%CONTENT%', $public_contents['content'], $mail['body']);

		$send_mail_id = $this->db->setSendMailData($mail['subject'], $mail['from'], $mail['reply_to'], $mail['body']);
		$this->db->setSendMailList($send_mail_id, $public_contents['mail_addr']);
		$this->db->setSendMailOk($send_mail_id);
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