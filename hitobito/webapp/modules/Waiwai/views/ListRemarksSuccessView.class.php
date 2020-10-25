<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 最新ワイワイ会議投稿ブロックビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class ListRemarksSuccessView extends WaiwaiBaseView {
	// ■ビュー実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('ListRemarks.html');

		// 最新投稿リスト取得
		$list_ary = $request->getAttribute('waiwai_list_ary');

		// ページ切り替えURL
		$url = '?module=Waiwai&action=ListRemarks';
		$channel_id = $request->getAttribute('channel_id');
		$navi_page_id = $request->getAttribute('navi_page_id');
		if ($channel_id)
			$url .= "&category_id=$channel_id";
		if ($navi_page_id)
			$url .= "&navipage_id=$navi_page_id";

		$list_offset = $request->getAttribute('waiwai_list_offset');

		$page = array();
		if (count($list_ary) > 10) {
			array_pop($list_ary);
			$page['next'] = "$url&list_offset=" . ($list_offset + 10);
		}

		if ($list_offset > 0) {
			$page['prev'] = "$url&list_offset=" . ($list_offset - 10);
		}

		$page['top'] = $url;
		$this->setAttribute('waiwai_page', $page);

		if (is_array($list_ary)) {
			foreach ($list_ary as $i => &$list) {
				$list['date'] = $this->format_datetime($list['date']);
				$list['content'] = $this->truncate_string($list['content'], 40, 20);
				$list['bgcolor'] = $i % 2 ? '#F0FFE8' : '#FFFFFF';
			}
		}
		$this->setAttribute('waiwai_list_ary', $list_ary);

		// メインテンプレート
		$this->useMainTemplate();
	}
}
?>