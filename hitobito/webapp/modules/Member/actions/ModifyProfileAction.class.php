<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Member
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: ModifyProfileAction.class.php,v 1.2 2006/01/22 13:12:17 ryu Exp $
 */

require_once MO_WEBAPP_DIR.'/lib/HNbActionForm.class.php';
require_once MO_WEBAPP_DIR. '/hitobito/db/t_navi.class.php';
require_once MO_WEBAPP_DIR. '/hitobito/db/t_navi_page.class.php';

require_once MO_WEBAPP_DIR.'/modules/Member/forms/registNaviForm.class.php';

class ModifyProfileAction extends HNbEditAction
{
	protected function fetchID()
	{
		return $this->user->getAttribute('nav_navi_id');
	}

	protected function getManager()
	{
		return HNb::getDBHandler('t_navi');
	}
	
	protected function getActionForm()
	{
		return new MemberModifyProfileForm();
	}

	protected function setUpNewObject(&$obj)
	{
		// =====================================================================
		// Object Init Code : ���֥������Ȥξ����ˤ��碌���������ɬ�פʤ餳���˽�
		// =====================================================================
		// (Ex.) $obj->setBlog_id($this->blog_id);
		$obj->setAttribute('nav_member_id', $this->user->getAttribute('mbr_member_id'));
		
	}

	protected function isEdit($obj)
	{
		// =======================================================================
		// Permission Check etc...  �Խ����¤Υ����å��ʤɤ�ɬ�פʤ餳���˥����ɤ��
		// ======================================================================= 
		if($this->user->isAuthenticated()){
			return TRUE;
		}else{
			$this->request->setAttribute('message', HNb::tr('�ʥӤ˱��礹��ˤϥ��С���Ͽ��ɬ�פǤ���'));
			return FALSE;
		}
	}
	
	protected function isConfirm()
	{
		return TRUE;
	}

	public function isSecure()
	{
		return TRUE;
	}
	
	public function execute()
	{
		$result = parent::execute();
		return $result; 
//		if($result == View::SUCCESS){
//			// ��̳�ɤإ᡼��
//			$master = $this->request->getAttribute('EditActionMaster');
//			$masterVars = $master->getAttributes();
//			$mailer = hitobito::getMailer();
//			$mailer->setAttribute('NAME', $masterVars['nav_name1'].' '.$masterVars['nav_name2']);
//			$mailer->setAttribute('MAIL', $this->user->getAttribute('mbr_mail_addr'));
//			
//			$mailer->loadTemplate('A_regist_navi');
//			$adminHandler = HNb::getDBHandler('m_admin');
//			$adminList = $adminHandler->getObjects('adm_status=1 AND adm_type=2'); // ͭ���ǡ���̳�ɥ���
//			foreach($adminList as $admin){
//				$mailer->send($admin->getAttribute('adm_mail_addr'));
//			}
//			// ����Ԥإ᡼��
//			$mailer->loadTemplate('regist_navi');
//			$mailer->send($this->user->getAttribute('mbr_mail_addr'));
//			return View::SUCCESS;
//		}else{
//			return $result;
//		}
	}
	
}

?>
