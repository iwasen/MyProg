<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * メルマガ管理 配信メルマガ一覧表示アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseAction.class.php');

class ShowClickAction extends MailMagBaseAction {
	// ■アクション実行
	public function execute() {
		$controller = $this->getContext()->getController();
		$request = $this->getContext()->getRequest();

		$contents_id = $request->getParameter('contents_id');
		$contents = $this->db->getClickContents($contents_id);

		$contents['contents'] = $this->getClickCount($contents['body']);

		$request->setAttribute('mailmag_contents', $contents);

		return View::SUCCESS;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::GET | Request::POST;
	}

	// ■クリックカウンタ取得
	private function getClickCount($body) {
		$click_ary = array();

		$total = 0;

		foreach (explode("\n", $body) as $text) {
			unset($tmp);

			$tmp['text'] = $text;

			if (preg_match('/\/mc.php\?([0-9]+)/', $text, $matches)) {
				$click = $this->db->getClickCounter($matches[1]);
				$tmp['click'] = $click;
				$total += $click;
			}

			$click_ary[] = $tmp;
		}

		foreach ($click_ary as &$click) {
			if (isset($click['click']))
				$click['percent'] = $total ? round($click['click'] / $total * 100) : 0;
		}

		return $click_ary;
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