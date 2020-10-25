<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 アクション基本クラス
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiDatabase.class.php');

abstract class WaiwaiBaseAction extends Action {
	protected $db;
	protected $channel_id;
	protected $navi_page_id;
	protected $room_id;
	protected $member_id;
	protected $display_type;
	protected $page_size;
	protected $max_page;
	protected $current_page;
	protected $is_room_member;

	// ■初期化
	public function initialize($context) {
		if (!parent::initialize($context))
			return false;

		$user = $context->getUser();

		// ナビページID正当性チェック
		if ($this->isNaviEditPage())
			Navi::GuardNaviEdit($user);

		// データベース接続
		$this->db = new WaiwaiDatabase();

		// ナビページID取得
		$request = $context->getRequest();
		$this->navi_page_id = $request->getParameter('navipage_id');
		$request->setAttribute('navi_page_id', $this->navi_page_id);

		// チャネルID取得
		$this->channel_id = $request->getParameter('category_id');
		$request->setAttribute('channel_id', $this->channel_id);

		// 会議室ID取得
		if (is_numeric($this->navi_page_id))
			$this->room_id = $this->db->getRoomIdFromNaviPageId($this->navi_page_id);
		else
			$this->room_id = 0;

		// メンバーID取得
		$this->member_id = $user->getAttribute('mbr_member_id');

		// 参加状態
		if ($this->room_id && $this->member_id)
			$this->is_room_member = $this->db->checkRoomMember($this->room_id, $this->member_id);
		$request->setAttribute('waiwai_isRoomMember', $this->is_room_member);

		// 「更新」ボタンがクリックされた場合は、getDefaultView()を呼び出すようにする
		if ($request->getParameter('waiwai_page_update_x') !== null || $request->getParameter('waiwai_page_move') !== null)
			$request->setMethod(REQUEST::GET);

		return true;
	}

	// ■ナビページID正当性チェック
	protected function checkNaviPageId($owner = false) {
		if (!is_numeric($this->navi_page_id)) {
			$this->forwardNaviPageIdError();
			return false;
		}

		return true;
	}

	// ■ナビページIDエラー処理
	protected function forwardNaviPageIdError() {
		$controller = $this->getContext()->getController();
		$controller->forward('Default', 'Index');
	}

	// ■閲覧共通部設定
	protected function setBrowseCommon($open_only = true) {
		// 会議室情報設定
		$this->setRoomInfo();

		// ページ表示情報設定
		$this->setPageInfo();

		// 発言一覧設定
		$this->setRemarkList($open_only);
	}

	// ■ナビのメンバーID取得
	protected function getNaviMemberId() {
		return $this->db->getMemberIdFromNaviPageId($this->navi_page_id);
	}

	// ■会議室情報設定
	protected function setRoomInfo() {
		$request = $this->getContext()->getRequest();

		$room = $this->db->getRoomOutline($this->room_id);
		$request->setAttribute('waiwai_room', $room);
	}

	// ■ページ表示情報設定
	protected function setPageInfo() {
		$context = $this->getContext();
		$request = $context->getRequest();
		$user = $context->getUser();

		// 投稿件数取得
		$remark_num = $this->db->getRemarkNum($this->room_id);

		// 現表示タイプ情報取得
		$this->display_type = $request->getParameter('waiwai_display_type');
		if ($this->display_type == null)
			$this->display_type = $user->getAttribute('waiwai_display_type');
		if ($this->display_type == null)
			$this->display_type = 'thread';
		$user->setAttribute('waiwai_display_type', $this->display_type);

		// 現ページサイズ選択肢
		$this->page_size = $request->getParameter('waiwai_page_size');
		if ($this->page_size == null)
			$this->page_size = $user->getAttribute('waiwai_page_size');
		if ($this->page_size == null)
			$this->page_size = 50;
		$user->setAttribute('waiwai_page_size', $this->page_size);

		// ページ数取得
		$this->max_page = (int)(($remark_num + $this->page_size - 1) / $this->page_size);
		if ($this->max_page == 0)
			$this->max_page = 1;

		// 現ページ番号取得
		$this->current_page = $request->getParameter('waiwai_current_page');
		if ($this->current_page == null)
			$this->current_page = $user->getAttribute('waiwai_current_page');
		if ($this->current_page == null)
			$this->current_page = $this->max_page;

		// 前ページ／次ページ処理
		if ($request->getParameter('waiwai_page_move') != '')
			$this->current_page += $request->getParameter('waiwai_page_move');

		// 現ページ保存
		$user->setAttribute('waiwai_current_page', $this->current_page);

		// ページの範囲を調整
		if ($this->current_page < 1)
			$this->current_page = 1;
		elseif ($this->current_page > $this->max_page)
			$this->current_page = $this->max_page;

		// スレッド／投稿順選択肢
		$page['display_type_options'] = array('thread' => 'スレッド表示', 'time' => '投稿順に表示');
		$page['display_type'] = $this->display_type;

		// ページサイズ選択肢
		$page['page_size_options'] = array(50 => '50件ずつ', 20 => '20件ずつ');
		$page['page_size'] = $this->page_size;

		// 表示ページ選択肢
		$page_ary = array();
		for ($page_no = 1; $page_no <= $this->max_page; $page_no++)
			$page_ary[$page_no] = "{$page_no}ページ";
		$page['current_page_options'] = $page_ary;
		$page['current_page'] = $this->current_page;

		// ページ数
		$page['max_page'] = $this->max_page;

		// ビューに渡す
		$request->setAttribute('waiwai_page', $page);
	}

	// ■発言一覧設定
	protected function setRemarkList($open_only) {
		$request = $this->getContext()->getRequest();

		$list_ary = array();

		if ($this->display_type == 'thread')
			$this->getThreadList($list_ary, $open_only);
		else
			$this->getTimeList($list_ary, $open_only);

		$request->setAttribute('waiwai_list_ary', $list_ary);
	}

	// ■スレッド表示リスト取得
	protected function getThreadList(&$ary, $open_only, $parent_remark_id = 0, $count = 0, $depth = '') {
		if (strlen($depth) > 100)
			return $count;

		$depth = str_replace('2', '3', $depth);
		$depth = str_replace('1', '4', $depth);

		$offset = ($this->current_page - 1) * $this->page_size;

		$rec_ary = $this->db->getChildrenList($this->room_id, $parent_remark_id);

		$nrow = count($rec_ary);
		for ($i = 0; $i < $nrow;$i++) {
			if ($this->page_size != 0 && $count >= $offset + $this->page_size)
				break;

			$rec = &$rec_ary[$i];

			$depth2 = $depth;

			if (!$open_only || $rec['open_flag'] == 1) {
				$depth2 .= ($i == $nrow - 1) ? '1' : '2';

				if ($count >= $offset) {
					$tmp = &$ary[];
					$tmp['remark_id'] = $rec['remark_id'];
					$tmp['seq_no'] = $rec['seq_no'];
					$tmp['subject'] = $rec['subject'];
					$tmp['nickname'] = $rec['nickname'];
					$tmp['date'] = $rec['date'];
					$tmp['depth'] = $depth2;
				}

				$count++;
			}

			if ($rec['child_num']) {
				if ($rec['child_num'] + $count < $offset){
					$count += $rec['child_num'];
}				else
					$count = $this->getThreadList($ary, $open_only, $rec['remark_id'], $count, $depth2);
			}
		}

		return $count;
	}

	// ■投稿順表示リスト取得
	protected function getTimeList(&$ary, $open_only) {
		$rec_ary = $this->db->getTimeList($this->room_id, $this->page_size, ($this->current_page - 1) * $this->page_size);

		foreach ($rec_ary as $rec) {
			if (!$open_only || $rec['open_flag'] == 1) {
				$tmp = &$ary[];
				$tmp['remark_id'] = $rec['remark_id'];
				$tmp['seq_no'] = $rec['seq_no'];
				$tmp['subject'] = $rec['subject'];
				$tmp['nickname'] = $rec['nickname'];
				$tmp['date'] = $rec['date'];
				$tmp['depth'] = '';
			}
		}
	}

	// ■発言内容設定
	protected function setContents($page_view_flag = false) {
		$context = $this->getContext();
		$request = $context->getRequest();

		$seq_no = $request->getParameter('waiwai_id');
		if ($seq_no) {
			$remark_id = $this->db->getRemarkId($this->room_id, $seq_no);
			if ($remark_id) {

				// 発言内容設定
				$contents = $this->db->getContents($remark_id);

				$request->setAttribute('waiwai_contents', $contents);

				// ページビューカウント
				if ($page_view_flag)
					$this->db->countPageView($contents['pv_id']);
			}
		}
	}

	// ■返信内容設定
	protected function setReply() {
		$request = $this->getContext()->getRequest();

		$remark_id = $this->db->getRemarkId($this->room_id, $request->getParameter('waiwai_id'));
		if ($remark_id) {
			$rec = $this->db->getContents($remark_id);
			if ($request->getParameter('waiwai_back_flag')) {
				$post['subject'] = $request->getParameter('waiwai_subject');
				$post['content'] = $request->getParameter('waiwai_content');
			} else {
				$post['subject'] = $this->add_re($rec['subject']);
				$post['content'] = $this->make_quote($rec['content']);
			}
			$post['back_flag'] = $request->getParameter('waiwai_back_flag');
			$request->setAttribute('waiwai_post', $post);
		}
	}

	// ■件名にRe: を付加
	protected function add_re($subject) {
		// 既存のRe;の削除
		while (true) {
			$subject = trim($subject);
			if (strncasecmp($subject, 'Re:', 3) == 0)
				$subject = substr($subject, 3);
			else
				break;
		}
		return "Re: $subject";
	}


	// ■引用文作成
	protected function make_quote(&$body) {
		$quote = '';
		$array = split("\n", $body);
		foreach ($array as $line) {
			$line= rtrim($line);
			$len = mb_strlen($line);
			$offset = 0;
			do {
				$str = mb_strimwidth($line, $offset, 76);
				$n = mb_strlen($str);
				$quote .= '> ' . $str . "\n";
				$offset += $n;
				$len -= $n;
			} while ($len > 0);
		}
		return $quote;
	}

	// ■ログインメンバー情報設定
	protected function setMemberInfo() {
		$context = $this->getContext();
		$request = $context->getRequest();
		$user = $context->getUser();

		if ($this->member_id) {
			$member['nickname'] = $this->db->getNickname($this->room_id, $this->member_id);
			$request->setAttribute('waiwai_member', $member);
		}
	}

	// ■発言登録処理
	protected function postRemark($member_id, $parent_remark_id, &$subject, &$body, $nickname = '') {
		// 会議室情報取得
		$room_info = $this->db->getRoomInfo($this->room_id);

		// メンバー情報取得
		if ($member_id) {
			// 通常メンバー
			$member_info = $this->db->getMemberInfo($this->room_id, $member_id);
			if (!$member_info)
				return;
		} else {
			// 一般投稿者
			$member_info['nickname'] = $nickname;
			$member_info['member_code'] = 'public';
			$member_info['mail_addr'] = '';
		}

		// Fromアドレスを編集
		$from_addr = $this->getMemberMailAddr($room_info['room_code'], $member_info['member_code']);
		$from = $this->joinMailFrom($from_addr, $member_info['nickname']);

		// 差し込み変数処理
		$var_ary = $this->getRoomVars($this->room_id);
		$content = $this->replaceRoomVars($var_ary, $body);
		$header = $room_info['header_flag'] ? $this->replaceRoomVars($var_ary, $room_info['header']) : '';
		$footer = $room_info['footer_flag'] ? $this->replaceRoomVars($var_ary, $room_info['footer']) : '';

		// 本文中の実メールアドレスを個人マスクアドレスに置換
		if ($member_info['mail_addr'] != '')
			$content = str_replace($member_info['mail_addr'], $this->getMemberMailAddr($room_info['room_code'], $member_info['member_code']), $content);

		// 件名無しの処置
		if ($subject == '')
			$subject = '（件名無し）';
		else {
			// メーリングリスト名と発言番号を削除
			$reg = sprintf('\[%s:[0-9]+\] *(Re: *)*', $room_info['room_code']);
			$subject = eregi_replace($reg, '', $subject);
		}

		// 発言ログに保存
		$rec['room_id'] = $this->room_id;
		$rec['member_id'] = $member_id;
		$rec['date'] = 'now';
		$rec['parent_remark_id'] = $parent_remark_id;
		$rec['disp_type'] = 1;
		$rec['disp_member_id'] = 0;
		$rec['remark_type'] = 1;
		$rec['seq_no'] = "(SELECT COALESCE(max(rmk_seq_no),0)+1 FROM l_room_remark WHERE rmk_room_id=$this->room_id AND rmk_disp_type=1)";
		$rec['from'] = $from;
		$rec['message_id'] = $this->makeMassageId();
		$rec['mail_header'] = '';
		$rec['subject'] = $subject;
		$rec['content'] = $content;
		$rec['room_header'] = $header;
		$rec['room_footer'] = $footer;
		$rec['web_mail'] = 2;
		$rec['ng_word'] = $this->db->checkNGWord($subject . $content);
		$rec['pv_id'] = $this->db->getNewPageViewId();
		$rec['nickname'] = $member_info['nickname'];
		$this->db->insertRemark($this->room_id, $rec);
	}

	// ■メール配信処理
	protected function sendRemark($remark_id, $member_id) {
		$send_remark = $this->db->getSendRemark($remark_id);
		if ($send_remark) {
			// 会議室CD等取得
			$room_id = $send_remark['room_id'];
			$room_code = $send_remark['room_code'];

			// 親メッセージID取得
			if ($send_remark['parent_remark_id']) {
				$ref_message_id = $this->db->getMessageId($send_remark['parent_remark_id']);
			} else
				$ref_message_id = '';

			// メールヘッダ編集
			$mail_header = $this->makeMailHeader($send_remark['message_id'], $ref_message_id);

			// ヘッダ・本文・フッタを編集
			$content = $this->makeMailContents($send_remark['room_header'], $send_remark['content'], $send_remark['room_footer']);

			// 件名にメーリングリスト名と発言番号を付加
			$subject = sprintf('[%s:%d] %s', $room_code, $send_remark['seq_no'], $send_remark['subject']);

			// 返信先取得
			$reply_to = $this->getRoomMailAddr($room_code);

			// 送信メールデータセット
			$send_mail_id = $this->db->setSendMailData($subject, $send_remark['from'], $reply_to, $content);

			// 配信先メンバー情報取得
			$member_info = $this->db->getMemberInfo($room_id, $member_id);
			if ($member_info) {
				// 配信先リスト設定
				$this->db->setSendMailList($send_mail_id, $member_info['mail_addr'], 1, $mail_header);

				// To:ヘッダをルームアドレスに設定
				$this->db->setSendMailEmbed($send_mail_id, 1, '%%TO_ADDR%%', $this->getRoomMailAddr($room_code));
			}

			$this->db->setSendMailOk($send_mail_id);
		}
	}

	// ■メールヘッダ生成
	private function makeMailHeader($message_id, $ref_message_id) {
		$ary[] = "Message-ID: <$message_id>";
		if ($ref_message_id) {
			$ary[] = "In-Reply-To: <$ref_message_id>";
			$ary[] = "References: <$ref_message_id>";
		}

		return join("\n", $ary);
	}

	// ■ヘッダ、本文、フッタを結合
	private function makeMailContents($header, $body, $footer) {
		if ($header != '')
			$ary[] = trim($header);

		$ary[] = trim($body);

		if ($footer != '')
			$ary[] = trim($footer);

		return join("\n\n", $ary);
	}

	// ■Message-ID:生成
	protected function makeMassageId($str = 'hitobito') {
		return uniqid($str, true) . '@' . $this->db->getConst('room_mail_domain');
	}

	// ■個人マスクアドレス取得
	protected function getMemberMailAddr($room_code, $member_code) {
		return sprintf("%s-%s@%s", $room_code, $member_code, $this->db->getConst('room_mail_domain'));
	}

	// ■FromアドレスとFrom名称を結合
	protected function joinMailFrom($addr, $name) {
		if ($name == '')
			return $addr;
		else
			return "$name <$addr>";
	}

	// ■キーワード取得
	protected function getKeyword() {
		$rec = $this->db->getRoomVars($this->room_id);
		if ($rec) {
			$kw['navi_page_name'] = $rec['title'];
			$kw['navi_page_url'] = $this->getNavipageURL();
			$kw['room_name'] = $rec['name'];
			$kw['navi_name'] = "{$rec['name1']} {$rec['name2']}";
			$kw['post_addr'] = $this->getRoomMailAddr($rec['room_code']);
			$kw['post_url'] = $this->getRoomURL();
		} else
			$kw = null;

		return $kw;
	}

	// ■差し込み変数設定
	protected function getRoomVars() {
		$var_ary = array();

		$kw = $this->getKeyword();
		if ($kw) {
			$var_ary['%ナビページ名%'] = $kw['navi_page_name'];
			$var_ary['%ナビページURL%'] = $kw['navi_page_url'];
			$var_ary['%会議室名%'] = $kw['room_name'];
			$var_ary['%ナビ名%'] = $kw['navi_name'];
			$var_ary['%投稿用アドレス%'] = $kw['post_addr'];
			$var_ary['%投稿用URL%'] = $kw['post_url'];
		}

		return $var_ary;
	}

	// ■会議室メールアドレス取得
	protected function getRoomMailAddr($room_code) {
		return sprintf("%s@%s", $room_code, $this->db->getConst('room_mail_domain'));
	}

	// ■ナビページURL取得
	protected function getNavipageURL() {
		$url = $this->db->getConst('user_page_url');
		$url .= "?module=Navi&action=ShowNaviPage&navipage_id=$this->navi_page_id";
		return $url;
	}

	// ■会議室投稿ページURL取得
	protected function getRoomURL() {
		$url = $this->db->getConst('user_page_url');
		$url .= "?module=Waiwai&action=PostNew&navipage_id=$this->navi_page_id";
		return $url;
	}

	// ■会議室関連差し込み処理
	protected function replaceRoomVars($var_ary, $content) {
		foreach ($var_ary as $key => $val)
			$content = str_replace($key, $val, $content);

		return $content;
	}

	// ■submitボタンチェック
	protected function getSubmitButton() {
		$request = $this->getContext()->getRequest();

		$buttons = $request->getParameterNames();
		if (is_array($buttons)) {
			foreach ($buttons as $button) {
				if (preg_match('/^(.+_btn)(_x)?$/', $button, $matches))
					return $matches[1];
			}
		}

		return null;
	}

	// ■メールアドレスチェック
	protected function checkMailaddr($mail_addr) {
		return preg_match('/^[a-zA-Z0-9._\/-]+@[a-zA-Z0-9_-]+\.[a-zA-Z0-9._-]+[^.]$/', $mail_addr) != 0 ? true : false;
	}

	// ■ワイワイ会議ステータスチェック
	protected function checkRoomStatus() {
		if ($this->room_id) {
			$status = $this->db->getRoomStatus($this->room_id);
			if ($status == 1)	// 公開
				return;
			elseif ($status == 2) {	// 非公開
				if ($this->is_room_member)
					return;
			}

			// 参加権限無し
			header('HTTP/1.0 403 Forbidden');
			die('Forbidden');
		}
	}

	// ■ナビ編集ページ
	protected function isNaviEditPage() {
		return false;
	}
}
?>