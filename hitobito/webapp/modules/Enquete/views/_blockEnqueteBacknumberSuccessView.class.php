<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ナビアンケート バックナンバー表示ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseView.class.php');

class _blockEnqueteBacknumberSuccessView extends EnqueteBaseView
{
	// ■ビュー実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('_blockEnqueteBacknumber.html');

		// アンケートリスト設定
		$navi_page_id = $request->getAttribute('navi_page_id');
		$enquete_list_ary = $request->getAttribute('enquete_list_ary');
		$this->setAttribute('enquete_list_ary', $enquete_list_ary);
	}
}
?>