<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ���ܾ����Խ����������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class EditBasicAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		// ���ܾ��󹹿�
		$basic = $this->getParameters();
		$this->db->updateBasicInfo($this->room_id, $this->getNaviMemberId(), $basic);

		return View::SUCCESS;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		// ���ܾ�������
		$basic = $this->db->getBasicInfo($this->room_id);
		$basic['nickname'] = $this->db->getNickname($this->room_id, $this->getNaviMemberId());
		$request->setAttribute('waiwai_basic', $basic);

		return View::INPUT;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::POST;
	}

	// ��Validator��Ͽ
	public function registerValidators($validatorManager) {
		$validatorManager->registerName('waiwai_nickname', TRUE, '�ʥӥ˥å��͡��ब̤���ϤǤ���');
		$validatorManager->registerName('waiwai_name', TRUE, '��ļ�̾��̤���ϤǤ���');
//		$validatorManager->registerName('waiwai_room_code', TRUE, 'ML-NAME��̤���ϤǤ���');
		$validatorManager->registerName('waiwai_outline', TRUE, '��ļ����פ�̤���ϤǤ���');
		$validatorManager->registerName('waiwai_status', TRUE, '�������꤬̤����Ǥ���');
		$validatorManager->registerName('waiwai_regist_mail_flag', TRUE, '����᡼�뤬̤����Ǥ���');
		$validatorManager->registerName('waiwai_resign_mail_flag', TRUE, '���᡼�뤬̤����Ǥ���');
		$validatorManager->registerName('waiwai_header_flag', TRUE, '�إå���̤����Ǥ���');
		$validatorManager->registerName('waiwai_footer_flag', TRUE, '�եå���̤����Ǥ���');
		$validatorManager->registerName('waiwai_guest_regist_notify_flag', TRUE, '��������Ͽ���Τ�̤����Ǥ���');
		$validatorManager->registerName('waiwai_public_user_notify_flag', TRUE, '���̥桼���������������Τ�̤����Ǥ���');
	}

/* ML-NAME���Խ��Ǥ��ʤ��褦���ѹ�
	// �����ϥǡ��������å�
	public function validate() {
		$request = $this->getContext()->getRequest();

		$room_code = trim($request->getParameter('waiwai_room_code'));

		if (!preg_match('/^[0-9a-z_\-]+$/', $room_code)) {
			$request->setError('room_code', "ML-NAME��Ⱦ�Ѿ�ʸ���ѿ��������Ϥ��Ƥ���������");
			return false;
		}

		if ($this->db->checkRoomCode($this->room_id, $room_code)) {
			$request->setError('room_code', "���Ϥ��줿ML-NAME�Ϥ��Ǥ˻Ȥ��Ƥ��ޤ���");
			return false;
		}

		return true;
	}
*/

	// �����ϥ��顼����
	public function handleError() {
		$request = $this->getContext()->getRequest();

		// ���ܾ�������
		$basic = $this->getParameters();
		$request->setAttribute('waiwai_basic', $basic);

		return View::INPUT;
	}

	// ���ѥ�᡼������
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['nickname'] = $request->getParameter('waiwai_nickname');
		$params['name'] = $request->getParameter('waiwai_name');
//		$params['room_code'] = trim($request->getParameter('waiwai_room_code'));
		$params['outline'] = $request->getParameter('waiwai_outline');
		$params['status'] = $request->getParameter('waiwai_status');
		$params['regist_mail_flag'] = $request->getParameter('waiwai_regist_mail_flag');
		$params['resign_mail_flag'] = $request->getParameter('waiwai_resign_mail_flag');
		$params['header_flag'] = $request->getParameter('waiwai_header_flag');
		$params['footer_flag'] = $request->getParameter('waiwai_footer_flag');
		$params['guest_regist_notify_flag'] = $request->getParameter('waiwai_guest_regist_notify_flag');
		$params['public_user_notify_flag'] = $request->getParameter('waiwai_public_user_notify_flag');

		return $params;
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