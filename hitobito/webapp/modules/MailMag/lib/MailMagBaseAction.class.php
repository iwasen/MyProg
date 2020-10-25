<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ���ޥ����� �����������ܥ��饹
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagDatabase.class.php');

abstract class MailMagBaseAction extends Action {
	protected $melmaga_id;
	protected $member_id;
	protected $db;
	protected $action_mode;

	// �������
	public function initialize($context) {
		parent::initialize($context);

		$user = $context->getUser();

		// �ʥӥڡ���ID�����������å�
		if ($this->isNaviEditPage())
			Navi::GuardNaviEdit($user);

		// �ǡ����١�����³
		$this->db = new MailMagDatabase();

		// �ʥӥڡ���ID����
        $request = $context->getRequest();
		$this->navi_page_id = hitobito::getNaviPageInfo()->getId();
        $request->setAttribute('navi_page_id', $this->navi_page_id);

		// ���С�ID����
		$this->member_id = $user->getAttribute('mbr_member_id');

		// ���ޥ�ID����
		$this->melmaga_id = $this->db->getMelmagaId($this->navi_page_id);

		return true;
	}


	// ���ۿ����ޥ���������
	protected function getContentsList() {
        $context = $this->getContext();
        $request = $context->getRequest();

		$list_ary = $this->db->getMailMagList($this->melmaga_id);

        $request->setAttribute('mailmag_list_ary', $list_ary);
	}

	// ���ۿ����ޥ��������
	protected function getMelmagaContentsInfo($contents_id) {
        $context = $this->getContext();
        $request = $context->getRequest();

		$data = $this->db->getMailMagContents($contents_id);
        $request->setAttribute('mailmag_contents_data', $data);
	}

	// �����Ϲ�������
	protected function setMailMagInput() {
        $context = $this->getContext();
        $request = $context->getRequest();

		// ǯ
		$year_ary = array();
		$start_year = date('Y');
		$end_year = (date('Y') + 5);

		for ($send_year = $start_year; $send_year <= $end_year; $send_year++) {
			$year_ary[$send_year] = $send_year;
		}
        $request->setAttribute('mailmag_send_year_options', $year_ary);

		// ��
		$month_ary = array();
		for ($i = 1; $i <= 12; $i++) {
			$month_ary[$i] = $i;
		}
        $request->setAttribute('mailmag_send_month_options', $month_ary);

		// ��
		$day_ary = array();
		for ($i = 1; $i <= 31; $i++) {
			$day_ary[$i] = $i;
		}
        $request->setAttribute('mailmag_send_day_options', $day_ary);

		// ��
		$hour_ary = array();
		for ($i = 0; $i <= 23; $i++) {
			$hour_ary[$i] = $i;
		}
        $request->setAttribute('mailmag_send_hour_options', $hour_ary);

		// ʬ
		$minute_ary = array();
		for ($i = 0; $i <= 50; $i += 10) {
			$minute_ary[$i] = $i;
		}
        $request->setAttribute('mailmag_send_minute_options', $minute_ary);

		// �Хå��ʥ�С���������
		$open_flag = array();
		$open_flag['1'] = '��������';
		$open_flag['0'] = '�������ʤ�';
        $request->setAttribute('mailmag_open_flag', $open_flag);

		// �ȥ�å��Хå���������
		$trackback_flag = array();
		$trackback_flag['1'] = '����';
		$trackback_flag['0'] = '�����';
        $request->setAttribute('mailmag_trackback_flag', $trackback_flag);

		// �����ȵ�������
		$com_flag = array();
		$com_flag['1'] = '����';
		$com_flag['0'] = '�����';
        $request->setAttribute('mailmag_comment_flag', $com_flag);
	}

	// �����ޥ����ܾ������
	protected function getMelmagaBasicInfo() {
        $context = $this->getContext();
        $request = $context->getRequest();

		$data = array();
		if (count($request->getErrors()) > 0) {
			// POST�������
			$data['melmaga_name'] = $request->getParameter('mailmag_name');
			$data['melmaga_intro'] = $request->getParameter('mailmag_intro');
			$data['melmaga_footer'] = $request->getParameter('mailmag_footer');
			$data['backnumber'] = $request->getParameter('mailmag_backnumber') == null ? 1 : $request->getParameter('mailmag_backnumber');
			$data['notify_mail'] = $request->getParameter('mailmag_notify_mail') == null ? 1 : $request->getParameter('mailmag_notify_mail');
			$data['status'] = $request->getParameter('mailmag_status');
	        $request->setAttribute('melmaga_data', $data);
		} else {
			$data = $this->db->getMailMagBasic($this->melmaga_id);
		}
        $request->setAttribute('melmaga_data', $data);

		// ���ޥ���ǽ����
		$status = array();
		$status['1'] = '���Ѥ���';
		$status['9'] = '���Ѥ��ʤ�';
        $request->setAttribute('radio_status', $status);

		// �Хå��ʥ�С���������
		$bn = array();
		$bn['1'] = '����';
		$bn['2'] = '�����';
		$bn['3'] = '�ǿ���Τ߸���';

        $request->setAttribute('radio_backnumber', $bn);

		// ������Υ᡼������
		$nm = array();
		$nm['1'] = '��˾����';
		$nm['0'] = '��˾���ʤ�';

        $request->setAttribute('radio_notify_mail', $nm);
	}

	// ���ۿ����ޥ�POST����������
	protected function getEditPostInfo() {
        $request = $this->getContext()->getRequest();

		$data = array();

		// POST�������
		$data['contents_id'] = $request->getParameter('mailmag_contents_id');
		$data['subject'] = $request->getParameter('mailmag_subject');
		$data['body'] = $request->getParameter('mailmag_body');
		$data['send_year'] = $request->getParameter('mailmag_send_year');
		$data['send_month'] = $request->getParameter('mailmag_send_month');
		$data['send_day'] = $request->getParameter('mailmag_send_day');
		$data['send_hour'] = $request->getParameter('mailmag_send_hour');
		$data['send_minute'] = $request->getParameter('mailmag_send_minute');
		$data['open_flag'] = $request->getParameter('mailmag_open_flag') == null ? 1 : $request->getParameter('mailmag_open_flag');
		$data['comment_flag'] = $request->getParameter('mailmag_comment_flag') == null ? 1 : $request->getParameter('mailmag_comment_flag');
		$data['trackback_flag'] = $request->getParameter('mailmag_trackback_flag') == null ? 1 : $request->getParameter('mailmag_trackback_flag');
		$data['click_counter'] = $request->getParameter('mailmag_click_counter');
		$data['status'] = $request->getParameter('mailmag_status');

		// �ۿ����Υǥե���Ȥ�24���ָ�Ȥ���
		if ($data['send_year'] == '') {
			$send_date = time() + 24 * 60 * 60;
			$data['send_year'] = date('Y', $send_date);
			$data['send_month'] = date('n', $send_date);
			$data['send_day'] = date('j', $send_date);
			$data['send_hour'] = date('G', $send_date);
			$data['send_minute'] = (int)(date('i', $send_date) / 10) * 10;
		}

		if (count($request->getErrors()) == 0 && $request->getParameter('mailmag_footer') == '')
			$data['footer'] = $this->db->getMelmagaFooter($this->navi_page_id);
		else
			$data['footer'] = $request->getParameter('mailmag_footer');

        $request->setAttribute('mailmag_contents_data', $data);
	}

	// ��submit�ܥ�������å�
	protected function getSubmitButton() {
		$request = $this->getContext()->getRequest();

		$buttons = $request->getParameterNames();
		if (is_array($buttons)) {
			foreach ($buttons as $button) {
				if (preg_match('/^(.+_btn)_x$/', $button, $matches))
					return $matches[1];
			}
		}

		return null;
	}

	// ���ʥ��Խ��ڡ���
	protected function isNaviEditPage() {
		return false;
	}
}
?>