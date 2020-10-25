<?php
/**
 * �ЂƂтƥnet ���[�U�^�i�r�y�[�W
 *
 * �N�`�R�~WEB �ŐV�N�`�R�~���\���A�N�V����
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Kuchikomi/lib/KuchikomiBaseAction.class.php');

class _blockShowKuchikomiAction extends KuchikomiBaseAction {
	// ���A�N�V�������s
	public function execute() {
		return View::SUCCESS;
	}

	// ���f�t�H���g�r���[�\��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		// �`���l��ID�擾
		$channel_id = $this->db->getChannelId($this->navi_page_id);

		// �\�������擾
		$disp_num = $this->db->getConst('max_new_kuchikomi');

		// �ŐV�N�`�R�~���擾
		$list_ary = $this->db->getKuchikomiList($channel_id, $disp_num);
		if ($list_ary) {
			$request->setAttribute('kuchikomi_list_ary', $list_ary);

			return View::SUCCESS;
		} else
			return View::NONE;
	}

	// ���A�N�V�������s���\�b�h�w��
	public function getRequestMethods() {
		return Request::NONE;
	}
}
?>