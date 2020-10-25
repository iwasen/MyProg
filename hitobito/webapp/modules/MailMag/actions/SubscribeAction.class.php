<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ���ɥ��ޥ���Ͽ/������������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseAction.class.php');

class SubscribeAction extends MailMagBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		$mail_addr = $request->getParameter('mailmag_mail_addr');
		$member_id = $this->db->getMemberIdFromMailaddr($mail_addr);

		switch ($this->getSubmitButton()) {
		case 'mailmag_regist_btn':
			$this->db->insertMelmagaMember($this->melmaga_id, $member_id);
			$request->setAttribute('mailmag_template', 'Subscribe.html');
			break;
		case 'mailmag_release_btn':
			$this->db->deleteMelmagaMember2($this->melmaga_id, $member_id);
			$request->setAttribute('mailmag_template', 'Release.html');
			break;
		}

		$mailmag_info = $this->db->getMailMagNavi($this->melmaga_id);
		$mailmag_info['mail_addr'] = $mail_addr;
		$request->setAttribute('mailmag_info', $mailmag_info);

		$mailmag_list_ary = $this->db->getMailMagChannel($mailmag_info['channel_id'], $this->melmaga_id, $member_id);
		$request->setAttribute('mailmag_list_ary', $mailmag_list_ary);

		return View::SUCCESS;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::GET | Request::POST;
	}

	// ��Validator��Ͽ
	public function registerValidators($validatorManager) {
		$validatorManager->registerName('mailmag_mail_addr', true, '�᡼�륢�ɥ쥹��̤���ϤǤ���');
	}

	// �����ϥ����å�
	public function validate() {
		$request = $this->getContext()->getRequest();

		$mail_addr = $request->getParameter('mailmag_mail_addr');
		$member_id = $this->db->getMemberIdFromMailaddr($mail_addr);

		switch ($this->getSubmitButton()) {
		case 'mailmag_regist_btn':
			if ($member_id) {
				if ($this->db->checkMelmagaMember($this->melmaga_id, $member_id)) {
					$request->setError('mailmag_mail_addr', '���ޥ��Ϲ��ɺѤߤǤ���');
					return false;
				}
			} else {
				$request->setAttribute('mailmag_member_regist_flag', true);
				$request->setError('mailmag_mail_addr', '���ޥ�����ɤ��뤿��ˤϥ��С���Ͽ��ԤäƤ���������');
				return false;
			}
			break;
		case 'mailmag_release_btn':
			if (!$member_id || !$this->db->checkMelmagaMember($this->melmaga_id, $member_id)) {
				$request->setError('mailmag_mail_addr', '���Ϥ����᡼�륢�ɥ쥹�Ϥ��Υ��ޥ�����ɤ��Ƥ��ޤ���');
				return false;
			}
			break;
		}

		return true;
	}
}
?>