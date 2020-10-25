<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ���ޥ����� �ۿ����ޥ�����ɽ�����������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseAction.class.php');

class ListMailMagAction extends MailMagBaseAction {
	// �����������¹�
	public function execute() {
		$controller = $this->getContext()->getController();
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		$user->setAttribute('mailmag_current_contents_id', $request->getParameter('contents_id'));

		switch ($this->getSubmitButton()) {
		case 'mailmag_edit_btn':
			// ���ޥ��Խ����̤�
			$request->setMethod(REQUEST::GET);
			$controller->forward('MailMag', 'EditContents');
			return View::NONE;
		case 'mailmag_show_click_btn':
			// ����å���ɽ��
			$controller->forward('MailMag', 'ShowClick');
			return View::NONE;
		}

		return $this->getDefaultView();;
	}


	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$this->getContentsList();

		return View::SUCCESS;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::POST;
	}

	// ��Validator��Ͽ
	public function registerValidators($validatorManager) {
		$validatorManager->registerName('contents_id', TRUE, '���ޥ���̤����Ǥ���');
	}

	// �����ϥ��顼����
	public function handleError() {
		return $this->getDefaultView();
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