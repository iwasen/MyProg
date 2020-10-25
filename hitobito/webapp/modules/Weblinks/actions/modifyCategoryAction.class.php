<?php

/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Weblinks
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: modifyCategoryAction.class.php,v 1.1 2005/12/12 08:35:38 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/modules/Weblinks/forms/categoryForm.class.php';
require_once MO_WEBAPP_DIR.'/modules/Weblinks/lib/category.class.php';
//require_once MO_WEBAPP_DIR.'/modules/Weblinks/lib/WeblinksBaseAction.class.php';

class ModifyCategoryAction extends HNbEditAction {
	protected function getActionForm() {
		return new WeblinksCategoryForm();
	}

	protected function getManager() {
		return new WeblinksCategoryManager();
	}

	protected function fetchId() {
		return intval($this->request->getParameter('weblinks_category_id'));
	}
	protected function setUpNewObject($obj) {
		$obj->setAttribute('lct_navi_page_id', hitobito :: getNaviPageInfo()->getId());
	}
	protected function isEdit($obj) {
		return TRUE;
	}

	public function isSecure() {
		return true;
	}
}
?>