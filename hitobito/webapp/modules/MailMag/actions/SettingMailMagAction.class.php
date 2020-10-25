<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ���ޥ����� ���������Խ����������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseAction.class.php');

class SettingMailMagAction extends MailMagBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		$contents_id = $request->getParameter('mailmag_contents_id');
		$basic = $this->getParameters();

		$this->db->updateBasicInfo($this->melmaga_id, $basic);

		return View::SUCCESS;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		$this->getMelmagaBasicInfo();

		return View::INPUT;

	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::POST;
	}

	// ��Validator��Ͽ
	public function registerValidators($validatorManager) {
		$validatorManager->registerName('mailmag_name', TRUE, '���ޥ������ȥ뤬̤���ϤǤ���');
		$validatorManager->registerName('mailmag_intro', TRUE, '���ޥ��Ҳ�ʸ��̤���ϤǤ���');
	}

	// �����ϥ��顼����
	public function handleError() {
		return $this->getDefaultView();
	}

	// ���ѥ�᡼������
	protected function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['melmaga_name'] = $request->getParameter('mailmag_name');
		$params['melmaga_intro'] = $request->getParameter('mailmag_intro');
		$params['melmaga_footer'] = $request->getParameter('mailmag_footer');
		$params['backnumber'] = $request->getParameter('mailmag_backnumber');
		$params['notify_mail'] = $request->getParameter('mailmag_notify_mail');
		$params['status'] = $request->getParameter('mailmag_status');

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