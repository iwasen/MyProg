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

abstract class KuchikomiBaseView extends SmartyView {
	// ■初期化
	public function initialize($context) {
		parent::initialize($context);

		// ナビページID
		$request = $context->getRequest();
		$this->setAttribute('navi_page_id', $request->getAttribute('navi_page_id'));

		return true;
	}
}
?>