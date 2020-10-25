<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 閲覧ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class BrowseSuccessView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		// テンプレート設定
		$this->setTemplate('BrowseSuccess.html');

		// 閲覧共通設定
		$this->setBowseCommon();

		// 発言内容設定
		$this->setContents();

		// リンク設定
		$this->setLinks();

		// メインテンプレート
		$this->useMainTemplate();
	}

	// ■前後投稿リンク設定
	private function setLinks() {
		$request = $this->getContext()->getRequest();
		$waiwai_links = $request->getAttribute('waiwai_links');

		if ($waiwai_links) {
				// 前リンク
			$link_data = $this->db->getContents($waiwai_links['befor_remark_id']);
			$waiwai_links['befor_link'] = $this->make_link_text('前の投稿', $link_data);

			// 後リンク
			$link_data = $this->db->getContents($waiwai_links['next_remark_id']);
			$waiwai_links['next_link'] = $this->make_link_text('後の投稿', $link_data);

			$this->setAttribute('waiwai_links', $waiwai_links);

		// 一覧表示強調設定
		} else if($request->getParameter('view')) {
			$waiwai_contents['seq_no'] = $request->getParameter('waiwai_id');
			$waiwai_contents['view'] = $request->getParameter('view');
			$this->setAttribute('waiwai_contents', $waiwai_contents);
		}
	}

	// ■前後投稿リンク生成
	private function make_link_text($kind, $link_data) {
		$navi_page_id = $this->getAttribute('navi_page_id');
		$seq_no = $link_data['seq_no'];
		$url = 'index.php?module=Waiwai&action=Browse&navipage_id='.$navi_page_id.'&waiwai_id='.$seq_no.'#waiwai';
		$title = $link_data['subject'];
		$name = $link_data['nickname'];
		$date = $this->format_datetime($link_data['date']);

		if ($seq_no)
			$text = '<a href="'.$url.'">' . $kind . '：['.$seq_no.'] '.$title.'　by '.$name.'　'.$date.'</a>';

		return $text;
	}
}

?>