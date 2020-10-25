<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� �֤���ȯ����ʬ������ץ��������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class SendSelfAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		// ȯ���᡼������
		$remark_id = $this->db->getRemarkId($this->room_id, $request->getParameter('waiwai_id'));
		if ($remark_id)
			$this->sendRemark($remark_id, $this->member_id);

		// ������������
		$this->setBrowseCommon();

		// ȯ����������
		$this->setContents();

		// ��������С���������
		$this->setMemberInfo();

		return View::SUCCESS;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		// ������������
		$this->setBrowseCommon();

		// ȯ����������
		$this->setContents();

		// ��������С���������
		$this->setMemberInfo();

		return View::INPUT;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::POST;
	}

	// ��ǧ�ڤ��׵�
    public function isSecure() {
        return true;
    }
}
?>