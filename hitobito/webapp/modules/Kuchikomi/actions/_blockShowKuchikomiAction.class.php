<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * クチコミWEB 最新クチコミ情報表示アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Kuchikomi/lib/KuchikomiBaseAction.class.php');

class _blockShowKuchikomiAction extends KuchikomiBaseAction {
	// ■アクション実行
	public function execute() {
		return View::SUCCESS;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		// チャネルID取得
		$channel_id = $this->db->getChannelId($this->navi_page_id);

		// 表示件数取得
		$disp_num = $this->db->getConst('max_new_kuchikomi');

		// 最新クチコミ情報取得
		$list_ary = $this->db->getKuchikomiList($channel_id, $disp_num);
		if ($list_ary) {
			$request->setAttribute('kuchikomi_list_ary', $list_ary);

			return View::SUCCESS;
		} else
			return View::NONE;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::NONE;
	}
}
?>