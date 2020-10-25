<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Member
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: changeMailStatusAction.class.php,v 1.1 2006/01/13 10:44:39 ryu Exp $
 */

require_once MO_WEBAPP_DIR.'/lib/HNbActionForm.class.php';

require_once MO_WEBAPP_DIR.'/modules/Member/forms/changeMailStatusForm.class.php';

class changeMailStatusAction extends HNbEditAction
{
	protected function fetchID()
	{
		$user = $this->getContext()->getUser();
		return $user->getAttribute('id', 'Member');
	}

	protected function getManager()
	{
		return HNb::getHandler('MemberManager');
	}
	
	protected function getActionForm()
	{
		return new MemberChangeMailStatusForm();
	}

	protected function setUpNewObject(&$obj)
	{
		// =====================================================================
		// Object Init Code : オブジェクトの状況にあわせた初期化が必要ならここに書く
		// =====================================================================
		// (Ex.) $obj->setBlog_id($this->blog_id); 
	}

	protected function isEdit($obj)
	{
		// =======================================================================
		// Permission Check etc...  編集権限のチェックなどが必要ならここにコードを書く
		// ======================================================================= 

		return true;
	}

	public function isSecure()
	{
		return true;
	}
}

?>
