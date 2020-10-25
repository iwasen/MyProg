<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ȯ��������������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class DeleteRemarkAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		// ȯ����������
		$this->setContents();

		switch ($this->getSubmitButton()) {
		case 'delete_btn':		// ���

			// ȯ���������
			$remark_id = $this->db->getRemarkId($this->room_id, $request->getParameter('waiwai_id'));
			$this->db->deleteRemark($remark_id);

			// ������������
			$this->setBrowseCommon();

			return View::SUCCESS;

		case 'confirm_btn':		// ��ǧ

			// ������������
			$this->setBrowseCommon();

			return 'Confirm';

		case 'cancel_btn':		// ���
			return $this->getDefaultView();
		}
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		// ������������
		$this->setBrowseCommon();

		// ȯ����������
		$this->setContents();

		return View::INPUT;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::POST;
	}

	// ��Validator��Ͽ
	public function registerValidators($validatorManager) {
		$validatorManager->registerName('waiwai_id', TRUE, '�������ȯ����̤����Ǥ���');
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