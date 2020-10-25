<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ��ļ������ȥ��С��������������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class ListGuestAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		$member_id = $request->getParameter('waiwai_member_id');

		switch ($this->getSubmitButton()) {
		case 'waiwai_admit_btn':		// ��ǧ
			$this->db->registMember($this->room_id, $member_id);
			$this->sendRegistMail($this->room_id, $member_id);
			break;
		case 'waiwai_mail_send_btn':	// �ۿ�
			$this->db->updateMailSendFlag($this->room_id, $member_id, 1);
			break;
		case 'waiwai_mail_stop_btn':	// ���
			$this->db->updateMailSendFlag($this->room_id, $member_id, 0);
			break;
		case 'waiwai_regist_cancel_btn':// ��Ͽ���
			$this->db->deleteMember($this->room_id, $member_id);
			break;
		}

		$this->setListData();

		return View::INPUT;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();
		$member_id = $request->getParameter('member_id');

		if ($member_id)
			$this->setMemberData($member_id);
		else
			$this->setListData();

		return View::INPUT;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::POST;
	}

	// �����ϥ����å�
	public function validate() {
		$request = $this->getContext()->getRequest();

		if (!$request->getParameter('waiwai_member_id')) {
			switch ($request->getParameter('waiwai_list_kind')) {
			case 'guest':
				$request->setError('waiwai_member_id', '�����ȥ��С���̤����Ǥ���');
				break;
			}
			return false;
		}

		return true;
	}

	// �����ϥ��顼����
	public function handleError() {
		$this->setListData();

		return View::INPUT;
	}

	// ���ꥹ�ȥǡ�������
	private function setListData() {
		$context = $this->getContext();
		$request = $context->getRequest();
		$user = $context->getUser();
		$sort_ary = array(1 => 'regist_date DESC', 2 => 'mail_addr', 3 => 'nickname', 4 => 'mail_send_flag DESC', 5 => 'regist_date');

		// �����ȥ��С��ꥹ�ȥ����Ƚ�
		$guest_sort = $request->getParameter('waiwai_guest_sort');
		if (!$guest_sort)
			$guest_sort = $user->getAttribute('waiwai_guest_sort');
		if (!$guest_sort)
			$guest_sort = 1;
		$user->setAttribute('waiwai_guest_sort', $guest_sort);
		$request->setAttribute('waiwai_guest_sort', $guest_sort);

		// �����ȥ��С��ꥹ������
		$guest_list_ary = $this->db->getguestList($this->room_id, $sort_ary[$guest_sort]);
		$request->setAttribute('waiwai_guest_list_ary', $guest_list_ary);
	}

	// ������᡼������
	private function sendRegistMail($room_id, $member_id) {
		// ��ļ��������
		$basic_info = $this->db->getBasicInfo($room_id);
		if (!$basic_info['regist_mail_flag'])
			return;

		// �᡼��ƥ�ץ졼�ȼ���
		$mail_template = $this->db->getMailTemplate('room_regist');

		// ����ԥ᡼�����
		$regist_mail = $this->db->getRegistMail($room_id);

		// ���������ѿ�����
		$var_ary = $this->getRoomVars($room_id);
		$mail_template['body'] = $this->replaceRoomVars($var_ary, $regist_mail['regist_mail'] != '' ? $regist_mail['regist_mail'] : $mail_template['body']);

		// ����᡼������
		$this->sendMailTemplate($room_id, $member_id, $mail_template);
	}

	// ���᡼��ƥ�ץ졼������
	private function sendMailTemplate($room_id, $member_id_ary, $mail) {
		// �����᡼��ǡ������å�
		$send_mail_id = $this->db->setSendMailData($mail['subject'], $mail['from'], $mail['reply_to'], $mail['body']);

		if (is_array($member_id_ary)) {
			foreach ($member_id_ary as $member_id) {
				// �ۿ�����С��������
				$member_info = $this->db->getMemberInfo($room_id, $member_id);
				if ($member_info) {
					// �ۿ���ꥹ������
					$this->db->setSendMailList($send_mail_id, $member_info['mail_addr']);
				}
			}
		}

		$this->db->setSendMailOk($send_mail_id);
	}

	// �����С���������
	private function setMemberData($member_id) {
		$request = $this->getContext()->getRequest();

		$member_data = $this->db->getMemberAttribute($member_id, $this->room_id);
		$request->setAttribute('waiwai_member_data', $member_data);
	}

	// ��ǧ�ڤ��׵�
    public function isSecure() {
        return true;
    }

	// ���ʥ��Խ��ڡ���
	protected function isNaviEditPage() {
		return true;
	}
}
?>