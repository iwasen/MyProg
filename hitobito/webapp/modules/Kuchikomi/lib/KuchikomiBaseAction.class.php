<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * クチコミWEB ビュー基本クラス
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Kuchikomi/lib/KuchikomiDatabase.class.php');

abstract class KuchikomiBaseAction extends Action {
	protected $db;
	protected $navi_page_id;

	// ■初期化
	public function initialize($context) {
		parent::initialize($context);

		// データベース接続
		$this->db = new KuchikomiDatabase();

		// ナビページID取得
		$request = $context->getRequest();
//		$this->navi_page_id = $request->getParameter('navipage_id');
		
		$this->navi_page_id = hitobito::getNavipageInfo()->getId();

		return true;
	}
}
?>