<?php

/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Weblinks
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: modifyLinkAction.class.php,v 1.5 2006/01/18 20:42:34 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/modules/Weblinks/forms/linkForm.class.php';
require_once MO_WEBAPP_DIR.'/modules/Weblinks/lib/category.class.php';
require_once MO_WEBAPP_DIR.'/modules/Weblinks/lib/link.class.php';
//require_once MO_WEBAPP_DIR.'/modules/Weblinks/lib/WeblinksBaseAction.class.php';

class ModifyLinkAction extends HNbEditAction {
	protected function getActionForm() {
		return new WeblinksLinkForm();
	}

	protected function getManager() {
		return new WeblinksLinkManager();
	}

	protected function fetchId() {
		return intval($this->request->getParameter('weblinks_link_id'));
	}
	protected function setUpNewObject($obj) {
		$obj->setAttribute('nol_navi_page_id', hitobito :: getNaviPageInfo()->getId());
		$obj->setAttribute('nol_member_id', $this->user->getAttribute('mbr_member_id'));
	}
	protected function isEdit($obj) {
		Navi::GuardNaviEdit($this->getContext()->getUser());
		//category がなかったらFALSE
		$categoryManager = new WeblinksCategoryManager();
//		if(!$categoryManager->getCategoriesCountByNaviPageId(hitobito::getNaviPageInfo()->getId())){
		if(!$categoryManager->getCount('1=1')){
			$this->request->setAttribute('message', HNb::tr('リンクを追加する前にカテゴリを追加してください'));
			return FALSE;
		}
		return TRUE;
	}

	public function isSecure() {
		return true;
	}
}
?>