<?php

/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: NewAssistAction.class.php,v 1.3 2006/01/18 09:26:19 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/modules/Navi/forms/AssistNaviForm.class.php';
require_once MO_WEBAPP_DIR.'/modules/Navi/lib/assistNavi.class.php';

class NewAssistAction extends HNbEditAction {
	protected function getActionForm() {
		return new NewAssistNaviForm();
	}

	protected function getManager() {
		return new assistNaviManager();
	}

	protected function fetchId() {
		$navipage_id = hitobito::getNaviPageInfo()->getId();
		$navi_id = intval($this->request->getParameter('navi_id'));
		return array('anv_navi_page_id' => $navipage_id, 'anv_navi_id' => $navi_id);
	}
	protected function setUpNewObject($obj) {
		$obj->setAttribute('anv_navi_page_id', hitobito::getNaviPageInfo()->getId());
		$obj->setAttribute('anv_status', 1);
		$obj->setAttribute('anv_navi_id', 0);
		$obj->setAttribute('nav_status', 1);
		
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