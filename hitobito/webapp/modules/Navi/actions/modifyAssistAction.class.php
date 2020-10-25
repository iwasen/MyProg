<?php

/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: modifyAssistAction.class.php,v 1.2 2006/01/05 09:08:18 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/modules/Navi/forms/AssistNaviForm.class.php';
require_once MO_WEBAPP_DIR.'/modules/Navi/lib/assistNavi.class.php';

class ModifyAssistAction extends HNbEditAction {
	protected function getActionForm() {
		return new AssistNaviForm();
	}

	protected function getManager() {
		return new assistNaviManager();
	}

	protected function fetchId() {
		$navipage_id = hitobito::getNaviPageInfo()->getId();
		$navi_id = intval($this->request->getParameter('navi_navi_id'));
		return array('anv_navi_page_id' => $navipage_id, 'anv_navi_id' => $navi_id);
	}
	protected function setUpNewObject($obj) {
	}
	protected function isEdit($obj) {
		Navi::GuardNaviEdit($this->getContext()->getUser());
		return TRUE;
	}

	public function isSecure() {
		return true;
	}
}
?>