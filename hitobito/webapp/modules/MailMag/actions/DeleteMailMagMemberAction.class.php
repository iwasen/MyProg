<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ���ޥ����� ����������������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseAction.class.php');

class DeleteMailMagMemberAction extends MailMagBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		$contents_id = $request->getParameter('mailmag_contents_id');
		$mail_addr = $request->getParameter('mailmag_mail_addr');

		$this->db->deleteMelmagaMember($this->melmaga_id, $mail_addr);

		return View::SUCCESS;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		$mail_addr = $request->getParameter('mailmag_mail_addr');
		$request->setAttribute('mail_addr', $mail_addr);


		return View::INPUT;

	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::POST;
	}

	// ��Validator��Ͽ
	public function registerValidators($validatorManager) {
		$request = $this->getContext()->getRequest();

		$validatorManager->registerName('check_mail_addr', $this->checkMailAddr($error_msg), $error_msg);
	}

	// �����ϥ��顼����
	public function handleError() {
		return $this->getDefaultView();
	}

	// ���᡼�륢�ɥ쥹�����å�
	public function checkMailAddr(&$msg) {
		$request = $this->getContext()->getRequest();

		$mail_addr = $request->getParameter('mailmag_mail_addr');
		if ($mail_addr == '') {
			$msg = '�᡼�륢�ɥ쥹��̤���ϤǤ���';
			return true;
		} else {
			$msg = '���Ϥ����᡼�륢�ɥ쥹����Ͽ����Ƥ��ޤ���';
			return !$this->db->checkMailAddr($this->melmaga_id, $mail_addr);
		}
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