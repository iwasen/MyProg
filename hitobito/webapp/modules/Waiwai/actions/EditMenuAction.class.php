<?php
/**
 * �ЂƂтƥnet ���[�U�^�i�r�y�[�W
 *
 * ���C���C��c�� �ҏW���j���[�A�N�V����
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class EditMenuAction extends WaiwaiBaseAction {
	// ���A�N�V�������s
	public function execute() {
		return View::NONE;
	}

	// ���f�t�H���g�r���[�\��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		// ���m�点�ݒ�
		$notice_flag['guest_member'] = $this->db->checkGuestMember($this->room_id);
		$notice_flag['public_post'] = $this->db->checkPublicPost($this->room_id);
		$request->setAttribute('waiwai_notice_flag', $notice_flag);

		return View::INPUT;
	}

	// ���A�N�V�������s���\�b�h�w��
	public function getRequestMethods() {
		return Request::NONE;
	}

	// ���F�؂�v��
    public function isSecure() {
        return true;
    }

	// ���i�r�ҏW�y�[�W
	protected function isNaviEditPage() {
		return true;
	}
}
?>