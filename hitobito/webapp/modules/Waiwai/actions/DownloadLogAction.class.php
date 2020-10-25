<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 発言ダウンロードアクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class DownloadLogAction extends WaiwaiBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		$request->setAttribute('waiwai_download_data', $this->db->getRemarkLog($this->room_id));

		return View::SUCCESS;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		return View::NONE;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::GET | Request::POST;
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