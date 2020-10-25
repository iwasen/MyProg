<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 ゲストメンバー一覧入力ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class ListGuestInputView extends WaiwaiBaseView {
	// ■ビュー実行
	public function execute() {
		$request = $this->getContext()->getRequest();
		$member_id = $request->getParameter('member_id');

		// テンプレート設定
		$this->setTemplate('ListGuestInput.html');

		if ($member_id) {
			// メンバー情報設定
			$member_data = $request->getAttribute('waiwai_member_data');
			$this->setAttribute('waiwai_member_data', $this->formatMemberData($member_data));
		} else {

			// ゲストメンバーリスト設定
			$this->setAttribute('waiwai_guest_sort', $request->getAttribute('waiwai_guest_sort'));
			$guest_list_ary = $request->getAttribute('waiwai_guest_list_ary');
			$this->setAttribute('waiwai_guest_list_ary', $this->formatList($guest_list_ary));

			// エラーメッセージ設定
			$this->setAttribute('waiwai_error_ary', $request->getErrors());
		}

		// メインテンプレート
		$this->useMainTemplate();
	}

	// ■リストを表示用にフォーマット
	private function formatList($list_ary) {
		$format_list_ary = array();
		if (is_array($list_ary)) {
			$no = 1;
			foreach ($list_ary as $list) {
				$list['no'] = $no++;
				$list['mail_send_flag'] = $list['mail_send_flag'] ? '配信' : '停止';
				$list['regist_date'] = $this->format_date($list['regist_date'], '-');

				$format_list_ary[] = $list;
			}
		}

		return $format_list_ary;
	}

	// ■メンバー情報表示設定
	private function formatMemberData($data) {

		$data['mail_addr'] = $data['member_code'] ? $data['room_code'] . '-' . $data['member_code'] . '@hitobito.net' : '未登録';
		$data['name'] = $data['name'] ? '登録済み' : '未登録';
		$data['name_kana'] = $data['name_kana'] ? $data['name_kana'] : '未登録';
		$data['sex_cd'] = $this->decode_sex_cd($data['sex_cd']);
		$data['birthday'] = $this->date_format_j($data['birthday']);
		$data['zip_cd'] = $data['zip_cd'] ? '登録済み' : '未登録';
		$data['address1'] = $this->db->getArea1Name($data['address1']) ? $this->db->getArea1Name($data['address1']) : '未登録';
		$data['address2'] = $data['address2'] ? '登録済み' : '未登録';
		$data['tel'] = $data['tel'] ? '登録済み' : '未登録';
		$data['self_introduction'] = $data['self_introduction'] ? $data['self_introduction'] : '未登録';

		return $data;
	}

	// ■性別デコード
	private function decode_sex_cd($code) {

		switch ($code) {
		case 1:
			return '男';
		case 2:
			return '女';
		default:
			return '未登録';
		}
	}

	// ■日付形成
	private function date_format_j($date) {
		if ($date) {
			sscanf($date, "%d-%d-%d", &$y, &$m, &$d);
			$date = $y . '年　' . $m . '月　' . $d . '日';
			return $date;
		} else {
			return '未登録';
		}
	}
}

?>