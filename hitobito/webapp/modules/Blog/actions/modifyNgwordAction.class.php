<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: modifyNgwordAction.class.php,v 1.1 2006/01/09 14:31:36 ryu Exp $
 */

require_once MO_WEBAPP_DIR.'/lib/HNbActionForm.class.php';
require_once MO_WEBAPP_DIR.'/hitobito/db/t_blog_ngword.class.php';

require_once MO_WEBAPP_DIR.'/modules/Blog/forms/ngwordForm.class.php';

class modifyNgwordAction extends HNbEditAction
{
	protected function fetchID()
	{
		return intval($this->request->getParameter('blog_ngword_id', 0));
	}

	protected function getManager()
	{
		return new t_blog_ngwordObjectHandler();
	}
	
	protected function getActionForm()
	{
		return new BlogNgwordForm();
	}

	protected function setUpNewObject(&$obj)
	{
		// =====================================================================
		// Object Init Code : オブジェクトの状況にあわせた初期化が必要ならここに書く
		// =====================================================================
		$obj->setAttribute('bng_navi_page_id', hitobito::getNaviPageInfo()->getId());
		// (Ex.) $obj->setBlog_id($this->blog_id); 
	}

	protected function isEdit($obj)
	{
		// =======================================================================
		// Permission Check etc...  編集権限のチェックなどが必要ならここにコードを書く
		// ======================================================================= 
		Navi::GuardNaviEdit($this->getContext()->getUser());
		return true;
	}

	public function isSecure()
	{
		return true;
	}
}

?>
