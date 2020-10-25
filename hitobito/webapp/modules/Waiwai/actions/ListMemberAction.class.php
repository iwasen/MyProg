<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ��ļ����С��������������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class ListMemberAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		$member_id = $request->getParameter('waiwai_member_id');

		switch ($this->getSubmitButton()) {
		case 'waiwai_to_admin_btn':		// �����Ԥˤ���
			$this->db->updateAdminFlag($this->room_id, $member_id, 1);
			break;
		case 'waiwai_to_member_btn':	// ���С����᤹
			$this->db->updateAdminFlag($this->room_id, $member_id, 0);
			break;
		case 'waiwai_mail_send_btn':	// �ۿ�
			$this->db->updateMailSendFlag($this->room_id, $member_id, 1);
			break;
		case 'waiwai_mail_stop_btn':	// ���
			$this->db->updateMailSendFlag($this->room_id, $member_id, 0);
			break;
		case 'waiwai_regist_cancel_btn':// ��Ͽ���
			$this->db->resignMember($this->room_id, $member_id);
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

	// �����ϥǡ��������å�
	public function validate() {
		$request = $this->getContext()->getRequest();

		if (!$request->getParameter('waiwai_member_id')) {
			switch ($request->getParameter('waiwai_list_kind')) {
			case 'admin':
				$request->setError('waiwai_member_id', '�����Ԥ�̤����Ǥ���');
				break;
			case 'member':
				$request->setError('waiwai_member_id', '���С���̤����Ǥ���');
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
		$sort_ary = array(1 => 'regist_date DESC', 2 => 'mail_addr', 3 => 'nickname', 4 => 'remark_num', 5 => 'last_date', 6 => 'mail_send_flag DESC', 7 => 'regist_date');

		// �����ԥꥹ�ȥ����Ƚ�
		$admin_sort = $request->getParameter('waiwai_admin_sort');
		if (!$admin_sort)
			$admin_sort = $user->getAttribute('waiwai_admin_sort');
		if (!$admin_sort)
			$admin_sort = 1;
		$user->setAttribute('waiwai_admin_sort', $admin_sort);
		$request->setAttribute('waiwai_admin_sort', $admin_sort);

		// �����ԥꥹ������
		$admin_list_ary = $this->db->getMemberList($this->room_id, 1, $sort_ary[$admin_sort]);
		$request->setAttribute('waiwai_admin_list_ary', $admin_list_ary);

		// ���С��ꥹ�ȥ����Ƚ�
		$member_sort = $request->getParameter('waiwai_member_sort');
		if (!$member_sort)
			$member_sort = $user->getAttribute('waiwai_member_sort');
		if (!$member_sort)
			$member_sort = 1;
		$user->setAttribute('waiwai_member_sort', $member_sort);
		$request->setAttribute('waiwai_member_sort', $member_sort);

		// ���С��ꥹ������
		$member_list_ary = $this->db->getMemberList($this->room_id, 0, $sort_ary[$member_sort]);
		$request->setAttribute('waiwai_member_list_ary', $member_list_ary);
	}

	// ��ǧ�ڤ��׵�
    public function isSecure() {
        return true;
    }

	// �����С���������
	private function setMemberData($member_id) {
		$request = $this->getContext()->getRequest();

		$member_data = $this->db->getMemberAttribute($member_id, $this->room_id);
		$request->setAttribute('waiwai_member_data', $member_data);
	}

	// ���ʥ��Խ��ڡ���
	protected function isNaviEditPage() {
		return true;
	}
}
?>