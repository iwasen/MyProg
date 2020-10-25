<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 メンバーアンケートアクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class EnqueteAction extends WaiwaiBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		$post = $this->getParameters();

		switch ($this->getSubmitButton()) {
		case 'contribute_btn':

			// 回答保存
			$this->saveAnswer();

			return View::SUCCESS;
		case 'confirm_btn':		// 確認

			// 確認情報設定
			$this->setConfirm();

			return 'Confirm';
		case 'cancel_btn':		// 戻る

			// 回答設定
			$this->setQuestionAnswer();

			return View::INPUT;
		}

		return $this->getDefaultView();

	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$user = $this->getContext()->getUser();

		// アンケートID取得
		$enquete_id = $this->db->isEnqQuestion($this->room_id, $this->member_id);
		$user->setAttribute('waiwai_enquete_id', $enquete_id);

		// 設問設定
		$this->setQuestion();

		return View::INPUT;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::POST;
	}

	// ■Validator登録
	public function registerValidators($validatorManager) {
		$post = $this->getParameters();

		foreach($post as $data) {
			if ($data['type'] != 3) {
				$validatorManager->registerName("waiwai_ans-{$data['quest_no']}", true, 'Q'.$data['quest_no'].' が未回答です。');
			} else {
				$validatorManager->registerName("waiwai_free_ans-{$data['quest_no']}", true, 'Q'.$data['quest_no'].' が未回答です。');
			}
		}
	}

	// ■入力データチェック
	public function validate() {
		$request = $this->getContext()->getRequest();
		$post = $this->getParameters();

		foreach($post as $data) {
			if ($data['type'] != 3 && is_array($data['ans']) && in_array('999', $data['ans']) && !$data['free_text']) {
				$request->setError("enq_{$data['quest_no']}", 'Q'.$data['quest_no'].' 「その他」欄が未記入です。');
				return false;
			}
		}
		return true;
	}

	// ■入力エラー処理
	public function handleError() {
		$request = $this->getContext()->getRequest();

		if ($this->getSubmitButton() == 'clear_btn') {
			// 設問設定
			$this->setQuestion();
		} else {
			// 設問回答設定
			$this->setQuestionAnswer();
		}

		return View::INPUT;
	}

	// ■パラメータ取得
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$param['quest_no'] = array();
		$param['ans'] = array();
		$param['que_num'] = $request->getParameter('waiwai_question_no_ary');
		$param['title'] = $request->getParameter('waiwai_enq_title');

		for ($i = 1; $i <= count($param['que_num']); $i++) {
			$param['quest_no'] = $i;
			$param['type'] = $request->getParameter("waiwai_type-{$i}");
			$param['free_flag'] = $request->getParameter("waiwai_free_flag-{$i}");
			$param['ans'] = $request->getParameter("waiwai_ans-{$i}");
			$param['ans_FA'] = $request->getParameter("waiwai_free_ans-{$i}");
			$param['free_text'] = $request->getParameter("waiwai_ans-{$i}-f");
			$params[] = $param;
		}

		return $params;
	}

	// ■認証を要求
  public function isSecure() {
      return true;
  }

	// ■設問設定
	private function setQuestion() {
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		$enquete_id = $user->getAttribute('waiwai_enquete_id');

		if ($enquete_id) {
			$quest_data_ary = $this->db->getEnqQuestion($enquete_id);

			foreach ($quest_data_ary as $data) {
				$data['option'] = $this->db->getOptionText($enquete_id, $data['question_no']);

				$ary[] = $data;
			}

		}
		$request->setAttribute('waiwai_enq_ary', $ary);	
	}

	// ■設問/回答設定
	private function setQuestionAnswer() {

		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		$enquete_id = $user->getAttribute('waiwai_enquete_id');

		$post = $this->getParameters();

		if ($enquete_id) {
			$quest_data_ary = $this->db->getEnqQuestion($enquete_id);

			$i = 0;
			foreach ($quest_data_ary as $data) {
				$data['option'] = $this->db->getOptionText($enquete_id, $data['question_no']);
				$data['ans_free'] = $post[$i]['free_text'];

				$ans_ary = $post[$i]['ans'];

				if ($data['type'] != 3) {
					for ($j = 0; $j < count($data['option']); $j++) {

						if (is_array($ans_ary) && in_array($data['option'][$j]['option_no'], $ans_ary))
							$data['option'][$j]['checked'] = 'checked';
					}

				} else {
					$data['ans_free'] = $post[$i]['ans_FA'];
				}

			if (is_array($ans_ary) && in_array('999', $ans_ary))
				$data['free_checked'] = 'checked';

				$i++;
				$ary[] = $data;
			}

		}
		$request->setAttribute('waiwai_enq_ary', $ary);
	}

	// ■確認情報設定
	private function setConfirm() {
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		$enquete_id = $user->getAttribute('waiwai_enquete_id');

		$post = $this->getParameters();

		if ($enquete_id) {
			$quest_data_ary = $this->db->getEnqQuestion($enquete_id);

			$i = 0;
			foreach ($quest_data_ary as $data) {
				$qno = $data['question_no'];
				$data['ans'] = array();

				$data['ans'] = $post[$i]['ans'];

				if ($data['type'] != 3) {
					if ($data['ans'] && in_array('999', $data['ans']))
						$data['ans_free'] = $post[$i]['free_text'];
					$data['ans_text'] = $this->db->getAnswerText($enquete_id, $data['question_no'], $data['ans']);
				} else {
					$data['ans_text'][0]['option_text'] = $post[$i]['ans_FA'];
				}

				$i++;
				$ary[] = $data;
			}
		}
		$request->setAttribute('waiwai_enq_ary', $ary);

	}

	// ■結果保存
	private function saveAnswer() {
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		$post = $this->getParameters();
		$enquete_id = $user->getAttribute('waiwai_enquete_id');
		$member_id = $user->getAttribute('mbr_member_id');

		// 回答履歴、回答者数保存
		$this->db->saveEnqueteAnswer($post, $enquete_id, $member_id);

		// 会議室名、メンバー数取得
		$room_info = $this->db->getRoomOutline($this->room_id);

		$nickname = $this->db->getNickname($this->room_id, $this->member_id);
		$status = $this->db->getRoomMemberStatus($this->room_id, $this->member_id);

		$data['room_id'] = $request->getParameter('navipage_id');
		$data['room_name'] = $room_info['name'];
		$data['member_num'] = $room_info['member_num'];
		$data['nickname'] = $nickname;
		$data['status'] = $status;
		$data['title'] = $post[0]['title'];

		$request->setAttribute('waiwai_enq_data', $data);
	}
}
?>