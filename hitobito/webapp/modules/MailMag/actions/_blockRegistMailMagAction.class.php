<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ���ޥ���Ͽ/������������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseAction.class.php');

class _blockRegistMailMagAction extends MailMagBaseAction {
	// �����������¹�
	public function execute() {
		if(hitobito::getNaviPageInfo()->getMelmagaStatus() !=1){
			return View::NONE;
		}
		return View::INPUT;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		if(hitobito::getNaviPageInfo()->getMelmagaStatus() !=1){
			return View::NONE;
		}
		return View::INPUT;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::POST;
	}
}
?>