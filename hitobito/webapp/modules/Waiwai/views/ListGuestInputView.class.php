<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� �����ȥ��С��������ϥӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class ListGuestInputView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	public function execute() {
		$request = $this->getContext()->getRequest();
		$member_id = $request->getParameter('member_id');

		// �ƥ�ץ졼������
		$this->setTemplate('ListGuestInput.html');

		if ($member_id) {
			// ���С���������
			$member_data = $request->getAttribute('waiwai_member_data');
			$this->setAttribute('waiwai_member_data', $this->formatMemberData($member_data));
		} else {

			// �����ȥ��С��ꥹ������
			$this->setAttribute('waiwai_guest_sort', $request->getAttribute('waiwai_guest_sort'));
			$guest_list_ary = $request->getAttribute('waiwai_guest_list_ary');
			$this->setAttribute('waiwai_guest_list_ary', $this->formatList($guest_list_ary));

			// ���顼��å���������
			$this->setAttribute('waiwai_error_ary', $request->getErrors());
		}

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}

	// ���ꥹ�Ȥ�ɽ���Ѥ˥ե����ޥå�
	private function formatList($list_ary) {
		$format_list_ary = array();
		if (is_array($list_ary)) {
			$no = 1;
			foreach ($list_ary as $list) {
				$list['no'] = $no++;
				$list['mail_send_flag'] = $list['mail_send_flag'] ? '�ۿ�' : '���';
				$list['regist_date'] = $this->format_date($list['regist_date'], '-');

				$format_list_ary[] = $list;
			}
		}

		return $format_list_ary;
	}

	// �����С�����ɽ������
	private function formatMemberData($data) {

		$data['mail_addr'] = $data['member_code'] ? $data['room_code'] . '-' . $data['member_code'] . '@hitobito.net' : '̤��Ͽ';
		$data['name'] = $data['name'] ? '��Ͽ�Ѥ�' : '̤��Ͽ';
		$data['name_kana'] = $data['name_kana'] ? $data['name_kana'] : '̤��Ͽ';
		$data['sex_cd'] = $this->decode_sex_cd($data['sex_cd']);
		$data['birthday'] = $this->date_format_j($data['birthday']);
		$data['zip_cd'] = $data['zip_cd'] ? '��Ͽ�Ѥ�' : '̤��Ͽ';
		$data['address1'] = $this->db->getArea1Name($data['address1']) ? $this->db->getArea1Name($data['address1']) : '̤��Ͽ';
		$data['address2'] = $data['address2'] ? '��Ͽ�Ѥ�' : '̤��Ͽ';
		$data['tel'] = $data['tel'] ? '��Ͽ�Ѥ�' : '̤��Ͽ';
		$data['self_introduction'] = $data['self_introduction'] ? $data['self_introduction'] : '̤��Ͽ';

		return $data;
	}

	// �����̥ǥ�����
	private function decode_sex_cd($code) {

		switch ($code) {
		case 1:
			return '��';
		case 2:
			return '��';
		default:
			return '̤��Ͽ';
		}
	}

	// �����շ���
	private function date_format_j($date) {
		if ($date) {
			sscanf($date, "%d-%d-%d", &$y, &$m, &$d);
			$date = $y . 'ǯ��' . $m . '�' . $d . '��';
			return $date;
		} else {
			return '̤��Ͽ';
		}
	}
}

?>