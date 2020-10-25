<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * メルマガ管理 バックナンバー一覧表示アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseAction.class.php');

class BacknumberListAction extends MailMagBaseAction {

	public function execute() {
		return View::NONE;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		if ($this->melmaga_id) {
			$mailmag = $this->db->getBacknumberInfo($this->melmaga_id);
			if ($mailmag['status'] == 1) {
				if ($mailmag['backnumber'] == 1 || $mailmag['backnumber'] == 3)
					$mailmag['backnumber_ary'] = $this->db->getBacknumberList($this->melmaga_id, $mailmag['backnumber'] == 3 ? 1 : 0);
			}

		} else {
			$mailmag['publish_count'] = 0;
			$mailmag['reader_num'] = 0;
			$mailmag['last_date'] = '';
		}
		$request->setAttribute('mailmag', $mailmag);

		return View::SUCCESS;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::NONE;
	}
}
?>