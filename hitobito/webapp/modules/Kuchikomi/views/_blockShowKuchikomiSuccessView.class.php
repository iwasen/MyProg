<?php
/**
 * �ЂƂтƥnet ���[�U�^�i�r�y�[�W
 *
 * �N�`�R�~WEB �ŐV�N�`�R�~���\���r���[
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Kuchikomi/lib/KuchikomiBaseView.class.php');

class _blockShowKuchikomiSuccessView extends KuchikomiBaseView
{
	// ��execute
	public function execute() {
		$request = $this->getContext()->getRequest();

		// �e���v���[�g�ݒ�
		$this->setTemplate('_blockShowKuchikomi.html');

		$this->setAttribute('kuchikomi_list_ary', $request->getAttribute('kuchikomi_list_ary'));
	}
}
?>