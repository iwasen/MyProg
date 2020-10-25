<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ナビアンケート 実施中アンケート表示ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseView.class.php');

class _blockShowEnqListSuccessView extends EnqueteBaseView
{
	// ■ビュー実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('_blockShowEnqList.html');

		$this->setAttribute('enquete_list_ary', $request->getAttribute('enquete_list_ary'));
	}
}
?>