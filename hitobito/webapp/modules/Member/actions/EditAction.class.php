<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Member
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: EditAction.class.php,v 1.2 2006/01/23 03:19:22 ryu Exp $
 */

 require_once MO_WEBAPP_DIR . '/modules/Member/forms/accountForm.class.php';
 
class EditAction extends HNbEditAction
{
	
	protected function fetchID()
	{
		$user = $this->getContext()->getUser();
		return $user->getAttribute('mbr_member_id');
	}

	protected function getManager()
	{
		return HNb::getHandler('MemberManager');
	}
	
	protected function getActionForm()
	{
		return new MemberEditAccountForm();
	}

	protected function setUpNewObject(&$obj)
	{
		// =====================================================================
		// Object Init Code : ���֥������Ȥξ����ˤ��碌���������ɬ�פʤ餳���˽�
		// =====================================================================
		// (Ex.) $obj->setBlog_id($this->blog_id);
		$obj->setAttribute('mbr_hitobito_letter',1);
	}

	protected function isEdit($obj)
	{
		// =======================================================================
		// Permission Check etc...  �Խ����¤Υ����å��ʤɤ�ɬ�פʤ餳���˥����ɤ��
		// ======================================================================= 
		return TRUE;
	}
	
	protected function isConfirm()
	{
		return TRUE;
	}

	public function isSecure()
	{
		return TRUE;
	}
	
}
?>