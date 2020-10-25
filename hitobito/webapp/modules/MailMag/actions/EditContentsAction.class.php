<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * メルマガ管理 配信メルマガ登録/編集アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseAction.class.php');

class EditContentsAction extends MailMagBaseAction {
	// ■アクション実行
	public function execute() {
		$controller = $this->getContext()->getController();
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		$contents_id = $request->getParameter('mailmag_contents_id');

		switch ($this->getSubmitButton()) {
		case 'preview_btn':		// プレビュー
			$this->setMailMagInput();
			$this->getEditPostInfo();
			return 'Preview';
		case 'immediate_btn':	// 即時配信
			$time = time();
			$request->setParameter('mailmag_send_year', date('Y'));
			$request->setParameter('mailmag_send_month', date('m'));
			$request->setParameter('mailmag_send_day', date('d'));
			$request->setParameter('mailmag_send_hour', date('H'));
			$request->setParameter('mailmag_send_minute', floor(date('i') / 10) * 10);
			// no break
		case 'reserve_btn':		// 配信予約
			$contents_id = $request->getParameter('mailmag_contents_id');

			$data = $this->getParameters();

			if ($contents_id)
				$this->db->updateContentsInfo($this->melmaga_id, $data, 'update');
			else
				$contents_id = $this->db->updateContentsInfo($this->melmaga_id, $data, 'insert');

			$this->regixxxlickCounter($contents_id);
			$user->setAttribute('mailmag_current_contents_id', $contents_id);
			return View::SUCCESS;
		case 'edit_click_counter_btn':	// クリックカウンタ設定
			$this->getEditPostInfo();
			$this->getClickCounter($contents_id);
			return 'EditClickCounter';
		case 'regist_click_counter_btn':	// クリックカウンタ登録
			$this->addClickCounter($contents_id);
			$this->getEditPostInfo();
			return 'RegixxxlickCounter';
		case 'cancel_btn':		// 登録しないで戻る
			return $this->getDefaultView();
		}
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$controller = $this->getContext()->getController();
		$request = $this->getContext()->getRequest();

		$this->setMailMagInput();

		if ($request->getParameter('form_data') == 'edit') {
			// 編集入力
			$contents_id = $request->getParameter('contents_id');
			if (!$contents_id) {
				$controller->forward('MailMag', 'ListMailMag');
				return View::NONE;
			}

			$this->getMelmagaContentsInfo($contents_id);
		} else {
			// 新規入力
			$this->getEditPostInfo();
		}

		return View::INPUT;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::POST;
	}

	// ■Validator登録
	public function registerValidators($validatorManager) {
		$request = $this->getContext()->getRequest();

		$validatorManager->registerName('mailmag_subject', true, 'タイトルが未入力です。');
		$validatorManager->registerName('mailmag_body', true, '本文が未入力です。');
		$this->checkSendDate($validatorManager,
				$request->getParameter('mailmag_send_year'),
				$request->getParameter('mailmag_send_month'),
				$request->getParameter('mailmag_send_day'),
				$request->getParameter('mailmag_send_hour'),
				$request->getParameter('mailmag_send_minute'));
	}

	// ■入力エラー処理
	public function handleError() {
		return $this->getDefaultView();
	}

	// ■パラメータ取得
	protected function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['contents_id'] = $request->getParameter('mailmag_contents_id');
		$params['subject'] = $request->getParameter('mailmag_subject');
		$params['body'] = $request->getParameter('mailmag_body');
		$params['footer'] = $request->getParameter('mailmag_footer');
		$params['send_year'] = $request->getParameter('mailmag_send_year');
		$params['send_month'] = $request->getParameter('mailmag_send_month');
		$params['send_day'] = $request->getParameter('mailmag_send_day');
		$params['send_hour'] = $request->getParameter('mailmag_send_hour');
		$params['send_minute'] = $request->getParameter('mailmag_send_minute');
		$params['open_flag'] = $request->getParameter('mailmag_open_flag');
		$params['comment_flag'] = $request->getParameter('mailmag_comment_flag');
		$params['trackback_flag'] = $request->getParameter('mailmag_trackback_flag');

		return $params;
	}

	// ■送信日チェック
	protected function checkSendDate(&$validatorManager, $year, $month, $day, $hour, $minute) {
		$time = mktime($hour, $minute, 0, $month, $day, $year);
		$date_ary = getdate($time);
		if ($date_ary['year'] == $year && $date_ary['mon'] == $month && $date_ary['mday'] == $day) {
			if ($time <= time())
				$validatorManager->registerName('check_send_date', true, '配信日時の時刻を過ぎています。');
		} else
			$validatorManager->registerName('check_send_date', true, '配信日が正しくありません。');
	}

	// ■クリックカウンタ取得
	private function getClickCounter($contents_id) {
		$request = $this->getContext()->getRequest();

		$body = $request->getParameter('mailmag_body');
		$user_page_url = $this->db->getConst('user_page_url');
		$click_counter_ary = array();
		$no = 1;

		if (mb_ereg_search_init($body, 'https?://[^ 　"\'\r\n]+')) {
			while (mb_ereg_search()) {
				$ary = mb_ereg_search_getregs();
				$jump_url = $ary[0];

				if (strpos($jump_url, "{$user_page_url}mc.php") === false) {
					$ctid = $this->db->getNextSequence('t_melmaga_click', 'mmi_ct_id');
					$click_counter_ary[$ctid]['no'] = $no++;
					$click_counter_ary[$ctid]['url_name'] = "URL-$ctid";
					$click_counter_ary[$ctid]['jump_url'] = $jump_url;
					$click_counter_ary[$ctid]['click_url'] = "{$user_page_url}mc.php?{$ctid}";
					$ctid++;
				}

			}
		}

		$request->setAttribute('mailmag_click_counter_ary', $click_counter_ary);
	}

	// ■クリックカウンタ追加
	private function addClickCounter($contents_id) {
		$request = $this->getContext()->getRequest();

		$body = $request->getParameter('mailmag_body');
		$click_counter = $request->getParameter('mailmag_click_counter');
		if ($click_counter != '')
			$click_counter_ary = unserialize($click_counter);
		else
			$click_counter_ary = array();

		$url_name = $request->getParameter('mailmag_url_name');
		$org_url = $request->getParameter('mailmag_org_url');
		$jump_url = $request->getParameter('mailmag_jump_url');
		$check = $request->getParameter('mailmag_check');

		$user_page_url = $this->db->getConst('user_page_url');

		if (is_array($check)) {
			foreach (array_keys($check) as $ctid) {
				$click_counter_ary[$ctid]['url_name'] = $url_name[$ctid];
				$click_counter_ary[$ctid]['jump_url'] = $jump_url[$ctid];

				$body = str_replace($org_url[$ctid], "{$user_page_url}mc.php?{$ctid}", $body);
			}
		}

		$request->setParameter('mailmag_body', $body);
		$request->setParameter('mailmag_click_counter', serialize($click_counter_ary));
	}

	// ■クリックカウンタ登録
	private function regixxxlickCounter($contents_id) {
		$request = $this->getContext()->getRequest();

		$click_counter = $request->getParameter('mailmag_click_counter');
		if ($click_counter != '')
			$click_counter_ary = unserialize($click_counter);
		else
			$click_counter_ary = array();

 		$this->db->insertClickCounter($contents_id, $click_counter_ary);
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