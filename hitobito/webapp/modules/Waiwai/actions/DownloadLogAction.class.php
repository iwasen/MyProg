<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ȯ����������ɥ��������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class DownloadLogAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		$request->setAttribute('waiwai_download_data', $this->db->getRemarkLog($this->room_id));

		return View::SUCCESS;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		return View::NONE;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::GET | Request::POST;
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