<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ���̥桼�����������ƥꥹ�ȥ��������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class ListPublicAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		return View::INPUT;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		$request->setAttribute('waiwai_public_list_ary', $this->db->getPublicList($this->room_id));

		return View::INPUT;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::NONE;
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