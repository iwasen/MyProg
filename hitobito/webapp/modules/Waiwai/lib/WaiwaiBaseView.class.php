<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 ビュー基本クラス
 *
 *
 * @package
 * @author
 * @version
 */

abstract class WaiwaiBaseView extends SmartyView {
	// ■初期化
	public function initialize($context) {
		parent::initialize($context);

		// データベース接続
		$this->db = new WaiwaiDatabase();

		// ナビページID
		$request = $context->getRequest();
		$this->setAttribute('navi_page_id', $request->getAttribute('navi_page_id'));

		// 時間
		$this->setAttribute('waiwai_time', time());

		// ログイン状態
		$user = $context->getUser();
		$this->setAttribute('waiwai_isAuthenticated', $user->isAuthenticated());

		// 参加状態
		$user = $context->getUser();
		$this->setAttribute('waiwai_isRoomMember', $request->getAttribute('waiwai_isRoomMember'));

		// ヘッダにうめこむファイルの指定
		$navipage = hitobito::getNaviPageInfo();
		$user = $context->getUser();
		if(!$user->isAuthenticated()){
			// 未ログイン
			$action = isset($_REQUEST['action']) ? htmlspecialchars($_REQUEST['action']) : '';
			$this->setAttribute('redirect_url', 'index.php?module=Waiwai&amp;action='.$action.'&amp;navipage_id='.$navipage->getId());
			$this->setAttribute('login_html_file', '_new_regist_form.inc.html');
		}else{
			$memberId = $user->getAttribute('mbr_member_id');
			$db = HNb::getAdodb();
			$sql = 'SELECT * FROM t_room_member'
					.' WHERE rmm_room_id='.$navipage->getWaiwaiId()
					.' AND   rmm_member_id=' .$memberId;
			$row = $db->GetRow($sql);
			if($row){
				// この会議室に参加済み
				$this->setAttribute('member_nickname', $row['rmm_nickname']);
				$this->setAttribute('login_html_file', '_welcome.inc.html');
			}else{
				// 会議室に参加してない
				$this->setAttribute('login_html_file', '_regist_form.inc.html');
			}
		}

		return true;
	}

	// ■閲覧共通部設定
	protected function setBowseCommon() {
		$this->setRoomInfo();
		$this->setPageInfo();
		$this->setListData();
	}

	// ■会議室情報設定
	protected function setRoomInfo() {
		$request = $this->getContext()->getRequest();

		$this->setAttribute('waiwai_room', $request->getAttribute('waiwai_room'));
	}

	// ■ページ情報設定
	protected function setPageInfo() {
		$request = $this->getContext()->getRequest();

		// ページ情報取得
		$page = $request->getAttribute('waiwai_page');

		// 前ページの有無
		$page['prev_page'] = ($page['current_page'] > 1);

		// 次ページの有無
		$page['next_page'] = ($page['current_page'] < $page['max_page']);

		$this->setAttribute('waiwai_page', $page);
	}

	// ■リストデータ設定
	protected function setListData() {
		$request = $this->getContext()->getRequest();

		// フォーマット処理
		$waiwai_list_ary = array();
		$list_ary = $request->getAttribute('waiwai_list_ary');
		if (is_array($list_ary)) {
			foreach ($list_ary as $list) {
				$list['date'] = $this->format_datetime($list['date']);

				$len = strlen($list['depth']);
				$level = &$list['level'];
				$level = array();
				for ($j = 1; $j < $len; $j++) {
					switch ($list['depth']{$j}) {
					case '1':
						$level[] = "waiwai_thread1";
						break;
					case '2':
						$level[] = "waiwai_thread2";
						break;
					case '3':
						$level[] = "waiwai_thread3";
						break;
					case '4':
						$level[] = "spacer";
						break;
					}
				}

				$waiwai_list_ary[] = $list;
			}
		}
		$this->setAttribute('waiwai_list_ary', $waiwai_list_ary);
	}

	// ■発言内容設定
	protected function setContents() {
		$request = $this->getContext()->getRequest();
		$waiwai_contents = $request->getAttribute('waiwai_contents');

		$user = $this->getContext()->getUser();
		$display_type = $user->getAttribute('waiwai_display_type');

		if ($waiwai_contents) {
			// フォーマット処理
			if (isset($waiwai_contents['date']))
				$waiwai_contents['date'] = $this->format_datetime($waiwai_contents['date']);

			$this->setAttribute('waiwai_contents', $waiwai_contents);
		}
	}

	// ■発言内容設定
	protected function setPost() {
		$request = $this->getContext()->getRequest();

		$this->setAttribute('waiwai_post', $request->getAttribute('waiwai_post'));
	}

	// ■ログインメンバー情報設定
	protected function setMemberInfo() {
		$request = $this->getContext()->getRequest();

		$this->setAttribute('waiwai_member', $request->getAttribute('waiwai_member'));
	}

	// ■日時整形
	protected function format_datetime($date, $def = '', $separator_ymd = '.') {
		if ($date == '')
			return $def;
		else {
			sscanf($date, "%d-%d-%d %d:%d", &$y, &$m, &$d, &$h, &$i);
			return sprintf("%02d%s%02d%s%02d %02d:%02d", $y % 100, $separator_ymd, $m, $separator_ymd, $d, $h, $i);
		}
	}

	// ■日付整形
	protected function format_date($date, $def='', $separator_ymd = '.') {
		if ($date == '')
			return $def;
		else {
			sscanf($date, "%d-%d-%d", &$y, &$m, &$d);
			return sprintf("%02d%s%02d%s%02d", $y % 100, $separator_ymd, $m, $separator_ymd, $d);
		}
	}

	// ■文字数切り詰め
	protected function truncate_string($str, $len_total = 0, $len_1line = 0) {
		// 改行を削除
		$str = str_replace("\n", '', str_replace("\r", '', $str));

		// 指定文字数で切り詰め
		if ($len_total > 0 && mb_strlen($str) > $len_total) {
			$str = mb_substr($str, 0, $len_total);
			$tr = '…';
		} else
			$tr = '';

		// １行の文字数毎に改行を挿入
		if ($len_1line > 0) {
			$len = mb_strlen($str);
			$ary = array();
			$p = 0;
			do  {
				$ary[] = mb_substr($str, $p, $len_1line);
				$p += $len_1line;
			} while ($p < $len);
			$str = join("\n", $ary);
		}

		return $str;
	}

	// ■CSV出力
	protected function outputCSV($filename, $data_ary, $quote = false) {
		header('Pragma: no-cache');
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="' . mb_convert_encoding($filename, 'SJIS') . '"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');

		if (is_array($data_ary)) {
			foreach ($data_ary as $row_ary) {
				$csv = array();
				foreach ($row_ary as $data) {
					if ($quote || strstr($data, ',') || strstr($data, '"') || strstr($data, "\n"))
						$csv[] = '"' . str_replace('"', '""', $data) . '"';
					else
						$csv[] = $data;
				}
				echo mb_convert_encoding(join(',', $csv), 'SJIS', 'EUC-JP'), "\r\n";
			}
		}
	}

	// ■属性情報設定
	protected function setSubitem() {
		$request = $this->getContext()->getRequest();
		$this->setAttribute('waiwai_regist', $request->getAttribute('waiwai_regist'));
	}

	// ■住所1情報設定
	protected function setAddress1() {
		$request = $this->getContext()->getRequest();
		$this->setAttribute('waiwai_address1_ary', $request->getAttribute('waiwai_address1'));
	}

	// ■生年月日情報設定
	protected function setBirth() {
		$request = $this->getContext()->getRequest();
		$this->setAttribute('waiwai_birth_y', $request->getAttribute('waiwai_birth_y'));
		$this->setAttribute('waiwai_birth_m', $request->getAttribute('waiwai_birth_m'));
		$this->setAttribute('waiwai_birth_d', $request->getAttribute('waiwai_birth_d'));
	}

}
?>