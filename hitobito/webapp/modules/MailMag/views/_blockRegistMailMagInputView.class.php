<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ���ޥ���Ͽ/����ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class _blockRegistMailMagInputView extends MailMagBaseView {
	// ���ӥ塼�¹�
	function execute(){
		$this->setTemplate('_blockRegistMailMag.html');
	 }
}
?>