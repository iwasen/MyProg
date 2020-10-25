<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ��ǧ������ꥹ��ɽ�����������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class EntryListAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		$submit = $this->getSubmitButton();
		switch($submit) {
		case 'waiwai_nickname_update_btn':
			$this->db->updateNickname($this->member_id, $request->getParameter('waiwai_nickname'));
			$request->setAttribute('waiwai_msg', '�˥å��͡���򹹿����ޤ�����');
			break;
		default:
			if (preg_match('/^waiwai_cancel_([0-9]+)_btn$/', $submit, $matches)) {
				$this->db->deleteRoomMember($matches[1], $this->member_id);
				$request->setAttribute('waiwai_msg', '�磻�磻��ļ��������ޤ�����');
			} elseif (preg_match('/^waiwai_stop_([0-9]+)_btn$/', $submit, $matches)) {
				$this->db->updateMailSendFlag($matches[1], array($this->member_id), 0);
				$request->setAttribute('waiwai_msg', '�᡼���ۿ����֤���ߤ����ꤷ�ޤ�����');
			} elseif (preg_match('/^waiwai_send_([0-9]+)_btn$/', $submit, $matches)) {
				$this->db->updateMailSendFlag($matches[1], array($this->member_id), 1);
				$request->setAttribute('waiwai_msg', '�᡼���ۿ����֤��ۿ������ꤷ�ޤ�����');
			}
			break;
		}

		return $this->getDefaultView();
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		$entry_list_ary = $this->db->getEntryList($this->member_id);
		$request->setAttribute('waiwai_entry_list_ary', $entry_list_ary);
		$request->setAttribute('user_page_url', $this->db->getConst('user_page_url'));

		return View::SUCCESS;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::POST;
	}

	// �����ϥǡ��������å�
	public function validate() {
		$request = $this->getContext()->getRequest();

		$valid = true;

		$submit = $this->getSubmitButton();
		switch($submit) {
		case 'waiwai_nickname_update_btn':
			// �˥å��͡����ʣ�����å�
			$nickname_ary = $request->getParameter('waiwai_nickname');
			if (is_array($nickname_ary)) {
				$err = false;
				foreach ($nickname_ary as $room_id => $nickname) {
					if ($this->db->checkNickname($room_id, $nickname, $this->member_id)) {
						$basic_info = $this->db->getBasicInfo($room_id);
						$request->setError("nockanme_$room_id", "�磻�磻��ļ���{$basic_info['name']}�פΥ˥å��͡����{$nickname}�פ�¾�������Ȥ��Ƥ��뤿���ѹ��Ǥ��ޤ���");
						$valid = false;
					}
				}
			}
			break;
		}

		return $valid;
	}

	// �����ϥ��顼����
	public function handleError() {
		$request = $this->getContext()->getRequest();

		$entry_list_ary = $this->db->getEntryList($this->member_id);

		if (is_array($entry_list_ary)) {
			$nickname_ary = $request->getParameter('waiwai_nickname');
			foreach ($entry_list_ary as &$entry_list)
				$entry_list['nickname'] = $nickname_ary[$entry_list['room_id']];
		}

		$request->setAttribute('waiwai_entry_list_ary', $entry_list_ary);
		$request->setAttribute('user_page_url', $this->db->getConst('user_page_url'));

		return View::SUCCESS;
	}

	// ��ǧ�ڤ��׵�
	public function isSecure() {
		return true;
	}
}
?>