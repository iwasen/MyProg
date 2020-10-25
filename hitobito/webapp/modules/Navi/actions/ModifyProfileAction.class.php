<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: ModifyProfileAction.class.php,v 1.2 2006/01/05 06:36:50 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/lib/HNbActionForm.class.php';
require_once MO_WEBAPP_DIR.'/modules/Navi/forms/NaviProfileForm.class.php';
require_once MO_WEBAPP_DIR.'/hitobito/hitobitoNavipage.class.php';
class ModifyProfileAction extends HNbEditAction
{
	protected function fetchID()
	{
		return intval($this->request->getParameter('navipage_id'));
	}

	protected function getManager()
	{
		return new hitobitoNavipageManager();
	}
	
	protected function getActionForm()
	{
		return new NaviProfileForm();
	}

	protected function setUpNewObject(&$obj)
	{
		// =====================================================================
		// Object Init Code : オブジェクトの状況にあわせた初期化が必要ならここに書く
		// =====================================================================
		// (Ex.) $obj->setBlog_id($this->blog_id);
//		$obj->setAttribute('nav_member_id', $this->user->getAttribute('mbr_member_id')); 
	}

	protected function isEdit($obj)
	{
		// =======================================================================
		// Permission Check etc...  編集権限のチェックなどが必要ならここにコードを書く
		// ======================================================================= 
		Navi::GuardNaviEdit($this->user);
		return TRUE;
	}
	
	protected function isConfirm()
	{
		return FALSE;
	}

	
    public function isSecure()
    {
        return true;
    }
}

?>