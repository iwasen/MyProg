<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ナビアンケート 全アンケート一覧入力ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseView.class.php');

class ListEnqueteInputView extends EnqueteBaseView {
	// ■ビュー実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('ListEnqueteInput.html');

		// ページ情報取得
		$page = $request->getAttribute('enquete_page');

		// 前ページの有無
		$page['prev_page'] = ($page['current_page'] > 1);

		// 次ページの有無
		$page['next_page'] = ($page['current_page'] < $page['max_page']);

		// ページ選択肢
		for ($i = 1; $i <= $page['max_page']; $i++)
			$page_ary[$i] = $i . 'ページ';
		$page['options'] = $page_ary;

		// ページ情報設定
		$this->setAttribute('enquete_page', $page);

		// アンケートリスト設定
		$navi_page_id = $request->getAttribute('navi_page_id');
		$enquete_list_ary = $request->getAttribute('enquete_list_ary');
		$this->setAttribute('enquete_list_ary', $this->formatList($enquete_list_ary, $page['current_page'], $navi_page_id));

		// エラーメッセージ設定
		$this->setAttribute('enquete_error_ary', $request->getErrors());

		// メインテンプレート
		$this->useMainTemplate();
	}

	// ■リストを表示用にフォーマット
	private function formatList($list_ary, $page_no, $navi_page_id) {
		$format_list_ary = array();
		if (is_array($list_ary)) {
			foreach ($list_ary as $index => $list) {
				$list['start_date'] = $this->format_date($list['start_date']);
				$list['end_date'] = $this->format_date($list['end_date']);
				$list['bgcolor'] = $index % 2 ? '#E6F2FF' : '#FFFFFF';

				switch ($list['status']) {
				case 2:
					$list['status_gif'] = 'enquete_time_on.gif';
					$list['status_alt'] = '回答受付中！';
					$list['status_text'] = '回答受付中';
					$list['status_flag'] = true;
					$list['status_color'] = '#EE615E';
					$list['link'] = "?module=Enquete&action=AnswerEnquete&navipage_id=$navi_page_id&enquete_id={$list['enquete_id']}";
					break;
				case 3:
					$list['status_gif'] = 'enquete_time_off.gif';
					$list['status_alt'] = '回答期間終了';
					$list['status_text'] = '回答期間終了';
					$list['status_flag'] = false;
					$list['status_color'] = '#999999';
					$list['link'] = "?module=Enquete&action=ShowResult&navipage_id=$navi_page_id&enquete_id={$list['enquete_id']}";
					break;
				}

				if ($page_no == 1 && $index < 2) {
					// アンケート選択肢項目
					$enquete_option = $list['option'];
					$option_ary = array();
					if (is_array($enquete_option)) {
						foreach ($enquete_option as $option) {
							$answer_count = $list['answer_count'];
							$option['bgcolor'] = $option['option_no'] % 2 ? '#E6F2FF' : '#FFFFFF';
							$option['percent'] = $answer_count ? round($option['answer_count'] / $answer_count * 100) : 0;
							$option['bar_width'] = $answer_count ? round($option['answer_count'] / $answer_count * 200) : 0;
							$option_ary[] = $option;
						}
					}
					$list['option_ary'] = $option_ary;
					$list['result_flag'] = true;
				} else
					$list['result_flag'] = false;

				$format_list_ary[] = $list;
			}
		}

		return $format_list_ary;
	}
}

?>