<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ���ޥ����� �ӥ塼���ܥ��饹
 *
 *
 * @package
 * @author
 * @version
 */

abstract class MailMagBaseView extends SmartyView {
	// �������
	public function initialize($context) {
		parent::initialize($context);

		// �ʥӥڡ���ID
        $request = $context->getRequest();
		$this->setAttribute('navi_page_id', $request->getAttribute('navi_page_id'));

		return true;
	}

	// ���ꥹ�ȥǡ�������
	protected function setListData() {
        $request = $this->getContext()->getRequest();

		// �ꥹ��ɽ��
		$list_ary = $request->getAttribute('mailmag_list_ary');

		if (is_array($list_ary)) {
			foreach ($list_ary as &$list) {
				$list['send_date'] = $this->format_datetime($list['send_date']);
				$list['status'] = $this->decode_status($list['status']);
			}
		}

		$this->setAttribute('mailmag_list_ary', $list_ary);
	}

	// ���ۿ����ޥ���������
	protected function setContentsData() {
        $request = $this->getContext()->getRequest();

		$contents_data = $request->getAttribute('mailmag_contents_data');
		$this->setAttribute('mailmag_contents_data', $contents_data);

		$open_flag = $request->getAttribute('mailmag_open_flag');
		$this->setAttribute('mailmag_open_flag', $open_flag);

		$trackback_flag = $request->getAttribute('mailmag_trackback_flag');
		$this->setAttribute('mailmag_trackback_flag', $trackback_flag);

		$comment_flag = $request->getAttribute('mailmag_comment_flag');
		$this->setAttribute('mailmag_comment_flag', $comment_flag);

		$options = $request->getAttribute('mailmag_send_year_options');
		$this->setAttribute('mailmag_send_year_options', $options);

		$options = $request->getAttribute('mailmag_send_month_options');
		$this->setAttribute('mailmag_send_month_options', $options);

		$options = $request->getAttribute('mailmag_send_day_options');
		$this->setAttribute('mailmag_send_day_options', $options);

		$options = $request->getAttribute('mailmag_send_hour_options');
		$this->setAttribute('mailmag_send_hour_options', $options);

		$options = $request->getAttribute('mailmag_send_minute_options');
		$this->setAttribute('mailmag_send_minute_options', $options);

		$action_mode = $request->getAttribute('action_mode');
		$this->setAttribute('action_mode', $action_mode);
	}

	// �����ޥ����ܾ�������
	protected function setMelmagaData() {
        $request = $this->getContext()->getRequest();

		$melmaga_data = $request->getAttribute('melmaga_data');
		$this->setAttribute('melmaga_data', $melmaga_data);

		$status = $request->getAttribute('radio_status');
		$this->setAttribute('status', $status);

		$backnumber = $request->getAttribute('radio_backnumber');
		$this->setAttribute('backnumber', $backnumber);

		$notify_mail = $request->getAttribute('radio_notify_mail');
		$this->setAttribute('notify_mail', $notify_mail);
	}

	// �����������������
	protected function setDeleteData() {
        $request = $this->getContext()->getRequest();

		$mail_addr = $request->getAttribute('mail_addr');
		$this->setAttribute('mail_addr', $mail_addr);
	}

	// ����������
	protected function format_datetime($date, $def = '', $separator_ymd = '.') {
		if ($date == '')
			return $def;
		else {
			sscanf($date, "%d-%d-%d %d:%d", &$y, &$m, &$d, &$h, &$i);
			return sprintf("%02d%s%02d%s%02d %02d:%02d", $y % 100, $separator_ymd, $m, $separator_ymd, $d, $h, $i);
		}
	}

	// ����������
	protected function format_date($date, $def='', $separator_ymd = '.') {
		if ($date == '')
			return $def;
		else {
			sscanf($date, "%d-%d-%d", &$y, &$m, &$d);
			return sprintf("%02d%s%02d%s%02d", $y % 100, $separator_ymd, $m, $separator_ymd, $d);
		}
	}

	// ���ꥹ�Ȥ��طʿ�����
	protected function setBgColor(&$list_ary, $first_color, $second_color) {
		if (is_array($list_ary)) {
			foreach ($list_ary as $i => &$list)
				$list['bgcolor'] = ($i % 2 == 0) ? $first_color : $second_color;
		}
	}

	// �����ơ������ǥ�����
	protected function decode_status($code, $def = '') {
		switch($code) {
		case 1:
			return '̤�ۿ�';
		case 2:
			return '�ۿ���';
		default:
			return $def;
		}
	}
}
?>